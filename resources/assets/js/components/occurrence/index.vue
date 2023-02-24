<template>
  <main class="main">
    <div class="viewContentWrapper">
      <aside
        class="site-drawer is-locked"
        :class="activeFillter ? 'is-active' : ''"
      >
        <div class="site-drawer__content">
          <div class="site-drawer__header">
            <div class="site-drawer__bar">
              <a
                href="/search"
                class="gb-icon-angle-left site-drawer__bar__icon inherit"
                tooltip="Everything"
              >
              </a>
              <div class="site-drawer__bar__title">
                <a href class="inherit">
                  <span>{{ $t("nbds_lang.submenu_occurrence") }}</span>
                </a>
              </div>
              <a
                class="gb-icon-trash discreet inherit site-drawer__bar__icon"
                style="cursor: pointer"
                @click="clearAllFilter()"
              >
                <span
                  class="count badge ng-binding"
                  v-if="number_of_filter > 0"
                  >{{ number_of_filter }}</span
                >
              </a>
            </div>
          </div>
          <div class="site-drawer__transclude">
            <div class="site-drawer__section" style="padding-bottom: 0px">
              <div class="search-bar search-bar filter-group">
                <div
                  class="search-bar__term"
                  style="top: 50%; transform: translateY(-50%)"
                >
                  <input
                    type="text"
                    autocomplete="off"
                    v-model="keyword"
                    :placeholder="$t('nbds_lang.search')"
                    class="fit-suggestions"
                    v-on:keyup.enter="search()"
                  />
                  <a
                    class="gb-icon-search search-bar__search"
                    style="cursor: pointer"
                    @click="search()"
                  >
                    <span class="sr-only">{{ $t("nbds_lang.search") }}</span>
                  </a>
                </div>
              </div>
              <filter-search
                :title="$t('nbds_lang.protected_areas.sub_loc')"
                :route_suggest="gettopsubloc"
                field_name="sub_loc"
                :route_search="getsublogs"
                :params="paramsProvince"
                :showCount="false"
                v-model="filter_subloc"
              >
              </filter-search>

              <filter-search
                :title="$t('nbds_lang.submenu_dataset')"
                :route_suggest="gettopdatasetsofoccurrences"
                field_count="darwin_core_occurrences_count"
                field_name="title"
                :route_search="asyncdatasetresources"
                v-model="filter_datasets"
              >
              </filter-search>

              <FilterYear v-model="filter_years"></FilterYear>

              <!-- <filter-search
                :title="$t('nbds_lang.protected_areas.region')"
                :route_suggest="getregion"
                field_name="name_vietnamese"
                :route_search="getregion"
                v-model="filter_region"
              >
              </filter-search> -->

              <filter-choose
                :title="$t('nbds_lang.submenu_occurrence')"
                v-model="resourceFilter"
                :options="optionResource"
              >
                <template v-slot:name-filter="{ item }">
                  {{ item.name }}
                </template>
              </filter-choose>
            </div>
          </div>
        </div>
      </aside>
      <div
        class="content__overlay hide-on-laptop ng-scope"
        @click="activeFillter = false"
        role="button"
        v-show="activeFillter"
      ></div>
      <div class="site-content">
        <div
          class="site-content__page lighter-background"
          style="padding-top: 55px"
        >
          <div class="species-search-results light-background">
            <section
              class="horizontal-stripe--paddingless white-background search-header"
            >
              <div class="container-fluid">
                <div class="row">
                  <div class="col-xs-12">
                    <nav
                      class="article-header__category article-header__category--deep"
                    >
                      <span class="article-header__category__upper">{{
                        $t("nbds_lang.submenu_occurrence")
                      }}</span>
                      <span
                        class="article-header__category__lower"
                        v-if="count_result != null"
                      >
                        <span
                          >{{ count_result + " "
                          }}{{ $t("nbds_lang.results") }}</span
                        >
                      </span>
                    </nav>
                  </div>
                </div>
              </div>
            </section>
            <section
              class="horizontal-stripe--paddingless white-background p-hidden"
            >
              <div class="container-fluid">
                <div class="row">
                  <nav class="tabs">
                    <ul>
                      <li class="tab isActive">
                        <span>{{ $t("nbds_lang.table") }}</span>
                      </li>
                      <li class="tab">
                        <a class="download">
                          <download-occurrence
                            :value="fullData"
                            v-if="!loading && user"
                          ></download-occurrence>
                        </a>
                      </li>
                    </ul>
                  </nav>
                </div>
              </div>
            </section>
            <section v-if="loading">
              <Loading :height="65"></Loading>
            </section>

            <section
              class="horizontal-stripe--paddingless lighter-background"
              v-else
            >
              <div class="occurrence-search__table__area rtl-bootstrap">
                <div class="scrollable-y">
                  <div class="table-container">
                    <table class="table search-table smaller">
                      <thead>
                        <tr>
                          <th class="gb-icon-more2" role="button"></th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.scientific_name"
                              )
                            }}
                          </th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.coordinates"
                              )
                            }}
                          </th>
                          <th>
                            {{
                              $t("nbds_lang.darwin_core_occurrences.day") +
                              " (yyyy/mm/dd)"
                            }}
                          </th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.dataset_resource_id"
                              )
                            }}
                          </th>
                          <th>
                            {{ $t("nbds_lang.darwin_core_taxons.taxon_rank") }}
                          </th>
                          <th>
                            {{ $t("nbds_lang.darwin_core_taxons.kingdom_id") }}
                          </th>
                          <th>
                            {{ $t("nbds_lang.darwin_core_taxons.phylum_id") }}
                          </th>
                          <th>
                            {{ $t("nbds_lang.darwin_core_taxons.class_id") }}
                          </th>
                          <th>
                            {{ $t("nbds_lang.darwin_core_taxons.order_id") }}
                          </th>
                          <th>
                            {{ $t("nbds_lang.darwin_core_taxons.family_id") }}
                          </th>
                          <th>
                            {{ $t("nbds_lang.darwin_core_taxons.genus_id") }}
                          </th>
                          <th>
                            {{ $t("nbds_lang.darwin_core_taxons.species_id") }}
                          </th>
                          <th>
                            {{
                              $t("nbds_lang.darwin_core_taxons.sub_species_id")
                            }}
                          </th>
                          <th>
                            {{
                              $t("nbds_lang.darwin_core_occurrences.references")
                            }}
                          </th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.basis_of_record"
                              )
                            }}
                          </th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.occurrence_remarks"
                              )
                            }}
                          </th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.recorded_by"
                              )
                            }}
                          </th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.individual_count"
                              )
                            }}
                          </th>
                          <th>
                            {{ $t("nbds_lang.darwin_core_occurrences.sex") }}
                          </th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.reproductive_condition"
                              )
                            }}
                          </th>
                          <th>
                            {{
                              $t("nbds_lang.darwin_core_occurrences.life_stage")
                            }}
                          </th>
                          <th>
                            {{
                              $t("nbds_lang.darwin_core_occurrences.behavior")
                            }}
                          </th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.sampling_protocol"
                              )
                            }}
                          </th>
                          <th>
                            {{
                              $t("nbds_lang.darwin_core_occurrences.event_date")
                            }}
                          </th>
                          <th>
                            {{
                              $t("nbds_lang.darwin_core_occurrences.event_time")
                            }}
                          </th>
                          <th>
                            {{
                              $t("nbds_lang.darwin_core_occurrences.locality")
                            }}
                          </th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.verbatim_elevation"
                              )
                            }}
                          </th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.verbatim_depth"
                              )
                            }}
                          </th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.specific_epithet"
                              )
                            }}
                          </th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.scientific_name_authorship"
                              )
                            }}
                          </th>
                          <th>
                            {{
                              $t("nbds_lang.darwin_core_occurrences.region_id")
                            }}
                          </th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.province_id"
                              )
                            }}
                          </th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.district_id"
                              )
                            }}
                          </th>
                          <th>
                            {{
                              $t(
                                "nbds_lang.darwin_core_occurrences.protected_area_id"
                              )
                            }}
                          </th>
                          <th>
                            {{
                              $t("nbds_lang.darwin_core_occurrences.plot_id")
                            }}
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          class="lastCellFlexible rtl-supported"
                          v-for="item in data"
                        >
                          <td class="table-cell table-cell--action"></td>
                          <td class="table-cell--wide">
                            <a :href="'/detail/occurrence/' + item.id"
                              ><i>{{ item.scientific_name }}</i></a
                            >
                          </td>
                          <td class="table-cell--narrow">
                            <a
                              v-if="
                                item.decimal_latitude && item.decimal_longitude
                              "
                              >{{
                                item.decimal_latitude +
                                " " +
                                item.decimal_longitude
                              }}</a
                            >
                          </td>
                          <td class="">
                            <a v-if="item.year && item.month && item.day">{{
                              item.year + "/" + item.month + "/" + item.day
                            }}</a>
                          </td>
                          <td class="table-cell--wide">
                            <a
                              v-if="item.data_resource"
                              :href="'/detail/dataset/' + item.data_resource.id"
                              >{{ item.data_resource.title }}</a
                            >
                          </td>
                          <td class="">
                            <a v-if="item.darwin_core_taxon">{{
                              item.darwin_core_taxon.taxon_rank
                            }}</a>
                          </td>
                          <td class="">
                            <a v-if="item.darwin_core_taxon.king_dom">{{
                              item.darwin_core_taxon.king_dom.name
                            }}</a>
                          </td>
                          <td class="">
                            <a v-if="item.darwin_core_taxon.phylum">{{
                              item.darwin_core_taxon.phylum.name
                            }}</a>
                          </td>
                          <td class="">
                            <a v-if="item.darwin_core_taxon.class">{{
                              item.darwin_core_taxon.class.name
                            }}</a>
                          </td>
                          <td class="">
                            <a v-if="item.darwin_core_taxon.order">{{
                              item.darwin_core_taxon.order.name
                            }}</a>
                          </td>
                          <td class="">
                            <a v-if="item.darwin_core_taxon.family">{{
                              item.darwin_core_taxon.family.name
                            }}</a>
                          </td>
                          <td class="">
                            <a v-if="item.darwin_core_taxon.genus">{{
                              item.darwin_core_taxon.genus.name
                            }}</a>
                          </td>
                          <td class="">
                            <a v-if="item.darwin_core_taxon.species">{{
                              item.darwin_core_taxon.species.name
                            }}</a>
                          </td>
                          <td class="">
                            <a v-if="item.darwin_core_taxon.sub_species">{{
                              item.darwin_core_taxon.sub_species.name
                            }}</a>
                          </td>
                          <td class="table-cell--wide">
                            <a>{{ item.references }}</a>
                          </td>
                          <td class="table-cell--wide">
                            <a>{{ item.basis_of_record }}</a>
                          </td>
                          <td class="table-cell--normal">
                            <a>{{ item.occurrence_remarks }}</a>
                          </td>
                          <td class="table-cell--wide">
                            <a>{{ item.recorded_by }}</a>
                          </td>
                          <td class="">
                            <a>{{ item.individual_count }}</a>
                          </td>
                          <td class="">
                            <a>{{ item.sex }}</a>
                          </td>
                          <td class="">
                            <a>{{ item.reproductive_condition }}</a>
                          </td>
                          <td class="">
                            <a>{{ item.life_stage }}</a>
                          </td>
                          <td class="">
                            <a>{{ item.behavior }}</a>
                          </td>
                          <td class="table-cell--normal">
                            <a>{{ item.sampling_protocol }}</a>
                          </td>
                          <td class="table-cell--normal">
                            <a>{{ item.event_date }}</a>
                          </td>
                          <td class="">
                            <a>{{ item.event_time }}</a>
                          </td>
                          <td class="table-cell--normal">
                            <a>{{ item.locality }}</a>
                          </td>
                          <td class="table-cell--normal">
                            <a>{{ item.verbatim_elevation }}</a>
                          </td>
                          <td class="table-cell--normal">
                            <a>{{ item.verbatim_depth }}</a>
                          </td>
                          <td class="table-cell--normal">
                            <a>{{ item.specific_epithet }}</a>
                          </td>
                          <td class="table-cell--normal">
                            <a>{{ item.scientific_name_authorship }}</a>
                          </td>
                          <td class="">
                            <a v-if="item.region">{{ item.region.name }}</a>
                          </td>
                          <td class="">
                            <a v-if="item.province">{{ item.province.name }}</a>
                          </td>
                          <td class="">
                            <a v-if="item.district">{{ item.district.name }}</a>
                          </td>
                          <td class="">
                            <a v-if="item.protected_area">{{
                              item.protected_area.name
                            }}</a>
                          </td>
                          <td class="">
                            <a v-if="item.plot">{{ item.plot.name }}</a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-xs-12">
                      <paginate
                        v-if="page_count > 1"
                        v-model="current_page"
                        :page-count="this.page_count"
                        :page-range="3"
                        :margin-pages="2"
                        :click-handler="clickCallback"
                        :prev-text="'‹‹'"
                        :next-text="'››'"
                        :container-class="'pagination'"
                        :page-class="'page-item'"
                      >
                      </paginate>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            <section
              class="emptyInfo"
              v-if="this.data.length <= 0 && loading == false"
            >
              <h3>
                {{ $t("nbds_lang.no_results_try_to_loosen_your_filters") }}
              </h3>
            </section>
          </div>
        </div>
        <div class="fab hide-on-laptop">
          <a class="gb-button--brand" @click="activeFillter = true">
            <span
              ><span class="gb-icon-filters"></span
              ><span>{{ $t("nbds_lang.filters") }}</span></span
            >
          </a>
        </div>
        <footer-component></footer-component>
      </div>
    </div>
  </main>
