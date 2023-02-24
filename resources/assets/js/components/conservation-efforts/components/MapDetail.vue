<template>
  <div class="submenu-wrapper rtl-bootstrap">
    <section class="horizontal-stripe--paddingless white-background">
      <div class="container--desktop" style="margin-bottom: 2rem">
        <div class="row">
          <div class="col-xs-12 col-md-12 body-text">
            <span>
              <div class="anchor-block--tabs">
                <p style="margin-top: 2.142857142855rem">
                  {{ $t("nbds_lang.label_region_region") }}:
                  {{ data.tinh_thanh ? data.tinh_thanh.name_vietnamese : "" }}
                </p>
              </div>
              <div style="min-height: 300px; width: 100%; position: relative">
                <Map @map-loaded="onMapLoaded"
                  style="position: absolute; top: 0; bottom: 0; width: 100%"
                ></Map>
              </div>
            </span>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import Map from "@/modules/map/index.vue";
import bbox from "@turf/bbox";
export default {
  components: { Map },
  props: ["data"],
  data() {
    return {
      map: null,
    };
  },

  methods: {
    onMapLoaded(map) {
      this.map = map;
      this.map.on("style.load", () => {
        const type = ["Polygon", "MultiPolygon"].includes(this.data.geom.type)
          ? "fill"
          : "circle";
        this.map.addLayer({
          id: `${type}-data`,
          type,
          source: {
            type: "geojson",
            data: {
              type: "FeatureCollection",
              features: [{ type: "Feature", geometry: this.data.geom }],
            },
          },
          paint: {
            [`${type}-color`]: "#145B14",
            [`${type}-opacity`]: 0.25,
          },
        });
        const source = this.map.getSource(`${type}-data`);
        console.log(source._data);
        this.map.fitBounds(bbox(source._data), {
          duration: 0,
        });
      });
    },
  },
  watch: {},
};
</script>
