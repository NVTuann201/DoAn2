<template>
  <div class="full-height site-container">
    <div class="loading-roles" v-if="loading">
      <components-loading-page></components-loading-page>
    </div>
    <Map @map-loaded="onMapLoaded">
      <MapLegend
        :legends="legends"
        showLegend
        @layer-change="onLayerChange"
        :diaGioi="diaGioi"
      />
      <MapSearch :quan_huyen="quan_huyen" @search="getMapData($event)" />
    </Map>
  </div>
</template>
<script>
import Map from "@/modules/map/index.vue";
import MapLegend from "./MapLegend.vue";
import MapSearch from "./MapSearch.vue";
import huyen from "./huyen.json";
import tp from "./tp.json";
import xa from "./xa.json";
import bbox from "@turf/bbox";

export default {
  props: ["quan_huyen"],
  components: { MapLegend, MapSearch, Map },
  data() {
    return {
      map: null,
      showLegend: false,
      loading: false,
      selected: {},
      diaGioi: [
        {
          id: "xa",
          name: "Xã",
          data: xa,
          color: "#FFEB3B",
        },
        {
          id: "huyen",
          name: "Huyện",
          data: huyen,
          color: "#FF9800",
        },
        {
          id: "tp",
          name: "Thành phố",
          data: tp,
          color: "#03A9F4",
          show: true,
        },
      ],
      legends: [
        {
          id: 1,
          name: "Khu bảo tồn",
          color: "#F44336",
          show: true,
          key: "protectedAreas",
          url: "/detail/protectedarea/",
          type: "fill",
        },
        {
          id: 2,
          name: "Hệ sinh thái",
          color: "#4CAF50",
          show: true,
          key: "heSinhThais",
          url: "/search/ecosystem/",
          type: "fill",
        },
        {
          id: 3,
          name: "Cơ sở bảo tồn",
          color: "#3F51B5",
          show: true,
          key: "coSoBaoTons",
          url: "/search/ecosystem/",
          type: "circle",
        },
      ],
      data: [],
    };
  },

  methods: {
    onMapLoaded(map) {
      this.map = map;
      this.map.addControl(new mapboxgl.NavigationControl());
      this.getMapData();
      this.addDiaGioi();
    },
    async getMapData(query) {
      this.loading = true;
      const { data } = await axios.get("/search/mapv1", { params: query });

      for (const item of data.heSinhThais) {
        item.geometry = item.geom;
        item.type = item.he_sinh_thai ? item.he_sinh_thai.name : "";
        delete item.geom;
        delete item.he_sinh_thai;
        delete item.he_sinh_thai_lookup_id;
      }
      for (const item of data.coSoBaoTons) {
        item.geometry = item.geom;
        item.type = item.loai_hinh ? item.loai_hinh.name : "";
        delete item.geom;
        delete item.loai_hinh;
        delete item.loai_hinh_id;
      }

      this.addFeaturesToMap(data);
      this.loading = false;
    },

    addFeaturesToMap(data) {
      for (const l of this.legends) {
        if (this.map.getLayer(l.key)) {
          this.map.removeLayer(l.key);
        }
        if (this.map.getSource(l.key)) {
          this.map.removeSource(l.key);
        }
        this.map.addLayer({
          id: l.key,
          type: l.type,
          source: {
            type: "geojson",
            data: {
              type: "FeatureCollection",
              features: data[l.key].map((item) => ({
                type: "Feature",
                geometry: item.geometry,
                properties: {
                  id: item.id,
                  name: item.name,
                  type: item.type,
                  color: l.color,
                  key: l.key,
                },
              })),
            },
          },
          paint: {
            [`${l.type}-color`]: l.color,
            [`${l.type}-opacity`]: l.type == 'circle' ? 1 : 0.6,
          },
        });
        this.initFeatureEvent(l.key, l.url, l.type === "fill");
      }
    },
    initFeatureEvent(layerId, url, hoverable = false) {
      this.map.on("mousemove", layerId, (e) => {
        this.map.getCanvas().style.cursor = "pointer";
        if (hoverable) this.addSelectedFeatureLayer(e.features[0]);
      });
      this.map.on("mouseleave", layerId, () => {
        this.map.getCanvas().style.cursor = "";
        if (hoverable) this.addSelectedFeatureLayer(null);
      });

      this.map.on("click", layerId, (e) => {
        const feature = e.features[0].properties;
        new mapboxgl.Popup()
          .setLngLat(e.lngLat)
          .setHTML(
            `
                <div class="location-map">
                  <div class="location-name">${
                    this.$t(
                      "conservation-area.conservation-infrastructure.item.name"
                    ) +
                    ": " +
                    feature.name
                  }</div>
                  <div class="location-name">${
                    this.$t(
                      "conservation-area.conservation-infrastructure.item.type"
                    ) +
                    ": " +
                    feature.type
                  }</div>
                  <div class="location-detail">
                    <a href="${url + feature.id}" target="_blank">${this.$t(
              "nbds_lang.link_record_detail"
            )}</a>
                  </div>
                </div>
              `
          )
          .addTo(this.map);
      });
    },
    addDiaGioi() {
      for (const item of this.diaGioi) {
        this.map.addLayer({
          id: item.id,
          type: "line",
          source: {
            type: "geojson",
            data: item.data,
          },
          layout: {
            visibility: item.show ? "visible" : "none",
          },
          paint: {
            "line-color": item.color,
          },
        });
      }
      this.map.fitBounds(bbox(tp));
    },
    addSelectedFeatureLayer(feature) {
      if (JSON.stringify(feature) === JSON.stringify(this.selected)) return;
      if (this.map.getLayer("selected")) {
        this.map.removeLayer("selected");
      }
      if (this.map.getSource("selected")) {
        this.map.removeSource("selected");
      }
      if (!feature) {
        this.selected = {};
        return;
      }

      this.selected = feature;
      this.map.addLayer({
        id: "selected",
        type: "fill",
        source: {
          type: "geojson",
          data: {
            type: "FeatureCollection",
            features: [feature],
          },
        },
        paint: {
          "fill-color": feature.properties.color,
          "fill-opacity": 1,
        },
      });
    },

    onLayerChange(e) {
      this.map.setLayoutProperty(
        e.id,
        "visibility",
        e.value ? "visible" : "none"
      );
    },
  },
  watch: {},
};
</script>
<style lang="scss">
#map {
  width: 100%;
  height: 700px;
  margin-top: 25px;
}
</style>
