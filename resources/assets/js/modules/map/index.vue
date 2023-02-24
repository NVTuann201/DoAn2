<template>
  <div ref="mapContainer" class="map-content">
    <slot />
  </div>
</template>

<script>
import mapboxgl from "mapbox-gl";
import style from "./style.js";
export default {
  mounted() {
    this.isSupport = mapboxgl.supported();
    if (this.isSupport) {
      this.$nextTick(() => {
        this.init();
      });
    }
  },
  methods: {
    init() {
      const initOptions = Object.assign(
        {},
        {
          center: [105.85121154785156, 21.021700660816588],
          zoom: 5.297175623863693,
          maxZoom: 22,
        },
        this.initOptions
      );
      this.map = new mapboxgl.Map(
        Object.assign(
          {
            container: this.$refs.mapContainer,
            style,
          },
          initOptions
        )
      );
      this.map.touchZoomRotate.disableRotation();
      this.map.dragRotate.disable();
      this.map.once("load", () => {
        this.loaded = true;
        // Emit for parent component
        this.$emit("map-loaded", this.map);
      });
    },
  },
};
</script>

<style scoped>
.map-content {
  width: 100%;
  height: 100%;
}
</style>
<style>
.mapboxgl-ctrl-attrib {
  display: none !important;
}
</style>
