<template>
  <page-filter :titleFilter="$t('nbds_lang.submenu_species')" :titleSearch="$t('nbds_lang.submenu_species_desc')"
    :count_result="count_result" :number_of_filter="number_of_filter" :page.sync="page" :page_count="count"
    :items="data" :loading="loading" :search.sync="keyword" @click:clearAllFilter="clearAllFilter"
    @click:search="search" @click:phantrang="clickCallback">
    <template v-slot:extra-filter>
      <div class="filter-group ng-isolate-scope">

        <div style="display: flex; justify-content: center; padding-top: 10px">
          <button style="height: 30px; width: 100px; background: green; color: white" @click="download()">
            <span class="gb-icon-file-download"></span>
            Tải dữ liệu</button>
        </div>

        <filter-search :title="$t('nbds_lang.submenu_dataset')" :route_suggest="gettopdatasetfortaxon"
          field_count="count" field_name="title" :route_search="asyncdatasetresources" v-model="datasetFilter">
        </filter-search>
        <filter-choose title="Danh mục phân loại Hà Nội" v-model="rankFilterHaNoi" :options="rankHnOptions">
          <template v-slot:name-filter="{ item }">
            {{ item.name | rankName }}
          </template>
        </filter-choose>
        <filter-choose title="Danh mục phân loại chung" v-model="rankFilter" :options="rankOptions">
          <template v-slot:name-filter="{ item }">
            {{ item.name | rankName }}
          </template>
        </filter-choose>
        <!-- <filter-search :title="$t('nbds_lang.label_adv_srch_pa')" field_name="name" other_name="name"
          :route_suggest="gettopdatasetforprotectedare" :route_search="asyncprotectedarea"
          v-model="protectedareaFilter">
        </filter-search> -->

        <filter-container title="Đối tượng bảo tồn">
          <template v-slot:defail-filter="{ open }">
            <div class="is-padded mt-2" v-show="open">
              <filter-choose v-for="dt in doiTuongBaoTons" :key="dt.name" :title="dt.name"
                v-model="filterDoiTuong[dt.code]" :options="doiTuong[dt.code]">
              </filter-choose>
            </div>
          </template>
        </filter-container>

        <!-- <filter-search title="Quận huyện" :route_suggest="gettopsubloc" field_name="sub_loc" :route_search="getsublogs"
          :params="paramsProvince" v-model="quan_huyen">
        </filter-search> -->

        <!-- <filter-search title="Nguồn gốc địa phương" :route_suggest="getTopPhanBo" field_name="phan_bo" :route_search="getTopPhanBoSearch"
          :params="paramsProvince" v-model="nguon_goc">
        </filter-search> -->
        <filter-choose title="Nguồn gốc địa phương" v-model="nguon_goc" :options="nguonGocs">
          <template v-slot:name-filter="{ item }">
            {{ item.phan_bo }}
          </template>
        </filter-choose>

        <!-- <filter-choose :title="$t('nbds_lang.species_environment')" v-model="filterEnvironment"
          :options="optionEnvironment">
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose>

        <filter-choose :title="$t('nbds_lang.species_diversity')" v-model="filterDiversity" :options="optionDiversity">
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose> -->

        <!-- <filter-container
          :title="$t('nbds_lang.species_distribution')"
          v-model="filterDistribution"
        >
          <template v-slot:defail-filter="{ open }">
            <div class="is-padded mt-2" v-show="open">
              <data-tree
                :data="distributionData"
                @click:item="distributionAddItem"
              ></data-tree>
            </div>
          </template>
        </filter-container> -->

        <filter-choose title="Môi trường sống" v-model="filterMoiTruongSong" :options="moiTruongSongs">
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose>

        <filter-choose :title="$t('nbds_lang.species_endangered')" v-model="filterEndangered"
          :options="optionEndangered">
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose>

        <!-- <filter-choose
          :title="$t('nbds_lang.species_source')"
          v-model="filterSource"
          :options="optionSource"
        >
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose> -->
        <!-- <filter-container :title="$t('nbds_lang.label_adv_srch_taxon')" v-model="taxonFilter">
          <template #name-filter="{ item }">
            {{ item.name_vietnamese || item.name || "Unknown" }}
          </template>
          <template v-slot:defail-filter="{ open }">
            <div class="is-padded mt-2" v-show="open" style="margin-left: -10px">
              <taxon-tree @click:item="taxonAddItem"></taxon-tree>
            </div>
          </template>
        </filter-container> -->
      </div>
    </template>
    <template v-slot:item="{ item }">
      <div class="card m-b-05 searchCard rtl-supported rtl-bootstrap ng-scope">
        <div class="card__stripe">
          <div class="card__content">
            <a @click="setRankSearch(item.rank)" style="cursor: pointer"
              class="uppercase-first inherit searchCard__type hoverBox ng-scope">
              {{ $t("nbds_lang.rank") + ":" }}
              {{ item.rank | rankName }}
            </a>

            <h3 class="searchCard__headline" dir="auto">
              <a @click="detailSpecies(item.id)" :name="item.name" class="ng-isolate-scope">
                <div v-if="item.name_vietnamese">
                  <span>{{ item.name_vietnamese }}</span>
                  <span style="font-style: italic"> - {{ item.name }}</span>
                </div>
                <div v-else>
                  {{ item.name }}
                </div>
              </a>
            </h3>
          </div>
        </div>
        <div class="card__stripe">
          <div class="card__content searchCard__content clearfix">
            <p class="discreet classification-list ng-binding ng-scope">
              {{ $t("nbds_lang.classification") }}:
              <span v-if="item.name_kingdom">
                <span>{{
    `${item.name_kingdom} ${item.name_vietnamese_kingdom
      ? "(" + item.name_vietnamese_kingdom + ")"
      : ""
    } `
}}</span>
              </span>
              <span v-if="item.name_phylum">
                <span>{{
    `${item.name_phylum} ${item.name_vietnamese_phylum
      ? "(" + item.name_vietnamese_phylum + ")"
      : ""
    } `
}}</span>
              </span>
              <span v-if="item.name_class">
                <span>{{
    `${item.name_class} ${item.name_vietnamese_class
      ? "(" + item.name_vietnamese_class + ")"
      : ""
    } `
}}</span>
              </span>
              <span v-if="item.name_order">
                <span>{{
    `${item.name_order} ${item.name_vietnamese_order
      ? "(" + item.name_vietnamese_order + ")"
      : ""
    } `
}}</span>
              </span>
              <span v-if="item.name_family">
                <span>{{
    `${item.name_family} ${item.name_vietnamese_family
      ? "(" + item.name_vietnamese_family + ")"
      : ""
    } `
}}</span>
              </span>
              <span v-if="item.name_genus">
                <span>{{
    `${item.name_genus} ${item.name_vietnamese_genus
      ? "(" + item.name_vietnamese_genus + ")"
      : ""
    } `
                  }}</span>
              </span>
            </p>
            <ul class="list-chips">
            </ul>
          </div>
        </div>
      </div>
    </template>
  </page-filter>
