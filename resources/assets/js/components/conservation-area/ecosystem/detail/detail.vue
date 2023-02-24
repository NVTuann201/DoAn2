<template>
  <div class="site-wrapper">
    <main class="main">
      <div class="viewContentWrapper">
        <div class="site-content">
          <div class="site-content__page">
            <div class="datasetKey2 light-background">
              <div class="wrapper-horizontal-stripes">
                <Header :data="headerData"></Header>
                <Tab :tabs="tabs" :tab="tab" @change="tab = $event" />
                <Info
                  v-if="tab === 1"
                  :data="serializedData"
                  :info="infoData"
                  :other="otherData"
                />
                <MapDetail v-if="tab === 2 && data.geom" :data="data" />
                <!-- <div v-if="tab === 5">
                  <div class="d-flex">
                    <div style="padding-left: 50px; padding-bottom: 50px">
                      <br />
                      <div class="row">
                        <div class="col-md-2">
                          <b-img
                            thumbnail
                            fluid
                            :src="
                              'https://picsum.photos/200/200/?image=' +
                              Math.floor(Math.random() * 100)
                            "
                            alt="Image 1"
                          ></b-img>
                        </div>
                        <div class="col-md-2">
                          <b-img
                            thumbnail
                            fluid
                            :src="
                              'https://picsum.photos/200/200/?image=' +
                              Math.floor(Math.random() * 100)
                            "
                            alt="Image 2"
                          ></b-img>
                        </div>
                        <div class="col-md-2">
                          <b-img
                            thumbnail
                            fluid
                            :src="
                              'https://picsum.photos/200/200/?image=' +
                              Math.floor(Math.random() * 100)
                            "
                            alt="Image 3"
                          ></b-img>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> -->
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
import Info from "../../components/Detail/Info.vue";

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
          label: "Hệ sinh thái",
        },
        {
          id: 2,
          label: this.$t("nbds_lang.dataset_resources.geographic_description"),
        },
        {
          id: 3,
          label: "Danh mục loài",
        },
        {
          id: 4,
          label: "Nguồn gen",
        },
        // {
        //   id: 5,
        //   label: "Bộ sưu tập",
        // },
      ];
    },
  },
  methods: {
    setHeaderData() {
      this.headerData.name = this.data.ten;
      this.headerData.type = this.data.he_sinh_thai
        ? this.data.he_sinh_thai.name
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