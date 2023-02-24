<template>
  <Map id="map-list" @map-loaded="initMap">
    <MapSearch :data="data" :detailUrl="detailUrl" />
    <MapLegend :legends="legends" :showLegend="showLegend" />
    <button @click="showLegend = !showLegend" class="button__on__map">
      <i class="fa fa-list" aria-hidden="true"></i>
    </button>
  </Map>
</template>
<script>
import Map from "@/modules/map/index.vue";
import bbox from "@turf/bbox";
import MapSearch from "./MapSearch.vue";
import MapLegend from "./MapLegend.vue";
function getRandomColor() {
  const letters = "0123456789ABCDEF";
  let color = "#";
  for (let i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}

export function getChartColor(index) {
  return colorChart[index] || getRandomColor();
}
const colorChart = [
  "#25CCF7",
  "#FD7272",
  "#54a0ff",
  "#00d2d3",
  "#1abc9c",
  "#2ecc71",
  "#3498db",
  "#9b59b6",
  "#34495e",
  "#16a085",
  "#27ae60",
  "#2980b9",
  "#8e44ad",
  "#2c3e50",
  "#f1c40f",
  "#e67e22",
  "#e74c3c",
  "#ecf0f1",
  "#95a5a6",
  "#f39c12",
  "#d35400",
  "#c0392b",
  "#bdc3c7",
  "#7f8c8d",
  "#55efc4",
  "#81ecec",
  "#74b9ff",
  "#a29bfe",
  "#dfe6e9",
  "#00b894",
  "#00cec9",
  "#0984e3",
  "#6c5ce7",
  "#ffeaa7",
  "#fab1a0",
  "#ff7675",
  "#fd79a8",
  "#fdcb6e",
  "#e17055",
  "#d63031",
  "#feca57",
  "#5f27cd",
  "#54a0ff",
  "#01a3a4",
];
export default {
  components: { MapSearch, MapLegend, Map },
  props: ["mapUrl", "mapListGetters", "detailUrl"],
  data() {
    return {
      sourceId: undefined,
      layerId: undefined,
      map: null,
      showLegend: false,
      legends: [],
      data: [],
    };
  },

  methods: {
    initMap(map) {
      this.map = map;
      this.getMapData();
      this.map.fitBounds(
        [
          105.2837938106118,
          20.565243447434938,
          106.01828956622859,
          21.386262523122376,
        ],
        { duration: 0 }
      );
      this.layerId = `show-geometry-control-layer-${this._uid}`;
      this.sourceId = `show-geometry-control-source-${this._uid}`;
      this.map.addSource(this.sourceId, {
        type: "geojson",
        data: {
          type: "FeatureCollection",
          features: [],
        },
      });
      this.map.addLayer({
        id: `${this.layerId}-polygons`,
        type: "fill", // For fill
        source: this.sourceId,
        filter: ["==", "$type", "Polygon"],
        paint: {
          "fill-color": { type: "identity", property: "color" },
          "fill-opacity": 0.6,
        },
      });
      this.map.addLayer({
        id: `${this.layerId}-points`,
        type: "circle",
        source: this.sourceId,
        filter: ["==", "$type", "Point"],
        paint: {
          "circle-color": { type: "identity", property: "color" },
          "circle-opacity": 0.6,
        },
      });
      this.initFeatureEvent(`${this.layerId}-polygons`);
      this.initFeatureEvent(`${this.layerId}-points`);
    },
    async getMapData(params) {
      if (!this.map) return [];
      const { data } = await axios.get(this.mapUrl, { params });
      const { data: objs, legends } = data;
      legends.push({ id: 0, name: "Khác" });
      legends.forEach((l, i) => (l.color = getChartColor(i)));
      this.legends = legends;
      this.data = [];
      if (objs.length > 0) {
        this.data = this.mapListGetters.data(objs).map((item) => ({
          type: "Feature",
          geometry: item.geometry,
          properties: {
            id: item.id,
            name: item.name,
            type: item.type,
            color: legends.find((l) => l.name === item.type).color,
          },
        }));
      }
      if (params && this.data.length > 0) {
        const bboxFil = bbox({
          type: "FeatureCollection",
          features: this.data,
        });
        this.map.fitBounds(bboxFil, {
          padding: 20,
          duration: 0,
        });
      }
      this.redraw();
      return this.data;
    },

    redraw() {
      this.$emit("change-data", this.data);
      let source = this.map.getSource(this.sourceId);
      if (source)
        source.setData({
          type: "FeatureCollection",
          features: this.data,
        });
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
    z-index: 10;
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
