<template>
  <page-filter :titleFilter="$t('nbds_lang.submenu_genetic_resources')"
    :titleSearch="$t('nbds_lang.submenu_genetic_resources_desc')" :count_result="count_result" :number_of_filter="0"
    :page.sync="page" :page_count="count" :items="data" :loading="loading" :search.sync="keyword"
    @click:clearAllFilter="clearAllFilter" @click:search="search" @click:phantrang="clickCallback">
    <template v-slot:extra-filter>
      <div class="filter-group ng-isolate-scope">
        <!-- <filter-search
          :title="$t('nbds_lang.protected_areas.region')"
          :route_suggest="getregion"
          field_name="name_vietnamese"
          :route_search="getregion"
          v-model="filter_region"
        >
        </filter-search> -->
        <div style="display: flex; justify-content: center; padding-top: 10px">
          <button style="height: 30px; width: 100px; background: green; color: white" @click="download()">
            <span class="gb-icon-file-download"></span>
            Tải dữ liệu</button>
        </div>
        <filter-search title="Quận huyện" :route_suggest="getquanhuyen" field_name="name_vietnamese"
          :route_search="getquanhuyen" :showCount="false" v-model="filter.quan_huyen">
        </filter-search>

        <filter-choose :title="$t('nbds_lang.genetic.gen_group')" v-model="filter.nhom_gen"
          :options="filterData.nhom_gen">
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose>

        <filter-search :title="$t('nbds_lang.genetic.storage')" :route_suggest="getnoiluugiu" field_name="ten"
          :route_search="getnoiluugiu" :params="paramsProvince" :showCount="false" v-model="filter.noi_luu_giu">
        </filter-search>

        <filter-choose :title="$t('nbds_lang.genetic.storage_status')" v-model="filter.tinh_trang_luu_giu"
          :options="filterData.tinh_trang_luu_giu">
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose>

        <filter-choose :title="$t('nbds_lang.genetic.usage_status')" v-model="filter.tinh_trang_su_dung"
          :options="filterData.tinh_trang_su_dung">
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose>

        <filter-choose :title="$t('nbds_lang.genetic.rare')" v-model="filter.gen_quy_hiem"
          :options="filterData.gen_quy_hiem">
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose>

        <filter-choose :title="$t('nbds_lang.genetic.status')" v-model="filter.tinh_trang"
          :options="filterData.tinh_trang">
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose>

        <filter-choose :title="$t('nbds_lang.genetic.exploit_with_conditions')" v-model="filter.gen_co_dieu_kien"
          :options="filterData.gen_co_dieu_kien">
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose>
      </div>
    </template>
    <template v-slot:item="{ item }">
      <div class="card m-b-05 searchCard rtl-supported rtl-bootstrap ng-scope">
        <div class="card__stripe">
          <div class="card__content">
            <div class="uppercase-first inherit searchCard__type hoverBox ng-scope">
              {{ item.nhom_gen.ten }}
            </div>
            <h3 class="searchCard__headline" dir="auto">
              <a @click="detailGenetic(item.id)" :name="item.name" class="ng-isolate-scope">
                {{ item.ten || item.ten_thong_thuong || item.ten_khoa_hoc }}
              </a>
            </h3>
          </div>
        </div>
        <div class="card__stripe">
          <div class="card__content searchCard__content clearfix">
            <div>
              <div v-if="item.loai_gen">
                <b>{{ item.loai_gen.ten }}</b>
              </div>
              <div>{{ item.su_dung ? item.su_dung.name : "" }}</div>
              <div>{{ item.dac_diem }}</div>
            </div>
            <br />
            <div>
              <div v-if="item.noi_luu_gius">
                Nơi lưu giữ:
                <span v-for="it in item.noi_luu_gius" :key="it.id">{{it.ten}}</span>
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
export default {
  props: ['searchdata'],
  components: {
    Multiselect,
    TaxonTree,
    FilterContainer,
    FilterSearch,
    PageFilter,
    FilterChoose,
  },
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
      protectedareaFilter: [],
      statusFilter: [],
      statusOptions: [],
      rankFilter: [],
      rankOptions: [],
      isExport: 0,
      filter: {
        quan_huyen: [],
        nhom_gen: [],
        noi_luu_giu: [],
        tinh_trang_luu_giu: [],
        tinh_trang_su_dung: [],
        gen_quy_hiem: [],
        tinh_trang: [],
        gen_co_dieu_kien: [],
      },
      paramsProvince: {},
      filterData: [],
    };
  },
  computed: {
    getnoiluugiu: function () {
      return env.endpointhttp + "noiluugiusearch";
    },
    getregion: function () {
      return env.endpointhttp + routes.getregion;
    },
    getquanhuyen: function () {
      return env.endpointhttp + "quanhuyensearch";
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
  },
  created() {
    this.filterData = this.searchdata
    this.getParamSearch()
  },
  mounted() {
    this.searchWithRank();
  },
  watch: {
    keyword: function () {
      this.search();
    },
    "filter.quan_huyen": function () {
      this.search();
    },
    "filter.nhom_gen": function () {
      this.search();
    },
    "filter.noi_luu_giu": function () {
      this.search();
    },
    "filter.tinh_trang_luu_giu": function () {
      this.search();
    },
    "filter.tinh_trang_su_dung": function () {
      this.search();
    },
    "filter.tinh_trang": function () {
      this.search();
    },
    "filter.gen_co_dieu_kien": function () {
      this.search();
    },
  },
  methods: {
    search() {
      this.searchWithRank();
    },
    clickTab(tab) {
      this.tab = tab;
    },
    detailGenetic(id) {
      window.location.href = "/detail/genetic/" + id;
    },
    getParamSearch() {
      var url_string = window.location.href;
      var url = new URL(url_string);
      let param = url.searchParams.get("nhomGen");
      if (param) {
        let nhomgen = this.filterData.nhom_gen.filter((el) => el.id == param);
        nhomgen.map((el) => (el.checked = true));
        this.filter.nhom_gen = nhomgen;
      }
    },
    async download() {
      try {
        this.isExport = 1;
        const res = await this.searchWithRank()
        console.log(res)
        saveAs(
          new Blob([res.data]),
          `du_lieu_gen.xls`
        );
      } finally {
        this.isExport = false;
      }
    },
    async searchWithRank(page = 1) {
      let params = {
        quan_huyen: this.filter.quan_huyen.map((el) => el.id),
        nhom_gen: this.filter.nhom_gen.map((el) => el.id),
        noi_luu_giu: this.filter.noi_luu_giu.map((el) => el.id),
        tinh_trang_luu_giu: this.filter.tinh_trang_luu_giu.map((el) => el.id),
        tinh_trang_su_dung: this.filter.tinh_trang_su_dung.map((el) => el.id),
        tinh_trang: this.filter.tinh_trang.map((el) => el.id),
        gen_co_dieu_kien: this.filter.gen_co_dieu_kien.map((el) => el.id),
        search: this.keyword,
        page: this.page,
        is_export: this.isExport
      };
      if (this.isExport == 1) {
        var response = await axios
          .get(env.endpointhttp + "geneticdata", {
            params,
            responseType: "blob"
          })
        this.loading = false;
        this.isExport = false
        return response
      }
      else{
        this.loading = true;
        var response = await axios
        .get(env.endpointhttp + "geneticdata", {
          params,
        })
        this.loading = false;
        this.data = response.data.data;
        this.count = response.data.last_page;
        this.count_result = response.data.total;
        this.page = response.data.current_page;
        $(".stickyNav").addClass("hasOffset");
      }
      
    },

    async getDataSearch() {
      let response = await axios.get(env.endpointhttp + "geneticdatasearch");
      this.filterData = response.data;
    },
    detail(id) {
      window.location.href = "/detail/indexspecies/" + id;
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.searchWithRank();
    },
    clearAllFilter: function () {
      this.filter = {
        quan_huyen: [],
        nhom_gen: [],
        noi_luu_giu: [],
        tinh_trang_luu_giu: [],
        tinh_trang_su_dung: [],
        gen_quy_hiem: [],
        tinh_trang: [],
        gen_co_dieu_kien: [],
      };
      this.search = null;
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
