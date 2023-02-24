<template>
  <div class="submenu-wrapper rtl-bootstrap">
    <section class="horizontal-stripe white-background">
      <div class="container--desktop" style="margin-bottom: 2rem">
        <div class="row">
          <div class="col-xs-12 col-md-12">
            <div class="card">
              <div class="card__content card__content--warning">
                <p>
                  <span
                    >{{ $t("nbds_lang.protected_areas.sub_loc") }}:
                    <span v-if="!(data.quan_huyen instanceof Array)">
                      {{
                        data.quan_huyen
                          ? data.quan_huyen.name_vietnamese
                          : $t("common.unknown")
                      }}
                    </span>
                    <template v-else>
                      <span v-if="data.quan_huyen.length === 0">{{
                        $t("common.unknown")
                      }}</span>
                      <span v-else>
                        {{
                          data.quan_huyen
                            .map((item) => item.name_vietnamese)
                            .join(", ")
                        }}
                      </span>
                    </template>
                  </span>
                </p>
              </div>
            </div>
            <div class="m-t-1">
              <h4 class="card-header"><p>KHÔNG GIAN</p></h4>
              <div style="min-height: 300px; width: 100%; position: relative">
                <Map
                  @map-loaded="initMap"
                  style="position: absolute; top: 0; bottom: 0; width: 100%"
                />
              </div>
            </div>
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
    initMap(map) {
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
