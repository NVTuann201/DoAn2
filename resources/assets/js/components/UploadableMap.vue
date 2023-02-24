<template>
  <div>
    <components-notifications
      :typeInput="typeNotification"
      :textInput="textNotification"
    ></components-notifications>
    <div>
      <button
        @click="openUploadDialog"
        style="margin-right: 10px"
        class="btn btn-success"
      >
        Tải tệp lên
      </button>
      <input
        @change="onUpload"
        ref="file"
        type="file"
        style="display: none"
        accept=".geojson"
      />
      <span>Chỉ hỗ trợ file geojson</span>
    </div>
    <div class="upload-map">
      <Map @map-loaded="onMapLoaded">
        <slot />
      </Map>
    </div>
  </div>
</template>
<script>
import Map from "@/modules/map/index.vue";
import bbox from "@turf/bbox";
export default {
  components: { Map },
  props: ["geometry", "height"],
  data() {
    return {
      map: null,
      file: null,
      typeNotification: null,
      textNotification: null,
      feature: null,
      sourceId: null,
      layerId: null,
      color: "#2ecc71",
    };
  },

  methods: {
    onMapLoaded(map) {
      this.map = map;
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
        id: `${this.layerId}-areas-fill`,
        type: "fill", // For fill
        source: this.sourceId,
        filter: ["==", "$type", "Polygon"],
        paint: {
          "fill-color": this.color,
          "fill-opacity": 0.3,
        },
      });

      this.map.addLayer({
        id: `${this.layerId}-areas-outline`,
        type: "line", // For outline
        source: this.sourceId,
        filter: ["==", "$type", "Polygon"],
        paint: {
          "line-color": this.color,
          "line-width": 2,
        },
      });
      this.map.addLayer({
        id: `${this.layerId}-points`,
        type: "circle",
        source: this.sourceId,
        filter: ["==", "$type", "Point"],
        paint: {
          "circle-radius": 12,
          "circle-color": this.color,
          "circle-stroke-color": "black",
          "circle-stroke-width": 0.5,
        },
      });
      this.map.addLayer({
        id: `${this.layerId}-lines`,
        type: "line",
        source: this.sourceId,
        filter: ["==", "$type", "LineString"],
        paint: {
          "line-color": this.color,
          "line-width": 2,
        },
      });
      this.map.resize();
      this.redraw();
      this.$emit("map-loaded", map);
    },
    notify(type, text) {
      this.typeNotification = type;
      this.textNotification = text;
      setTimeout(() => {
        this.typeNotification = null;
        this.textNotification = null;
      }, 1000);
    },
    openUploadDialog() {
      this.$refs.file.click();
    },
    onUpload(e) {
      const file = e.target.files[0];
      if (!file) return;
      const ext = file.name.slice(file.name.lastIndexOf(".") + 1);
      if (ext !== "geojson") return this.notify(1, "Chỉ hỗ trợ file geojson");
      const reader = new FileReader();
      reader.onload = this.onReaderLoad;
      reader.readAsText(file);
      this.$refs.file.value = "";
    },
    onReaderLoad(event) {
      try {
        const geojson = JSON.parse(event.target.result);
        this.addFeature(geojson);
      } catch (error) {
        console.log(error);
        this.notify(1, "File không hợp lệ");
      }
    },
    addFeature(geojson) {
      this.geometry = geojson.geometry;
      this.redraw();
      this.fitBounds(this.geometry);
      this.$emit("change", geojson.geometry);
    },
    redraw() {
      if (!this.map || !this.geometry) return;
      const source = this.map.getSource(this.sourceId);
      if (source) {
        source.setData({
          type: "FeatureCollection",
          features: [
            { type: "Feature", geometry: this.geometry, properties: {} },
          ],
        });
      }
      this.fitBounds(this.geometry);
    },
    fitBounds(value) {
      if (!value || !value.type) return;
      let bboxFil = undefined;
      if (["Feature", "FeatureCollection"].includes(value.type)) {
        bboxFil = bbox(value);
      } else {
        bboxFil = bbox({
          type: "Feature",
          properties: {},
          geometry: value,
        });
      }
      this.map.resize();
      if (bboxFil && this.map)
        this.map.fitBounds(bboxFil, {
          padding: 50,
          duration: 0,
        });
    },
  },
  watch: {
    geometry() {
      this.redraw();
    },
  },
};
</script>
<style scoped>
.upload-map {
  width: 100%;
  margin-top: 20px;
  height: 400px;
  z-index: 1;
}
</style>
