<template>
    <main class="main">
        <div class="viewContentWrapper">
            <aside class="site-drawer is-locked" :class="activeFillter ? 'is-active' : ''">
                <div class="site-drawer__content">
                    <div class="site-drawer__header">
                        <div class="site-drawer__bar">
                            <a href="/search" class="gb-icon-angle-left site-drawer__bar__icon inherit" tooltip="Everything">
                            </a>
                            <div class="site-drawer__bar__title">
                                <a href class="inherit">
                                    <span>{{ $t("nbds_lang.enum_darwin_core_taxons")}}</span>
                                </a>
                            </div>
                            <a class="gb-icon-trash discreet inherit site-drawer__bar__icon" style="cursor: pointer;"
                               @click="clearAllFilter()">
                                <span class="count badge ng-binding" v-if="number_of_filter > 0">{{number_of_filter}}</span>
                            </a>
                        </div>
                    </div>
                    <div class="site-drawer__transclude">
                        <div class="site-drawer__section" style="padding-bottom: 0px">
                            <div class="search-bar search-bar filter-group">
                                <div class="search-bar__term" style="top: 50%;transform: translateY(-50%);">
                                    <input type="text" autocomplete="off" v-model="keyword" :placeholder="$t('nbds_lang.search')" class="fit-suggestions" v-on:keyup.enter="search()">
                                    <a class="gb-icon-search search-bar__search" style="cursor: pointer;" @click="search()">
                                        <span class="sr-only">{{ $t("nbds_lang.search") }}</span>
                                    </a>
                                </div>
                            </div>
                            <!-- <div class="filter-group text-center">
                                <div class="gb-btn-group">
                                    <label class="active">Simple</label>
                                    <label>Advanced</label>
                                </div>
                            </div> -->
                            <FilterSearch
                                    :title="$t('nbds_lang.submenu_dataset')"
                                    :route_suggest="gettopdatasetfortaxon"
                                    field_count="darwin_core_taxons_count"
                                    field_name="title"
                                    :route_search="asyncdatasetresources"
                                    :value_search="getvaluedataresuorces"
                                    v-model="filter_datasets">
                            </FilterSearch>
                        </div>
                    </div>
                </div>
            </aside>
            <div class="content__overlay hide-on-laptop ng-scope" @click="activeFillter = false" role="button" v-show="activeFillter"></div>
            <div class="site-content">
                <div class="site-content__page lighter-background" style="padding-top: 55px">
                    <div class="species-search-results light-background">
                        <section class="horizontal-stripe--paddingless white-background search-header">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <nav class="article-header__category article-header__category--deep">
                                            <span class="article-header__category__upper">{{ $t("nbds_lang.enum_darwin_core_taxons")}}</span>
                                            <span class="article-header__category__lower">{{`${tab == 1 ? (count_result && count_result > 0 ? $n(count_result) : 0) : $n(totalGallery) } `}}{{ $t("nbds_lang.results") }}</span>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="horizontal-stripe--paddingless white-background p-hidden">
                            <div class="container-fluid">
                                <div class="row">
                                    <nav class="tabs">
                                        <ul>
                                            <li class="tab" :class="[tab == 1 ? 'isActive':'']" @click="tab = 1">
                                                <span>{{$t('nbds_lang.table')}}</span>
                                            </li>
                                            <li class="tab" :class="[tab == 2 ? 'isActive':'']" @click="tab = 2">
                                                <span>{{$t('nbds_lang.gallery')}}</span>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </section>
                        <section v-if="loading" style="display: flex;
                        justify-content: center;">
                            <Loading :width="65" :height="65"></Loading>
                        </section>

                        <section class="horizontal-stripe--paddingless lighter-background" v-else-if="!loading && tab == 1">
                            <div class="occurrence-search__table__area rtl-bootstrap">
                                <div class="scrollable-y">
                                    <div class="table-container">
                                        <table class="table search-table smaller">
                                            <thead>
                                            <tr>
                                                <th>{{$t('nbds_lang.darwin_core_taxons.scientific_name')}}</th>
                                                <th>{{$t('nbds_lang.darwin_core_taxons.kingdom_id')}}</th>
                                                <th>{{$t('nbds_lang.darwin_core_taxons.phylum_id')}}</th>
                                                <th>{{$t('nbds_lang.darwin_core_taxons.class_id')}}</th>
                                                <th>{{$t('nbds_lang.darwin_core_taxons.order_id')}}</th>
                                                <th>{{$t('nbds_lang.darwin_core_taxons.family_id')}}</th>
                                                <th>{{$t('nbds_lang.darwin_core_taxons.genus')}}</th>
                                                <!-- <th>{{$t('nbds_lang.darwin_core_taxons.species_id')}}</th> -->
                                                <th>{{$t('nbds_lang.darwin_core_taxons.name_vietnamese')}}</th>
                                                <th>{{$t('nbds_lang.protected_areas.name_en')}}</th>
                                                <th>{{$t('nbds_lang.darwin_core_taxons.dataset_resource_id')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="lastCellFlexible rtl-supported" v-for="item in data" style="cursor: pointer;" @click="goDetailTaxon(item.id)">
                                                <td class="table-cell--wide">
                                                    <a><i>{{item.scientific_name}}</i></a>
                                                </td>
                                                <td class="">
                                                    <a v-if="item.king_dom">{{item.king_dom.name}}</a>
                                                </td>
                                                <td class="">
                                                    <a v-if="item.phylum">{{item.phylum.name}}</a>
                                                </td>
                                                <td class="">
                                                    <a v-if="item.class">{{item.class.name}}</a>
                                                </td>
                                                <td class="">
                                                    <a v-if="item.order">{{item.order.name}}</a>
                                                </td>
                                                <td class="">
                                                    <a v-if="item.family">{{item.family.name}}</a>
                                                </td>
                                                <td class="">
                                                    <a v-if="item.genus">{{item.genus.name}}</a>
                                                </td>
                                                <!-- <td class="">
                                                    <a v-if="item.species">{{item.species.name}}
                                                    </a>
                                                </td> -->
                                                 <td class="">
                                                    <a v-if="item.species">{{item.species.name_vietnamese}}
                                                   </a>
                                                </td>
                                                 <td class="">
                                                    <a >{{item.vernacular_name_english}}
                                                   </a>
                                                </td>
                                                <td class="">
                                                    <a v-if="item.data_resource">{{item.data_resource.title}}</a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <paginate v-if="page_count>1" v-model="current_page" :page-count="page_count" :page-range="3" :margin-pages="2"
                                                      :click-handler="clickCallback" :prev-text="'‹‹'" :next-text="'››'"
                                                      :container-class="'pagination'" :page-class="'page-item'">
                                            </paginate>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section  v-else-if="!loading && tab == 2">
                          <gallery @total="getTotalGallery" :keyword="keyword" :filter_datasets="filter_datasets"/>
                        </section>
                        <section class="emptyInfo" v-if="this.data.length <= 0 && loading == false">
                            <h3>{{ $t("nbds_lang.no_results_try_to_loosen_your_filters") }}</h3>
                        </section>
                    </div>
                </div>
                <div class="fab hide-on-laptop">
                    <a class="gb-button--brand" @click="activeFillter = true">
                        <span>
                            <span class="gb-icon-filters"></span>
                            <span>{{$t("nbds_lang.filters")}}</span>
                        </span>
                    </a>
                </div
                <footer-component></footer-component>
            </div>
        </div>
    </main>
</template>

<script>
import * as env from "../../env.js";
import Multiselect from "vue-multiselect";
import FilterSearch from "@/modules/filter-group/filter-search.vue";
import * as routes from "../../routes.js";
import Loading from "../loading.vue";
import FilterYear from "../filterYear.vue";
import gallery from "./gallery.vue";
export default {
  props: ["dataset"],
  components: {
    Multiselect,
    FilterSearch,
    Loading,
    FilterYear,
    gallery,
  },
  data: function () {
    return {
      activeFillter: false,
      current_page: 1,
      page_count: 0,
      data: [],
      keyword: null,
      valueSelect: {},
      count_result: null,
      loading: false,
      filter_datasets: [],
      nextPage: false,
      fullData: [],
      user: null,
      tab: 1,
      totalGallery: 0,
      itemKey: [
        "scientific_name",
        "king_dom",
        "phylum",
        "class",
        "order",
        "family",
        "genus",
        "species",
        "data_resource",
        "vernacular_name_english",
      ],
    };
  },
  mounted() {
    if (this.dataset && this.dataset.hasOwnProperty("id")) {
      this.filter_datasets.push(this.dataset);
    }
    if (this.$store.state.valueSelect) {
      let value = this.$store.state.valueSelect;
      this.getdataresuorces(value);
      this.$store.commit("setValueSelect", { amount: null });
    } else {
      this.search();
    }
  },
  methods: {
    getTotalGallery(value) {
      this.totalGallery = value;
    },
    goDetailTaxon(value) {
      window.location.href = "/detail/taxon/" + value;
    },
    getdataresuorces(value) {
      let url = env.endpointhttp + routes.dataresuorces;
      axios
        .get(url, {
          params: {
            dataset_resources_id: value,
          },
        })
        .then((response) => {
          const valueSelect = {
            id: response.data.result[0].id,
            title: response.data.result[0].title,
          };
          this.filter_datasets.push(valueSelect);
          this.search();
        })
        .catch(function (error) {})
        .finally(() => {});
    },
    search() {
      if (!this.nextPage) {
        this.current_page = 1;
      }
      this.loading = true;
      let url = env.endpointhttp + routes.gettaxon;
      let filter_dataset_ids = [];
      this.filter_datasets.forEach(function (item, index) {
        filter_dataset_ids.push(item.id);
      });
      axios
        .get(url, {
          params: {
            page: this.current_page,
            search: this.keyword,
            datasets: filter_dataset_ids,
            filter_years: this.filter_years,
          },
        })
        .then((response) => {
          this.nextPage = false;
          let res = response.data.result;
          this.data = res.data.filter((x) => {
            return this.itemKey.some((key) => {
              return (
                x[key] != "" ||
                (x[key] != null &&
                  typeof x[key] === "object" &&
                  (x[key]["name"] || x[key]["title"]))
              );
            });
          });
          this.count_result = res.total;
          this.page_count = res.last_page;
          this.fullData = response.data.fullData;
          this.user = response.data.user;
        })
        .catch(function (error) {
          this.nextPage = false;
        })
        .finally(() => {
          this.nextPage = false;
          this.loading = false;
        });
    },
    clearAllFilter: function () {
      if (this.filter_datasets.length > 0) {
        this.filter_datasets = [];
      }
      this.search();
    },
    clickCallback(pageNum) {
      this.current_page = pageNum;
      this.nextPage = true;
      this.search();
    },
  },
  computed: {
    getvaluedataresuorces: function () {
      if (this.valueSelect) {
        return this.valueSelect;
      }
    },
    gettopdatasetfortaxon: function () {
      return env.endpointhttp + routes.gettopdatasetfortaxon;
    },
    asyncdatasetresources: function () {
      return env.endpointhttp + routes.asyncdatasetresources;
    },
    number_of_filter: function () {
      var numberOfFiler = 0;
      if (this.filter_datasets.length > 0) {
        numberOfFiler++;
      }
      return numberOfFiler;
    },
  },
  watch: {
    filter_datasets: function () {
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
.tab {
  cursor: pointer;
}
</style>
