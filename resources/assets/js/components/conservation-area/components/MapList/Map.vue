<template>
  <Map @map-loaded="onMapLoaded">
    <MapSearch :data="data" :detailUrl="detailUrl" />
    <MapLegend :legends="legends" :showLegend="showLegend" />
    <button @click="showLegend = !showLegend" class="button__on__map">
      <i class="fa fa-list" aria-hidden="true"></i>
    </button>

    <slot />
  </Map>
</template>
<script>
import MapSearch from "./MapSearch.vue";
import MapLegend from "./MapLegend.vue";
import Map from "@/modules/map/index.vue";
function getRandomColor() {
  const letters = "0123456789ABCDEF";
  let color = "#";
  for (let i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}
export default {
  components: { MapSearch, MapLegend, Map },
  props: ["mapUrl", "mapListGetters", "detailUrl"],
  data() {
    return {
      map: null,
      showLegend: false,
      legends: [],
      data: [],
    };
  },

  methods: {
    onMapLoaded(map) {
      this.map = map;
      this.getMapData();
    },
    async getMapData() {
      const { data } = await axios.get(this.mapUrl);
      const { data: objs, legends } = data;
      legends.push({ id: 0, name: "Khác" });
      legends.forEach((l) => (l.color = getRandomColor()));
      this.legends = legends;

      this.data = this.mapListGetters.data(objs).map((item) => ({
        type: "Feature",
        geometry: item.geometry,
        properties: {
          id: item.id,
          name: item.name,
          type: item.type,
          color:
            (legends.find((l) => l.name === item.type) || {}).color || "#000",
        },
      }));
      this.map.resize();
      this.map.fitBounds(
        [
          105.2837938106118, 20.565243447434938, 106.01828956622859,
          21.386262523122376,
        ],
        { duration: 0 }
      );
      this.addFeaturesToMap();
    },

    addFeaturesToMap() {
      for (const type of ["fill", "circle"]) {
        const geomTypes =
          type === "fill" ? ["Polygon", "MultiPolygon"] : ["Point"];
        const features = this.data.filter((f) =>
          geomTypes.includes(f.geometry.type)
        );
        this.map.addLayer({
          id: `${type}-data`,
          type,
          source: {
            type: "geojson",
            data: {
              type: "FeatureCollection",
              features,
            },
          },
          paint: {
            [`${type}-color`]: { type: "identity", property: "color" },
            [`${type}-opacity`]: 0.6,
          },
        });

        this.initFeatureEvent(`${type}-data`);
      }
    },

    initFeatureEvent(layerId) {
      this.map.on("mousemove", layerId, (e) => {
        this.map.getCanvas().style.cursor = "pointer";
        this.addSelectedFeatureLayer(e.features[0]);
      });
      this.map.on("mouseleave", layerId, () => {
        this.map.getCanvas().style.cursor = "";
        this.addSelectedFeatureLayer(null);
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
                    <a href="${this.detailUrl + feature.id}">${this.$t(
              "nbds_lang.link_record_detail"
            )}</a>
                  </div>
                </div>
              `
          )
          .addTo(this.map);
      });
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
  },
  watch: {},
};
</script>
<style lang="scss" scoped>
#map-list {
  width: 100%;
  height: 100%;
  .button__on__map {
    position: absolute;
    bottom: 20px;
    right: 20px;
    z-index: 100;
    padding: 10px;
    box-sizing: border-box;
    height: 35px;
    width: 35px;
    border-radius: 4px;
    background: white;
    box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
    i {
      font-size: 16px;
    }
    & :focus {
      box-shadow: 0 0 2px 2px #76b476;
    }
  }
}
</style>