</template>

<script>
import { debounce } from "lodash";
import * as env from "../../env.js";
import * as routes from "../../routes.js";
import Multiselect from "vue-multiselect";
import TaxonTree from "@/modules/filter-group/taxontree.vue";
import FilterSearch from "@/modules/filter-group/filter-search.vue";
import FilterContainer from "@/modules/filter-group/filter-container.vue";
import FilterChoose from "@/modules/filter-group/filter-choose.vue";
import PageFilter from "../layouts/page-filter.vue";
import DataTree from "@/modules/filter-group/datatree.vue";
import { doiTuongBaoTons } from "../../doituongbaoton.js";

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
      loading: false,
      taxonFilter: [],
      protectedareaFilter: [],
      datasetFilter: [],
      protectedareaFilter: [],
      statusFilter: [],
      statusOptions: [],
      rankFilter: [],
      rankOptions: [],
      rankHnOptions: [],
      resourceFilter: [],
      rankFilterHaNoi: [],
      filter_region: [],
      filter_subloc: [],
      paramsProvince: {},
      filterBehavior: [],
      filterEnvironment: [],
      filterDiversity: [],
      filterDistribution: [],
      filterEndangered: [],
      filterMoiTruongSong: [],
      filterSource: [],
      quan_huyen: [],
      doiTuongBaoTons,
      doiTuong: {},
      nguon_goc: [],
      filterDoiTuong: {
        khu_bao_ton: [],
        co_so_bao_ton: [],
        khu_dang_de_xuat: []
      },
      isExport: 0,
      moiTruongSongs: [

        { id: 1, name: "Hệ sinh thái rừng thường xanh núi đất độ cao trên 600m" },
        { id: 2, name: "Hệ sinh thái rừng thường xanh núi đất độ cao dưới 600m" },
        { id: 3, name: "Hệ sinh thái rừng hỗn giao tre nứa xen cây gỗ" },
        { id: 4, name: "Hệ sinh thái rừng trên núi đá vôi" },
        { id: 5, name: "Hệ sinh thái tràng cỏ cây bụi" },
        { id: 6, name: "Hệ sinh thái rừng trồng" },
        { id: 7, name: "Hệ sinh thái khu dân cư đô thị" },
        { id: 8, name: "Hệ sinh thái khu dân cư nông thôn" },
        { id: 9, name: "Hệ sinh thái nông nghiệp" },
        { id: 10, name: "Hệ sinh thái đất ngập nước" },
      ],
      optionEndangered: [
        { id: 1, name: "IUCN", code: "iucn_2012" },
        { id: 2, name: "Cites", code: "cites" },
        { id: 3, name: "Sách đỏ Việt Nam 2007", code: "red_list" },
        { id: 4, name: "Loài theo nghị định 64", code: "nghi_dinh" },
      ],
      optionSource: [
        { id: 1, name: "Bản địa", code: "bản địa" },
        { id: 2, name: "Tự nhiên hoang dã", code: "Native" },
        { id: 3, name: "Bán hoang dã" },
        { id: 4, name: "Cư trú" },
        { id: 5, name: "Du nhập", code: "Naturalized" },
        { id: 6, name: "Xâm lấn" },
        { id: 7, name: "Di cư" },
        { id: 8, name: "Nhập nội" },
        { id: 9, name: "Lai" },
        { id: 10, name: "Chưa biết", code: "Unknown" },
      ],
      optionDiversity: [
        { id: 1, name: "Phong phú" },
        { id: 2, name: "Xu hướng tăng" },
        { id: 3, name: "Xu hướng giảm" },
        { id: 4, name: "Hiếm" },
      ],
      optionEnvironment: [
        { id: 1, name: "Nước mặn" },
        { id: 2, name: "Nước ngọt" },
        { id: 3, name: "Nước lợ" },
        { id: 5, name: "Lưỡng cư" },
        { id: 6, name: "Trên cạn" },
      ],
      nguonGocs: []
    };
  },
  computed: {
    getregion: function () {
      return env.endpointhttp + routes.getregion;
    },
    getsublogs: function () {
      return env.endpointhttp + routes.getsubloc;
    },
    getTopPhanBo: function () {
      return env.endpointhttp + 'topphanbo';
    },
    getTopPhanBoSearch: function () {
      return env.endpointhttp + 'searchphanbo';
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
    number_of_filter: function () {
      return (
        this.datasetFilter.length +
        this.taxonFilter.length +
        this.protectedareaFilter.length +
        this.statusFilter.length +
        this.rankFilter.length +
        this.resourceFilter.length +
        this.filterBehavior.length +
        this.filterEnvironment.length +
        this.filterDiversity.length +
        this.filterDistribution.length +
        this.filterEndangered.length +
        this.filterMoiTruongSong.length +
        this.filterSource.length
      );
    },
  },
  mounted() {
    this.getDoiTuong()
    this.getDataSearch();
    this.getNguonGoc()
  },
  watch: {
    nguon_goc(val) {
      this.search()
    },
    filterDoiTuong: {
      handler() {
        this.search();
      },
      deep: true,
    },
    filter_region: function () {
      this.search();
    },
    filter_subloc: function () {
      this.search();
    },
    protectedareaFilter() {
      this.search();
    },
    taxonFilter() {
      this.search();
    },
    datasetFilter() {
      this.search();
    },
    statusFilter() {
      this.search();
    },
    rankFilter(val) {
      this.search();
    },
    rankFilterHaNoi(val) {
      this.search();
    },
    resourceFilter() {
      this.search();
    },
    filterBehavior() {
      this.search();
    },
    filterEnvironment() {
      this.search();
    },
    filterDiversity() {
      this.search();
    },
    filterDistribution() {
      this.search();
    },
    filterEndangered() {
      this.search();
    },
    filterMoiTruongSong() {
      this.search();
    },
    filterSource() {
      this.search();
    },
  },
  methods: {
    getDoiTuong() {
      axios
        .get(env.endpointhttp + "doituongkhubaoton")
        .then((response) => {
          this.doiTuong = response.data;
        })
        .catch((error) => {
          console.log(error);
          this.loading = false;
        });
    },
    getNguonGoc() {
      axios
        .get(env.endpointhttp + "topphanbo")
        .then((response) => {
          this.nguonGocs = response.data.result;
        })
        .catch((error) => {
          console.log(error);
          this.loading = false;
        });
    },
    taxonAddItem(item) {
      if (
        !this.taxonFilter.find(
          (x) =>
            x.filter.child_id == item.filter.child_id &&
            x.filter.rank == item.filter.rank
        )
      ) {
        this.taxonFilter = [item];
      }
    },

    search: debounce(function () {
      this.searchWithRank();
    }, 200),
    clickTab(tab) {
      this.tab = tab;
    },
    detailSpecies(id) {
      window.location.href = "/detail/indexspecies/" + id;
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.searchWithRank(pageNum);
    },
    async searchWithRank(page = 1) {
      let filter_sub_locs = [];
      this.filter_subloc.forEach(function (item, index) {
        filter_sub_locs.push(item.sub_loc);
      });

      let filter_sub_region = [];
      this.filter_region.forEach(function (item, index) {
        filter_sub_region.push(item.id);
      });
      this.paramsProvince = { region_ids: filter_sub_region };
      let params = {
        ranks: this.rankFilter.map((x) => x.name),
        page: this.page,
        search_name: this.keyword,
        status: this.statusFilter.map((x) => x.name),
        dataset_ids: this.datasetFilter.map((x) => x.id),
        protectedarea_ids: this.protectedareaFilter.map((x) => x.id),
        resource_ids: this.resourceFilter.map((x) => x.id),
        sub_locs: filter_sub_locs,
        region_ids: filter_sub_region,
        behavior: this.filterBehavior.map((x) => x.code || x.name),
        environment: this.filterEnvironment.map((x) => x.code || x.name),
        diversity: this.filterDiversity.map((x) => x.code || x.name),
        distribution: this.filterDistribution.map((x) => x.code || x.name),
        endangered: this.filterEndangered.map((x) => x.code || x.name),
        source: this.filterSource.map((x) => x.code || x.name),
        rank_hn: this.rankFilterHaNoi.map(x => x.name),
        doi_tuong: this.filterDoiTuong,
        moi_truong_song: this.filterMoiTruongSong.map((x) => x.name),
        phan_bo: this.nguon_goc.map(x => x.phan_bo),
        is_export: this.isExport
      };
      if (this.taxonFilter.length > 0) {
        Object.assign(
          params,
          this.taxonFilter.reduce((pre, cur) => {
            if (cur.filter) {
              pre[`${cur.rank}_id`] = cur.id;
            }
            return pre;
          }, {})
        );
      }
      
      try {
        if (this.isExport) {
          let response = await axios.request({
            url:  env.endpointhttp + "searchspecies",
            params: params,
            responseType: "blob",
          });            
          this.loading = false;
          this.isExport = false;
          return response
        }
        else{
          this.loading = true;
          let response = await axios.get(env.endpointhttp + "searchspecies", { params });
          this.loading = false;
          this.data = response.data.result.result_species.data;
          this.count = response.data.result.result_species.last_page;
          this.count_result = response.data.result.result_species.total;
          this.loading = false;
          this.page = response.data.result.result_species.current_page;
          $(".stickyNav").addClass("hasOffset");
          this.isExport = false;
        }
        
      } catch (error) {
        $(".stickyNav").addClass("hasOffset");
        this.loading = false;
        this.isExport = false;
        console.log(error)
      }

    },
    addRankFilter(strRank) {
      if (!this.rankFilter.find((x) => x.name == strRank)) {
        this.rankFilter.push(this.rankOptions.find((x) => x.name == strRank));
      }
    },
    setRankSearch(value) {
      if (
        [
          "kingdom",
          "phylum",
          "class",
          "order",
          "family",
          "genus",
          "species",
        ].includes(value)
      ) {
        this.addRankFilter(value);
      }
      if (value == "accepted") {
        if (!this.statusFilter.find((x) => x.name == "accepted")) {
          this.statusFilter.push(
            this.statusOptions.find((x) => x.name == "accepted")
          );
        }
      }
      if (value == "synonym") {
        if (!this.statusFilter.find((x) => x.name == "synonym")) {
          this.statusFilter.push(
            this.statusOptions.find((x) => x.name == "synonym")
          );
        }
      }
      this.searchWithRank();
    },
    getDataSearch() {
      axios
        .get(env.endpointhttp + "getdatasearch/species")
        .then((response) => {
          let ranks = response.data.result.rank;
          this.rankOptions = ranks
            .map((x) => ({
              name: x.rank,
              count: x.total,
            }))
            .reverse();

          let rank_hn = response.data.result.rank_hn;
          this.rankHnOptions = rank_hn
            .map((x) => ({
              name: x.rank,
              count: x.total,
            }))
          let status = response.data.result.status;
          this.statusOptions = status.map((x) => ({
            name: x.status,
            text: x.status.charAt(0).toUpperCase() + x.status.slice(1),
            count: x.total,
          }));
          if (this.$store.state.keySearch) {
            this.keyword = this.$store.state.keySearch;
            this.$store.commit("setKeySearch", { amount: null });
          }
          if (this.$store.state.valueSelect) {
            let rank = this.$store.state.valueSelect;
            this.setRankSearch(rank);
            this.$store.commit("setValueSelect", { amount: null });
          }
          let param = this.getParamSearch();
          if (param) {
            this.setRankSearch(param);
          }
          this.search();
        })
        .catch((error) => { })
        .finally();
    },
    getParamSearch() {
      var url_string = window.location.href;
      var url = new URL(url_string);
      return url.searchParams.get("phanloai");
    },
    detail(id) {
      window.location.href = "/detail/indexspecies/" + id;
    },
    async download() {
      try {
        this.isExport = 1;
        const res = await this.searchWithRank()
        console.log(res)
        saveAs(
          new Blob([res.data]),
          `du_lieu_loai.xls`
        );
      } finally {
        this.isExport = false;
      }
    },
    clearAllFilter: function () {
      this.rankFilter = [];
      this.taxonFilter = [];
      this.datasetFilter = [];
      this.statusFilter = [];
      this.protectedareaFilter = [];
      this.resourceFilter = [];
      this.filterBehavior = [];
      this.filterEnvironment = [];
      this.filterDiversity = [];
      this.filterDistribution = [];
      this.filterEndangered = [];
      this.filterSource = [];
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
