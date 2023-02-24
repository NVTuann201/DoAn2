<template>
  <page-filter
    :titleFilter="$t('nbds_lang.submenu_ecosystem_services')"
    :titleSearch="$t('nbds_lang.submenu_ecosystem_services_desc')"
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
        <filter-search
          :title="$t('nbds_lang.label_adv_srch_pa')"
          field_name="name"
          other_name="orig_name"
          :route_suggest="gettopdatasetforprotectedare"
          :route_search="asyncprotectedarea"
          v-model="filter.khu_bao_ton"
        >
        </filter-search>
        <filter-container
          :title="$t('nbds_lang.ecosystem_services_value_price')"
          v-model="filterDistribution"
        >
          <template v-slot:defail-filter="{ open }">
            <div class="is-padded mt-2" v-show="open">
              <div class="d-flex">
                <div style="width: 10px">Từ</div>
                <input
                  placeholder="  Nhập giá trị"
                  class="inputcustom"
                  v-model="filter.luong_gia.tu"
                  type="number"
                />
              </div>
              <br />
              <div class="d-flex">
                <div style="width: 10px">Đến</div>
                <input
                  placeholder="  Nhập giá trị"
                  class="inputcustom"
                  v-model="filter.luong_gia.den"
                  type="number"
                />
              </div>
              <div style="padding-top: 10px; padding-bottom: 10px">
                <b-button variant="success" @click="search()" size="sm"
                  >Lọc</b-button
                >
              </div>
            </div>
          </template>
        </filter-container>
      </div>
    </template>
    <template v-slot:item="{ item }">
      <div class="card m-b-05 searchCard rtl-supported rtl-bootstrap ng-scope">
        <div class="card__stripe">
          <div class="card__content">
            <div
              class="uppercase-first inherit searchCard__type hoverBox ng-scope"
            >
              {{ item.phan_loai ? item.phan_loai.name : "" }}
            </div>
            <h3 class="searchCard__headline" dir="auto">
              {{ item.ten }}
            </h3>
          </div>
        </div>
        <div class="card__stripe">
          <div class="card__content searchCard__content clearfix">
            <div style="padding-bottom: 15px">
              Giá trị lượng giá: <b>{{ item.gia_tri_luong_gia }}</b>
            </div>
            <div>
              <b>Địa điểm:</b>
              <div v-if="item.he_sinh_thai && item.he_sinh_thai">
                <div v-for="it in item.he_sinh_thai" :key="it.id">
                  - <span>{{
                    "Hệ sinh thái: "+ it.ten

                  }}</span>
                  <span v-if="it.dia_diem">{{
                    " - Khu bảo tồn: " + it.dia_diem.orig_name
                  }}</span>
                </div>
              </div>
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
  props: ["phanloais"],
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
      filterBehavior: [],
      filterEnvironment: [],
      filterDiversity: [],
      filterDistribution: [],
      filterEndangered: [],
      filterSource: [],
      filter: {
        phan_loai: [],
        khu_bao_ton: [],
        luong_gia: {
          tu: null,
          den: null,
        },
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
  },
  mounted() {
    this.searchWithRank();
  },
  watch: {
    "filter.khu_bao_ton": function () {
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
        khu_bao_ton: this.filter.khu_bao_ton.map((el) => el.id),
        luong_gia_tu: this.filter.luong_gia.tu,
        luong_gia_den: this.filter.luong_gia.den,
      };
      this.loading = true;
      axios
        .get(env.endpointhttp + "ecosystemservicedata", {
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

    detail(id) {
      window.location.href = "/detail/indexspecies/" + id;
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.searchWithRank(pageNum);
    },
    clearAllFilter: function () {
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
.inputcustom {
  background: white;
  height: 28px;
  border: 1px solid #cacfd2;
  border-radius: 6px;
}
a:hover {
  text-decoration: none !important;
}

.circular {
  width: 100px !important;
}
</style>
