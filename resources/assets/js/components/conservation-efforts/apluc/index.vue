<template>
  <page-filter titleFilter="Áp lực lên ĐDSH" titleSearch="Thống kê áp lực lên đa dạng sinh học"
    :count_result="count_result" :page.sync="page" :page_count="count" :items="data" :loading="loading"
    :search.sync="keyword" @click:clearAllFilter="clearAllFilter" @click:search="search"
    @click:phantrang="clickCallback">
    <template v-slot:extra-filter>
      <div class="filter-group ng-isolate-scope">
        <FilterDay v-model="bat_dau" title="Thời gian bắt đầu"></FilterDay>
        <FilterDay v-model="ket_thuc" title="Thời gian kết thúc"></FilterDay>
        <filter-choose title="Phân cấp" v-model="phan_cap" :options="[...searchdata.phan_cap]">
          <template v-slot:name-filter="{ item }">
            <div style="display: flex">
              <div style="flex: 1">
                {{ item.name }}
              </div>
              <div>{{ item.so_luong }}</div>
            </div>
          </template>
        </filter-choose>

        <filter-choose title="Chỉ thị" v-model="chi_thi" :options="[...searchdata.chi_thi]">
          <template v-slot:name-filter="{ item }">
            <div style="display: flex">
              <div style="flex: 1">
                {{ item.ten }}
              </div>
              <div>{{ item.so_luong }}</div>
            </div>
          </template>
        </filter-choose>
      </div>
    </template>
    <template v-slot:item="{ item }">
      <div class="card m-b-05 searchCard rtl-supported rtl-bootstrap ng-scope">
        <div class="card__stripe">
          <div class="card__content" @click="detail(item)">
            <a class="uppercase-first inherit searchCard__type hoverBox ng-scope">
              {{ item.phan_cap.name }}
            </a>

            <h3 class="searchCard__headline" dir="auto">
              <a :name="item.chi_thi.ten" class="ng-isolate-scope">
                <div>
                  {{ item.chi_thi.ten }}
                </div>
              </a>
            </h3>
          </div>
        </div>
        <div class="card__stripe">
          <div class="card__content searchCard__content clearfix">
            <div class="discreet classification-list ng-binding ng-scope">
              <!-- <div>Chỉ thị: {{ item.chi_thi.ten }}</div> -->
              <!-- <div>Phân cấp: {{ item.phan_cap.name }}</div> -->
              <div>Thời gian bắt đầu: {{ item.bat_dau }}</div>
              <div>Thời gian kết thúc: {{ item.ket_thuc }}</div>
              <div>Đơn vị báo cáo: {{ item.don_vi_bao_cao }}</div>
              <div>Giá trị: {{ item.gia_tri ? item.gia_tri + ' (' + item.don_vi_tinh + ')' : '' }}</div>
              <div>Kỳ báo cáo: {{ item.ky_bao_cao }}</div>
              <!-- <div>Mô tả: {{ item.mota }}</div> -->
            </div>
          </div>
        </div>
      </div>
    </template>
  </page-filter>
</template>

<script>
import { debounce } from "lodash";
import * as env from "../../../env.js";
import * as routes from "../../../routes.js";
import Multiselect from "vue-multiselect";
import TaxonTree from "@/modules/filter-group/taxontree.vue";
import FilterSearch from "@/modules/filter-group/filter-search.vue";
import FilterContainer from "@/modules/filter-group/filter-container.vue";
import FilterChoose from "@/modules/filter-group/filter-choose.vue";
import PageFilter from "../../layouts/page-filter.vue";
import DataTree from "@/modules/filter-group/datatree.vue";
import FilterDay from "../../filterDay.vue";

export default {
  props: ["searchdata"],
  components: {
    Multiselect,
    TaxonTree,
    DataTree,
    FilterContainer,
    FilterSearch,
    PageFilter,
    FilterChoose,
    FilterDay,
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
      paramsProvince: {},
      filter_years: [],
      bat_dau: [],
      phan_cap: [],
      chi_thi: [],
      ket_thuc: []
    };
  },
  computed: {
    getsublogs: function () {
      return env.endpointhttp + routes.getsubloc;
    },
    gettopsubloc: function () {
      return env.endpointhttp + routes.gettopsubloc;
    },
  },
  mounted() {
    this.searchWithRank();
    // console.log(this.searchdata)
  },
  watch: {
    bat_dau: function (val) {
      this.search();
    },
    ket_thuc: function () {
      this.search();
    },
    phan_cap: function (val) {
      this.search();
    },
    chi_thi : function () {
      this.search();
    }
  },
  methods: {
    search: debounce(function () {
      this.page = 1;
      this.searchWithRank();
    }, 200),
    detailSpecies(id) {
      window.location.href = "/detail/indexspecies/" + id;
    },
    clickCallback() {
      this.searchWithRank();
    },
    searchWithRank(page = 1) {
      let params = {
        page: this.page,
        search: this.keyword,
        bat_dau: this.bat_dau,
        ket_thuc: this.ket_thuc,
        phan_cap: this.phan_cap.map(x => x.id),
        chi_thi: this.chi_thi.map(x => x.id)
      };
      axios
        .get(env.endpointhttp + "ap-luc-search", {
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
    detail(item) {
      // console.log(item)
      window.location.href = "/detail/ap-luc/" + item.id;
    },
    clearAllFilter: function () {
      this.keyword = null;
      this.quan_huyen = [];
      this.phan_loai = [];
      this.nguy_co = [];
      this.searchWithRank();
    },
    searchAll() {
      this.setKeySearch(this.keyword);
      window.location.href = "/search";
    },
    setKeySearch(value) {
      this.$store.commit("setKeySearch", { amount: value });
    },
    distributionAddItem(item) {
      this.filterDistribution = [item];
    },
    setRankHigherSearch(x) {
      let item = Object.assign({}, x, {
        filter: { child_id: x[`${x.rank}_id`], rank: x.rank },
        id: x[`${x.rank}_id`],
      });
      if (
        !this.taxonFilter.find(
          (x) =>
            x.filter.child_id == item.filter.child_id &&
            x.filter.rank == item.filter.rank
        )
      ) {
        this.taxonFilter = [item];
        this.setRankSearch("species");
      }
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
