<template>
  <page-filter
    :titleFilter="$t('nbds_lang.submenu_traditional_knowledge')"
    :titleSearch="$t('nbds_lang.submenu_traditional_knowledge_desc')"
    :count_result="count_result"
    :page.sync="page"
    :page_count="count"
    :items="data"
    :loading="loading"
    :search.sync="keyword"
    @click:clearAllFilter="clearAllFilter"
    @click:search="search"
    @click:phantrang="clickCallback"
  >
    <template v-slot:extra-filter>
      <div class="filter-group ng-isolate-scope">
        <filter-choose
          :title="$t('nbds_lang.genetic_group_traditional_knowledge')"
          v-model="filterNhom"
          :options="nhomtrithucs"
        >
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
            <div
              class="uppercase-first inherit searchCard__type hoverBox ng-scope"
            >
              {{ item.nhom ? item.nhom.name : "" }}
            </div>
            <h3 class="searchCard__headline" dir="auto">
              {{ item.ten }}
            </h3>
          </div>
        </div>
        <div class="card__stripe">
          <div class="card__content searchCard__content clearfix">
            <div>
              Giấy chúng nhận: <b>{{ item.cap_giay_chung_nhan }}</b>
            </div>
            <div>
              <div>Người lưu giữ: {{ item.nguoi_luu_giu }}</div>
              <div>Nơi lưu giữ: {{ item.noi_luu_giu }}</div>
            </div>
            <br />
            <div>
              <div>
                {{ item.mo_ta }}
              </div>
              <div v-if="item.ghi_chu">Ghi chú: {{ item.ghi_chu }}</div>
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
  props: ["nhomtrithucs"],
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
      resourceFilter: [],
      filter_region: [],
      filter_subloc: [],
      paramsProvince: {},
      filterNhom: [],
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
  },
  watch: {
    filter_subloc: function () {
      this.search();
    },
    filterNhom() {
      this.search();
    },
    keyword: function (val) {
      this.search();
    },
  },
  mounted() {
    this.getParamSearch()
    this.searchWithRank();
  },
  methods: {
    search() {
      this.searchWithRank(1);
    },
    getParamSearch() {
      var url_string = window.location.href;
      var url = new URL(url_string);
      let param = url.searchParams.get("nhom");
      if (param) {
        let nhom = this.nhomtrithucs.filter((el) => el.id == param);
        nhom.map((el) => (el.checked = true));
        this.filterNhom = nhom;
      }
    },
    clickTab(tab) {
      this.tab = tab;
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.searchWithRank();
    },
    searchWithRank(page = 1) {
      let params = {
        nhom: this.filterNhom.map((el) => el.id),
        search: this.keyword,
        page: this.page
      };
      this.loading = true;
      axios
        .get(env.endpointhttp + "knowledgedata", {
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
    clickCallback(pageNum) {
      this.page = pageNum;
      this.searchWithRank(pageNum);
    },
    clearAllFilter: function () {
      this.filterNhom = [];
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
