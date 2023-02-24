<template>
  <div class="row">
    <div class="col-md-6">
      <label class="col-md-12">Kinh độ</label>
      <div class="col-md-12">
        <input
          type="number"
          v-model="longitude"
          name="description"
          class="form-control"
          placeholder="Kinh độ"
        />
      </div>
    </div>
    <div class="col-md-6">
      <label class="col-md-12">Vĩ độ</label>
      <div class="col-md-12">
        <input
          type="number"
          v-model="c_latitude"
          name="description"
          class="form-control"
          placeholder="Vĩ độ"
        />
      </div>
    </div>
    <components-notifications
      :typeInput="typeNotification"
      :textInput="textNotification"
    ></components-notifications>
    <div class="col-md-12">
      <button @click="openUploadDialog" class="btn btn-success">
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
    <div class="col-md-12">
      <div class="upload-map">
        <Map @map-loaded="onMapLoaded"> </Map>
      </div>
    </div>
  </div>
</template>

<script>
import Map from "@/modules/map/index.vue";
import { Marker } from "mapbox-gl";
export default {
  components: { Map },
  props: {
    latitude: {},
    longitude: {},
  },
  data() {
    return {
      typeNotification: null,
      textNotification: null,
      map: null,
      marker: null,
    };
  },
  computed: {
    c_latitude: {
      get() {
        return this.latitude;
      },
      set(value) {
        this.$emit("update:latitude", value);
      },
    },
    c_longitude: {
      get() {
        return this.longitude;
      },
      set(value) {
        this.$emit("update:longitude", value);
      },
    },
  },
  methods: {
    onMapLoaded(map) {
      this.map = map;
      this.$nextTick(() => {
        this.map.resize();
      });
      this.map.on("click", this.onClick);
      this.redraw();
    },
    onClick(event) {
      this.c_longitude = event.lngLat.lng;
      this.c_latitude = event.lngLat.lat;
      this.$nextTick(() => {
        this.redraw();
      });
      this.redraw();
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
      let geometry = geojson.geometry;
      if (geometry.type != "Point")
        return this.notify(1, "Chỉ hỗ trợ dữ liệu điểm");
      this.c_longitude = geometry.coordinates[0];
      this.c_latitude = geometry.coordinates[1];

      this.redraw();
    },
    redraw() {
      if (!this.map || !this.longitude || !this.latitude) return;
      if (!this.marker) {
        this.marker = new Marker();
      }
      this.marker.setLngLat([this.longitude, this.latitude]).addTo(this.map);
    },
  },
};
</script>

<style></style>

<style scoped>
.upload-map {
  position: relative;
  width: 100%;
  margin-top: 20px;
  height: 400px;
  z-index: 1;
}
</style>
