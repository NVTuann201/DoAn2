<template>
  <span>
    <div class="card__content card__content--warning">
        <p><span>{{ $t("nbds_lang.protected_areas.sub_loc") }}: {{ this.sub }}</span>
        </p>
    </div>
    <div class="m-t-1">
        <h4 class="card-header"><p></p></h4>
        <div style="min-height: 300px; width: 100%; position: relative;">
            <Map  @map-loaded="onMapLoaded"
              style="position: absolute; top: 0; bottom: 0; width: 100%;"
            ></Map>
        </div>
    </div>
  </span>
</template>

<script>
import Map from "@/modules/map/index.vue"
export default {
  components: { Map },
  props: ["value", "sub_loc"],
  data: function () {
    return {
      sub: "Không có thông tin",
      map: null,
      geojson: {},
    };
  },
  created() {
    if (this.sub_loc) {
      this.sub = this.sub_loc;
    }
    if (typeof this.value == "string") {
      this.geojson = JSON.parse(this.value);
    } else {
      this.geojson = this.value;
    }
  },
  methods: {
    onMapLoaded(map) {
      this.map = map;
      this.createMap();
    },
    createMap() {
      const vm = this;
      if (vm.geojson.type) {
        vm.map.on("load", function () {
          if (vm.geojson.type.includes("Polygon")) {
            const first = vm.geojson.coordinates[0][0][0];
            vm.map.setCenter(first);
          }
          vm.map.addLayer({
            id: "maine",
            type: "fill",
            source: {
              type: "geojson",
              data: {
                type: "Feature",
                geometry: vm.geojson,
              },
            },
            layout: {},
            paint: {
              "fill-color": "#145B14",
              "fill-opacity": 0.25,
              "fill-outline-color": "#145B14",
            },
          });
          setTimeout(() => {
            vm.map.resize();
          }, 0);
        });
      }
    },
  },
};
</script>
