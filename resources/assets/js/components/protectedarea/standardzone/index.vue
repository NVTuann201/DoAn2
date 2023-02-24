<template>
  <page-filter
    :titleFilter="$t('nbds_lang.submenu_standard_zone')"
    :titleSearch="$t('nbds_lang.submenu_standard_zone_desc')"
    :count_result="count_result"
    :page.sync="page"
    :page_count="count"
    :items="data"
    :loading="loading"
    :search.sync="keyword"
    @click:clearAllFilter="clearAllFilter"
    @click:search="search"
    :showOther="isShowMap"
  >
    <template v-slot:extra-filter>
      <div class="filter-group ng-isolate-scope">
        <!-- <filter-choose
          title="Danh mục ô tiêu chuẩn"
          v-model="filterEnvironment"
          :options="[
            { id: 1, name: 'Ô tiêu chuẩn tạm thời' },
            { id: 2, name: 'Ô tiêu chuẩn cố định' },
          ]"
        >
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose> -->
        <filter-search
          :title="$t('nbds_lang.label_adv_srch_pa')"
          field_name="name"
          :route_suggest="gettopdatasetforprotectedare"
          :route_search="asyncprotectedarea"
          v-model="protectedareaFilter"
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
              {{ item.dia_diem ? item.dia_diem.orig_name : "" }}
            </div>
            <h3 class="searchCard__headline" dir="auto">
              {{ item.ten }}
            </h3>
          </div>
        </div>
        <div class="card__stripe">
          <div class="card__content searchCard__content clearfix">
            <div style="padding-bottom: 15px">
              Diện tích: <b>{{ item.dien_tich }} (ha)</b>
            </div>
            <div>
              <div>Khu sinh thái: {{ item.khu_sinh_thai }}</div>
              <div>Kích thước: {{ item.kich_thuoc }}</div>
              <div>Hình dạng: {{ item.hinh_dang }}</div>
              <div>Vị trí: {{ item.vi_tri }}</div>
            </div>
          </div>
        </div>
      </div>
    </template>
    <!-- <template v-slot:tabs>
      <ul>
        <li
          class="tab cursor-pointer"
          @click="clickTab()"
          :class="{
            isActive: !isShowMap,
          }"
        >
          <a>{{ $t("nbds_lang.all") }}</a>
        </li>
        <li
          class="tab cursor-pointer"
          @click="clickTab('Map')"
          :class="{ isActive: isShowMap }"
        >
          <a>{{ $t("nbds_lang.map") }}</a>
        </li>
        <li class="tab cursor-pointer">
          <a class="download"> </a>
        </li>
      </ul>
    </template> -->
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
  data: function () {
    return {
      tab: "table",
      page: 1,
      count: 0,
      data: [],
      keyword: null,
      count_result: null,
      filter_desig_type: null,
      loading: false,
      taxonFilter: [],
      protectedareaFilter: [],
      datasetFilter: [],
      statusFilter: [],
      statusOptions: [],
      paramsProvince: {},
      isShowMap: false,
      map: null,
    };
  },
  computed: {
    asyncprotectedarea() {
      return env.endpointhttp + routes.asynchronousprotectedareas;
    },
    gettopdatasetforprotectedare() {
      return env.endpointhttp + routes.asynchronousprotectedareas;
    },
  },
  mounted() {
    this.searchWithRank();
  },
  watch: {
    protectedareaFilter: function () {
      this.search();
    },
  },
  methods: {
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
        khu_bao_ton: this.protectedareaFilter.map((el) => el.id),
      };

      this.loading = true;
      axios
        .get(env.endpointhttp + "standardzonedata", {
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
      this.rankFilter = [];
      this.taxonFilter = [];
      this.datasetFilter = [];
      this.statusFilter = [];
      this.protectedareaFilter = [];
      this.resourceFilter = [];
      this.searchWithRank();
    },

    clickTab(type) {
      this.current_page = 1;
      this.isShowMap = type == "Map";
      if (type == "Map") {
        this.filter_desig_type = null;
        if (this.$refs.map) {
          this.$refs.map.resize();
        }
        return;
      }
      this.filter_desig_type = type;
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
