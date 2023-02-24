<template>
  <div class="site-wrapper">
    <main class="main">
      <div class="viewContentWrapper">
        <div class="site-content">
          <div class="site-content__page">
            <div class="datasetKey2 light-background">
              <div class="wrapper-horizontal-stripes">
                <Header :data="data" :type="type"></Header>
                <Tab :tabs="tabs" :tab="tab" @change="tab = $event" />
                <template v-if="tab === 1">
                  <TinhInfo :data="data" v-if="type === 'tinh'" />
                  <CoSoInfo :data="data" v-else />
                </template>

                <Result v-if="tab === 2" :data="data" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
<script>
import Tab from "../../components/Detail/Tab.vue";
import detailMixin from "../../components/Detail/detail.mixin";
import Header from "./Header.vue";
import TinhInfo from "./TinhInfo.vue";
import CoSoInfo from "./CoSoInfo.vue";
import Result from "./Result.vue";
import { info, other } from "./detail";
export default {
  mixins: [detailMixin],
  props: ["type"],
  components: { Header, Tab, TinhInfo, CoSoInfo, Result },
  data() {
    return {
      info,
      other,
      tab: 1,
      tabs: [
        {
          id: 1,
          label: "Thông tin chung",
        },
        {
          id: 2,
          label: "Kết quả",
        },
      ],
    };
  },
  methods: {
    setHeaderData() {
      this.headerData.name = this.data.so_van_ban;
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