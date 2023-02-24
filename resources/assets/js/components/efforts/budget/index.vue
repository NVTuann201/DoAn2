<template>
  <page-filter
    :titleFilter="$t('nbds_lang.submenu_annual_budget')"
    :titleSearch="$t('nbds_lang.submenu_annual_budget_desc')"
    :count_result="count_result"
    :number_of_filter="number_of_filter"
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
        <FilterDay v-model="filter_years" title="Thời gian"></FilterDay>
        <filter-choose
          title="Nguồn kinh phí"
          v-model="filterBehavior"
          :options="[
            { id: 1, name: 'Ngân sách nhà nước' },
            { id: 2, name: 'Đầu tư, đóng góp' },
            { id: 2, name: 'Khác' },
          ]"
        >
          <template v-slot:name-filter="{ item }">
            {{ item.name }}
          </template>
        </filter-choose>
        <!-- <filter-search
          :title="$t('nbds_lang.protected_areas.sub_loc')"
          :route_suggest="gettopsubloc"
          field_name="sub_loc"
          :route_search="getsublogs"
          :params="paramsProvince"
          :showCount="false"
          v-model="filter_subloc"
        >
        </filter-search> -->
      </div>
    </template>
    <template v-slot:item="{ item }">
      <div class="card m-b-05 searchCard rtl-supported rtl-bootstrap ng-scope">
        <div class="card__stripe">
          <div class="card__content">
            <a
              @click="setRankSearch(item.rank)"
              style="cursor: pointer"
              class="uppercase-first inherit searchCard__type hoverBox ng-scope"
            >
              {{ $t("nbds_lang.rank") + ":" }}
              {{ item.rank | rankName }}
            </a>
            <h3 class="searchCard__headline" dir="auto">
              <a
                @click="detailSpecies(item.id)"
                :name="item.name"
                class="ng-isolate-scope"
              >
                {{
                  item.name_vietnamese
                    ? item.name + " (" + item.name_vietnamese + ")"
                    : item.name
                }}
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
                  `${item.name_kingdom} ${
                    item.name_vietnamese_kingdom
                      ? "(" + item.name_vietnamese_kingdom + ")"
                      : ""
                  } `
                }}</span>
              </span>
              <span v-if="item.name_phylum">
                <span>{{
                  `${item.name_phylum} ${
                    item.name_vietnamese_phylum
                      ? "(" + item.name_vietnamese_phylum + ")"
                      : ""
                  } `
                }}</span>
              </span>
              <span v-if="item.name_class">
                <span>{{
                  `${item.name_class} ${
                    item.name_vietnamese_class
                      ? "(" + item.name_vietnamese_class + ")"
                      : ""
                  } `
                }}</span>
              </span>
              <span v-if="item.name_order">
                <span>{{
                  `${item.name_order} ${
                    item.name_vietnamese_order
                      ? "(" + item.name_vietnamese_order + ")"
                      : ""
                  } `
                }}</span>
              </span>
              <span v-if="item.name_family">
                <span>{{
                  `${item.name_family} ${
                    item.name_vietnamese_family
                      ? "(" + item.name_vietnamese_family + ")"
                      : ""
                  } `
                }}</span>
              </span>
              <span v-if="item.name_genus">
                <span>{{
                  `${item.name_genus} ${
                    item.name_vietnamese_genus
                      ? "(" + item.name_vietnamese_genus + ")"
                      : ""
                  } `
                }}</span>
              </span>
            </p>
            <ul class="list-chips">
              <li>
                <a
                  @click="setRankSearch(item.status)"
                  style="cursor: pointer"
                  class="uppercase-first ng-scope"
                >
                  {{ $t("nbds_lang.dataset_resources.status") + ":" }}
                  <span style="text-transform: capitalize">{{
                    item.status
                  }}</span></a
                >
              </li>
              <li>
                <a
                  @click="setRankSearch(item.rank)"
                  style="cursor: pointer"
                  class="uppercase-first ng-scope"
                >
                  {{ item.rank | rankName }}
                </a>
              </li>
              <li>
                <a @click="setRankHigherSearch(item)">
                  <span class="loaded">
                    {{ $n(item.number_count) }}
                    <span style="margin-left: 5px">
                      {{ $t("nbds_lang.submenu_species") }}
                    </span>
                  </span>
                </a>
              </li>
            </ul>
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
import FilterDay from "../../filterDay.vue";

export default {
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
      filter_years: [],
      filter_gov_types: [],
      filter_status: [],
      distributionData: [
        {
          id: 1,
          name: "Phân bố tỉnh",
          children: [
            {
              name: "Phổ biến",
              id: 1,
            },
            {
              name: "Có hạn",
              id: 2,
            },
            {
              name: "Đặc hữu",
              id: 3,
            },
          ],
        },
        {
          id: 2,
          name: "Phân bố Việt Nam",
          children: [
            {
              name: "Phổ biến",
              id: 1,
            },
            {
              name: "Có hạn",
              id: 2,
            },
            {
              name: "Đặc hữu",
              id: 3,
            },
          ],
        },
      ],
    };
  },
  computed: {
    gettopgovtypesofprotectedareas: function () {
      return env.endpointhttp + routes.gettopgovtypesofprotectedareas;
    },
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
    number_of_filter: function () {
      return (
        this.datasetFilter.length +
        this.taxonFilter.length +
        this.protectedareaFilter.length +
        this.statusFilter.length +
        this.rankFilter.length +
        this.resourceFilter.length
      );
    },
  },
  mounted() {
    this.getDataSearch();
    if (this.$store.state.keySearch) {
      this.keyword = this.$store.state.keySearch;
      this.searchWithRank();
      this.$store.commit("setKeySearch", { amount: null });
    } else {
      this.searchWithRank();
    }
    if (this.$store.state.valueSelect) {
      let rank = this.$store.state.valueSelect;
      this.setRankSearch(rank);
      this.searchWithRank();
      this.$store.commit("setValueSelect", { amount: null });
    } else {
      this.searchWithRank();
    }
  },
  watch: {
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
    rankFilter() {
      this.search();
    },
    resourceFilter() {
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
        page,
        search_name: this.keyword,
        status: this.statusFilter.map((x) => x.name),
        dataset_ids: this.datasetFilter.map((x) => x.id),
        protectedarea_ids: this.protectedareaFilter.map((x) => x.id),
        resource_ids: this.resourceFilter.map((x) => x.id),
        sub_locs: filter_sub_locs,
        region_ids: filter_sub_region,
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
      this.loading = true;
      axios
        .get(env.endpointhttp + "searchspecies", {
          params,
        })
        .then((response) => {
          this.data = response.data.result.result_species.data;
          this.count = response.data.result.result_species.last_page;
          this.count_result = response.data.result.result_species.total;
          this.loading = false;
          this.page = response.data.result.result_species.current_page;
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

          let status = response.data.result.status;
          this.statusOptions = status.map((x) => ({
            name: x.status,
            text: x.status.charAt(0).toUpperCase() + x.status.slice(1),
            count: x.total,
          }));
        })
        .catch((error) => {})
        .finally();
    },
    detail(id) {
      window.location.href = "/detail/indexspecies/" + id;
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
    searchAll() {
      this.setKeySearch(this.keyword);
      window.location.href = "/search";
    },
    setKeySearch(value) {
      this.$store.commit("setKeySearch", { amount: value });
    },
    distributionAddItem(item) {
      console.log(item);
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
