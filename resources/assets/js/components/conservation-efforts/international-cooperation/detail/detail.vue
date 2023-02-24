<template>
  <div class="site-wrapper">
    <main class="main">
      <div class="viewContentWrapper">
        <div class="site-content">
          <div class="site-content__page">
            <div class="datasetKey2 light-background">
              <div class="wrapper-horizontal-stripes">
                <Header :data="headerData"></Header>
                <Info
                  :data="serializedData"
                  :info="infoData"
                  :other="otherData"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
<script>
import Header from "../../components/Detail/Header.vue";
import Tab from "../../components/Detail/Tab.vue";
import detailMixin from "../../components/Detail/detail.mixin";
import MapDetail from "../../components/MapDetail.vue";
import Info from "./Info.vue";

import { info, other } from "./detail";
export default {
  mixins: [detailMixin],
  components: { Header, Tab, MapDetail, Info },
  data() {
    return {
      tab: 1,
      info,
      other,
    };
  },
  computed: {
    tabs() {
      return [
        {
          id: 1,
          label: this.$t("conservation-area.biosphere-reserves.title"),
        },
        {
          id: 2,
          label: this.$t("nbds_lang.dataset_resources.geographic_description"),
        },
      ];
    },
  },
  methods: {
    setHeaderData() {
      this.headerData.name = this.data.ten;
      this.headerData.type = this.data.phan_loai
        ? this.data.phan_loai.name
        : "";
    },
  },
};
</script>
<style scoped>
.site-content__page {
  min-height: calc(100vh - 18.542857142848571428571428571429rem);
}
</style>