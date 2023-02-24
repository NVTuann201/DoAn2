<template>
  <page-filter
    :titleFilter="$t('nbds_lang.menu_ecosystem')"
    :titleSearch="$t('nbds_lang.menu_ecosystem_desc')"
    :count_result="count_result"
    :page.sync="page"
    :page_count="count"
    :items="data"
    :loading="loading"
    :search.sync="keyword"
    @click:clearAllFilter="clearAllFilter"
    @click:search="search"
  >
    <template v-slot:extra-filter>
      <div class="filter-group ng-isolate-scope">
        <filter-choose
          title="Hệ sinh thái"
          v-model="filter.he_sinh_thai"
          :options="hesinhthais"
        >
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose>

        <filter-choose
          title="Phân loại"
          v-model="filter.phan_loai"
          :options="phanloais"
        >
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose>

        <filter-search
          :title="$t('nbds_lang.protected_areas.sub_loc')"
          :route_suggest="gettinhthanh"
          field_name="name_vietnamese"
          :route_search="gettinhthanh"
          :showCount="false"
          v-model="filter.tinh_thanh"
        >
        </filter-search>
      </div>
    </template>
    <template v-slot:item="{ item }">
      <div class="card m-b-05 searchCard rtl-supported rtl-bootstrap ng-scope">
        <div class="card__stripe">
          <div class="card__content">
            <div
              class="uppercase-first inherit searchCard__type hoverBox ng-scope"
            >
              {{ item.he_sinh_thai ? item.he_sinh_thai.name : "" }}
            </div>
            <h3 class="searchCard__headline" dir="auto">
              {{ item.ten }}
            </h3>
          </div>
        </div>
        <div class="card__stripe">
          <div class="card__content searchCard__content clearfix">
            <p>
              <b>{{ item.phan_loai ? item.phan_loai.name : "" }} (ha)</b>
            </p>
            <div>
              Địa điểm (khu bảo tồn):
              <b>{{ item.dia_diem ? item.dia_diem.orig_name : "" }}</b>
            </div>
            <div>
              <div>
                Diện tích: <b>{{ item.dien_tich }}</b>
              </div>
              <div>
                Nguồn gốc: {{ item.nguon_goc ? item.nguon_goc.name : "" }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </page-filter>
</template>

<script>
import * as env from "../../../env.js";
import * as routes from "../../../routes.js";
import Multiselect from "vue-multiselect";
import TaxonTree from "@/modules/filter-group/taxontree.vue";
import FilterContainer from "@/modules/filter-group/filter-container.vue";
import FilterSearch from "@/modules/filter-group/filter-search.vue";
import FilterChoose from "@/modules/filter-group/filter-choose.vue";
import PageFilter from "../../layouts/page-filter.vue";
import DataTree from "@/modules/filter-group/datatree.vue";
export default {
  components: {
    Multiselect,
    TaxonTree,
    DataTree,
    FilterContainer,
    FilterSearch,
    PageFilter,
    FilterChoose,
  },
  props: ["hesinhthais", "phanloais"],
  data: function () {
    return {
      tab: "table",
      page: 1,
      count: 0,
      data: [],
      keyword: null,
      count_result: null,
      loading: false,
      taxonFilter: [],
      protectedareaFilter: [],
      datasetFilter: [],
      statusFilter: [],
      statusOptions: [],
      rankFilter: [],
      rankOptions: [],
      resourceFilter: [],
      filter_region: {},
      paramsProvince: {},
      filter: {
        tinh_thanh: [],
        phan_loai: [],
        he_sinh_thai: [],
      },
    };
  },
  computed: {
    getregion: function () {
      return env.endpointhttp + routes.getregion;
    },
    getsublogs: function () {
      return env.endpointhttp + routes.getsubloc;
    },
    gettopsubloc: function () {
      return env.endpointhttp + routes.gettopsubloc;
    },
    asyncdatasetresources() {
      return env.endpointhttp + routes.asyncdatasetresources;
    },
    asyncResourcetypes() {
      return env.endpointhttp + routes.getresource_type;
    },
    asyncprotectedarea() {
      return env.endpointhttp + routes.asynchronousprotectedareas;
    },
    gettopdatasetforprotectedare() {
      return env.endpointhttp + routes.asynchronousprotectedareas;
    },
    gettopdatasetfortaxon() {
      return env.endpointhttp + routes.gettopdatasetfortaxon;
    },
    gettinhthanh: function () {
      return env.endpointhttp + "tinhthanhsearch";
    },
  },
  created() {
    this.searchWithRank();
  },
  watch: {
    "filter.tinh_thanh": function () {
      this.search();
    },
    "filter.phan_loai": function () {
      this.search();
    },
    "filter.he_sinh_thai": function () {
      this.search();
    },
  },
  methods: {
    changeFilter() {
      console.log(this.filter);
    },
    search() {
      this.searchWithRank(1);
    },
    clickTab(tab) {
      this.tab = tab;
    },
    detailSpecies(id) {
      window.location.href = "/detail/indexspecies/" + id;
    },
    searchWithRank(page = 1) {
      let params = {
        tinh_thanh: this.filter.tinh_thanh.map((el) => el.id),
        he_sinh_thai: this.filter.he_sinh_thai.map((el) => el.id),
        phan_loai: this.filter.phan_loai.map((el) => el.id),
      };
      this.loading = true;
      axios
        .get(env.endpointhttp + "ecosystemdata", {
          params,
        })
        .then((response) => {
          this.data = response.data.data;
          this.count = response.data.last_page;
          this.count_result = response.data.total;
          this.loading = false;
          this.page = response.data.current_page;
        })
        .catch((error) => {
          console.log(error);
          this.loading = false;
        })
        .finally(() => {
          $(".stickyNav").addClass("hasOffset");
          this.loading = false;
        });
    },
    addRankFilter(strRank) {
      if (!this.rankFilter.find((x) => x.name == strRank)) {
        this.rankFilter.push(this.rankOptions.find((x) => x.name == strRank));
      }
    },
    detail(id) {
      window.location.href = "/detail/indexspecies/" + id;
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.searchWithRank(pageNum);
    },
    clearAllFilter: function () {
      this.hesinhthais.forEach((el, index) => {
        this.filter.phan_loai[el.id] = [];
        this.filter.khu_bao_ton[el.id] = [];
      });
      this.keyword = null;
      this.searchWithRank();
    },
  },
};
</script>
<style>
.multiselect__tags {
  border-radius: 0px !important;
}

.multiselect {
  margin-left: 10px !important;
  width: 90% !important;
}

a:hover {
  text-decoration: none !important;
}

.circular {
  width: 100px !important;
}
</style>