</template>

<script>
import * as env from "../../env.js";
import Multiselect from "vue-multiselect";
import FilterSearch from "@/modules/filter-group/filter-search.vue";
import FilterChoose from "@/modules/filter-group/filter-choose.vue";
import * as routes from "../../routes.js";
import Loading from "../loading.vue";
import FilterYear from "../filterYear.vue";
import TaxonTree from "@/modules/filter-group/taxontree.vue";
import FilterContainer from "@/modules/filter-group/filter-container.vue";

export default {
  props: ["dataset"],
  components: {
    Multiselect,
    FilterSearch,
    Loading,
    FilterYear,
    FilterContainer,
    TaxonTree,
    FilterChoose,
  },
  data: function () {
    return {
      activeFillter: false,
      nextPage: false,
      current_page: 1,
      page_count: 0,
      data: [],
      keyword: null,
      count_result: null,
      loading: false,
      filter_publishers: [],
      filter_datasets: [],
      filter_years: [],
      fullData: [],
      user: null,
      taxonFilter: [],
      resourceFilter: [],
      filter_region: [],
      paramsProvince: {},
      filter_subloc: [],
      optionResource: [
        { id: 1, name: "Loài mới xuất hiện" },
        { id: 2, name: "Loài lâu không xuất hiện" },
      ],
    };
  },
  mounted() {
    if (this.dataset && this.dataset.hasOwnProperty("id")) {
      this.filter_datasets.push(this.dataset);
    }
    this.search();
  },
  methods: {
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
    search() {
      this.loading = true;
      if (!this.nextPage) {
        this.current_page = 1;
      }
      let url = env.endpointhttp + routes.getoccurrences;
      let filter_publisher_ids = [];
      this.filter_publishers.forEach(function (item, index) {
        filter_publisher_ids.push(item.id);
      });
      let filter_dataset_ids = [];
      this.filter_datasets.forEach(function (item, index) {
        filter_dataset_ids.push(item.id);
      });
      let params = {
        page: this.current_page,
        search: this.keyword,
        publishers: filter_publisher_ids,
        datasets: filter_dataset_ids,
        filter_years: this.filter_years,
        resource_ids: this.resourceFilter.map((x) => x.id),
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
      axios
        .get(url, {
          params,
        })
        .then((response) => {
          this.nextPage = false;
          let res = response.data.result;
          this.data = res.data;
          this.fullData = response.data.fullData;
          this.count_result = res.total;
          this.page_count = res.last_page;
          this.user = response.data.user;
        })
        .catch(function (error) {
          this.nextPage = false;
          console.log(error);
        })
        .finally(() => {
          this.nextPage = false;
          this.loading = false;
        });
    },
    clearAllFilter: function () {
      this.filter_publishers = [];
      this.filter_datasets = [];
      this.taxonFilter = [];
      this.resourceFilter = [];
      this.current_page = 1;
      this.search();
    },
    clickCallback(pageNum) {
      this.current_page = pageNum;
      this.nextPage = true;
      this.search();
    },
  },
  computed: {
    getregion: function () {
      return env.endpointhttp + routes.getregion;
    },
    gettopsubloc: function () {
      return env.endpointhttp + routes.gettopsubloc;
    },
    getsublogs: function () {
      return env.endpointhttp + routes.getsubloc;
    },
    getpublishers: function () {
      return env.endpointhttp + routes.asynchronouspublishers;
    },
    gettoppublisersofoccurrences: function () {
      return env.endpointhttp + routes.gettoppublisersofoccurrences;
    },
    asyncdatasetresources: function () {
      return env.endpointhttp + routes.asyncdatasetresources;
    },
    gettopdatasetsofoccurrences: function () {
      return env.endpointhttp + routes.gettopdatasetsofoccurrences;
    },
    number_of_filter: function () {
      return (
        this.filter_publishers.length +
        this.filter_datasets.length +
        this.taxonFilter.length +
        this.resourceFilter.length
      );
    },
    asyncResourcetypes() {
      return env.endpointhttp + routes.getresource_type;
    },
  },
  watch: {
    filter_publishers: function () {
      this.current_page = 1;
      this.search();
    },
    filter_subloc: function () {
      this.current_page = 1;
      this.search();
    },
    filter_datasets: function () {
      this.current_page = 1;
      this.search();
    },
    filter_years: function () {
      this.current_page = 1;
      this.search();
    },
    taxonFilter() {
      this.current_page = 1;
      this.search();
    },
    resourceFilter() {
      this.current_page = 1;
      this.search();
    },
  },
};
</script>

<style scoped>
.occurrence-search__table__area {
  position: relative;
  overflow: hidden;
  width: 100%;
}

.scrollable-y {
  overflow-y: auto;
}
</style>
