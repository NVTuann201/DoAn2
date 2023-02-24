<template>
  <div>
    <div id="site-wrapper" class="site-wrapper" style="display: block">
      <main id="main" class="main" role="main" ui-view="">
        <div ui-view="main" class="viewContentWrapper" style="">
          <div>
            <div class="site-content">
              <div class="site-content__page">
                <div class="omni-search-results light-background" style="padding-top:50px">
                  <div class="wrapper-horizontal-stripes">
                    <div
                      class="horizontal-stripe--paddingless bare-background p-t-1 seperator--b"
                    >
                      <div class="container--narrow">
                        <div class="row">
                          <div class="col-xs-12">
                            <div class="search-box ng-pristine ng-valid">
                              <input
                                type="text"
                                autocomplete="off"
                                name="search"
                                placeholder="Loài, bộ dữ liệu, cơ quan công bố, khu bảo tồn"
                                class="ng-pristine ng-untouched ng-valid ng-empty"
                                aria-invalid="false"
                                v-model="info"
                                v-on:keyup.enter="submitSearch()"
                              />
                            </div>
                          </div>

                          <div class="col-xs-12">
                            <nav class="tabs">
                              <ul>
                                <li class="tab isActive">
                                  <a href="#">{{
                                    $t("nbds_lang.everything")
                                  }}</a>
                                </li>

                                <li class="tab">
                                  <a @click="searchSpecies()">{{
                                    $t("nbds_lang.submenu_species")
                                  }}</a>
                                </li>

                                <li class="tab">
                                  <a @click="searchDataset()">{{
                                    $t("nbds_lang.submenu_dataset")
                                  }}</a>
                                </li>

                                <li class="tab">
                                  <a @click="searchPublisher()">{{
                                    $t("nbds_lang.submenu_publisher")
                                  }}</a>
                                </li>

                                <li class="tab">
                                  <a @click="searchProtectedarea()">{{
                                    $t("nbds_lang.submenu_protected_area")
                                  }}</a>
                                </li>
                              </ul>
                            </nav>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="horizontal-stripe--paddingless">
                      <div class="container--narrow">
                        <div class="row">
                          <div class="col-xs-12 m-t-1">
                            <div v-if="loading" class="d-flex justify-center">
                              <Loading :height="65"></Loading>
                            </div>
                            <div
                              class="emptyInfo"
                              v-show="!loading"
                              v-if="
                                result_species.length == 0 &&
                                result_datatset.length == 0 &&
                                result_protectedarea.length == 0 &&
                                result_publisher.length == 0
                              "
                            >
                              <h3 translate="search.noSearchTermsEntered">
                                {{ $t("nbds_lang.no_search_terms_entered") }}
                              </h3>
                            </div>
                            <div v-if="data.length != 0" v-show="!loading">
                              <div v-if="result_species.length != 0">
                                <p class="clearfix small m-t-1">
                                  <span
                                    class="discreet--very text-uppercase"
                                    translate="search.species"
                                    >{{ $t("nbds_lang.submenu_species") }}</span
                                  >
                                  <a
                                    @click="searchSpecies()"
                                    class="discreet--very text-uppercase pull-right"
                                  >
                                    <span style="margin-right: 5px"
                                      >{{ $n(result_species.length) }}
                                    </span>
                                    <span>{{ $t("nbds_lang.results") }}</span>
                                  </a>
                                </p>
                                <div
                                  src="'#"
                                  v-for="(item, index) in result_species.slice(
                                    0,
                                    9
                                  )"
                                >
                                  <div
                                    class="card m-b-05 searchCard rtl-supported rtl-bootstrap"
                                  >
                                    <div class="card__stripe">
                                      <div class="card__content">
                                        <a
                                          @click="setRankSearch(item.rank)"
                                          style="cursor: pointer"
                                          class="inherit searchCard__type hoverBox"
                                          >{{ item.rank | rankName }}</a
                                        >
                                        <h3
                                          class="searchCard__headline"
                                          dir="auto"
                                        >
                                          <a
                                            :href="
                                              '/detail/indexspecies/' + item.id
                                            "
                                            :name="item.name"
                                          >
                                            {{ item.name }}
                                            <template
                                              v-if="item.name_vietnamese"
                                            >
                                              ({{ item.name_vietnamese }})
                                            </template>
                                          </a>
                                        </h3>
                                      </div>
                                    </div>
                                    <div class="card__stripe">
                                      <div
                                        class="card__content searchCard__content clearfix"
                                      >
                                        <p class="discreet classification-list">
                                          {{ $t("nbds_lang.classification") }}:
                                          <span v-if="item.name_kingdom">
                                            <span>{{ item.name_kingdom }}</span>
                                          </span>
                                          <span v-if="item.name_phylum">
                                            <span>{{ item.name_phylum }}</span>
                                          </span>
                                          <span v-if="item.name_class">
                                            <span>{{ item.name_class }}</span>
                                          </span>
                                          <span v-if="item.name_order">
                                            <span>{{ item.name_order }}</span>
                                          </span>
                                          <span v-if="item.name_family">
                                            <span>{{ item.name_family }}</span>
                                          </span>
                                          <span v-if="item.name_genus">
                                            <span>{{ item.name_genus }}</span>
                                          </span>
                                        </p>
                                        <ul
                                          class="list-chips"
                                          v-if="item.number_count > 0"
                                        >
                                          <li>
                                            <a
                                              @click="
                                                searchSelectSpecies(item.rank)
                                              "
                                              style="cursor: pointer"
                                            >
                                              {{ item.rank | rankName }}
                                            </a>
                                          </li>
                                          <li>
                                            <a href="#">
                                              <span class="loaded">
                                                {{ $n(item.number_count) }}
                                                <span style="margin-left: 5px">
                                                  {{ $t("nbds_lang.specie") }}
                                                </span>
                                              </span>
                                            </a>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div v-if="result_datatset.length != 0">
                                <p class="clearfix small m-t-1">
                                  <span
                                    class="discreet--very text-uppercase"
                                    translate="search.species"
                                    >{{ $t("nbds_lang.submenu_dataset") }}</span
                                  >
                                  <a
                                    @click="searchDataset()"
                                    class="discreet--very text-uppercase pull-right"
                                  >
                                    <span style="margin-right: 5px">
                                      {{ $n(result_datatset.length) }}
                                    </span>
                                    <span>{{ $t("nbds_lang.results") }}</span>
                                  </a>
                                </p>

                                <dataset-item
                                  v-for="(item, index) in result_datatset.slice(
                                    0,
                                    9
                                  )"
                                  :key="item.id"
                                  :item="item"
                                ></dataset-item>
                              </div>
                              <div v-if="result_protectedarea.length != 0">
                                <p class="clearfix small m-t-1">
                                  <span
                                    class="discreet--very text-uppercase"
                                    translate="search.species"
                                    >{{
                                      $t("nbds_lang.submenu_protected_area")
                                    }}</span
                                  >
                                  <a
                                    @click="searchProtectedarea()"
                                    class="discreet--very text-uppercase pull-right"
                                  >
                                    <span style="margin-right: 5px"
                                      >{{ $n(result_protectedarea.length) }}
                                    </span>
                                    <span>{{ $t("nbds_lang.results") }}</span>
                                  </a>
                                </p>
                                <protecdarea-item
                                  :item="item"
                                  v-for="item in result_protectedarea.slice(
                                    0,
                                    9
                                  )"
                                  :key="item.id"
                                  @click:seachByGovType="seachByGovType"
                                  @click:seachBySubLoc="
                                    seachBySubLoc($event, 'sub_loc')
                                  "
                                  @click:seachByDesig="
                                    seachByDesig($event, 'desig')
                                  "
                                  @click:seachByStatus="
                                    seachByStatus($event, 'status')
                                  "
                                ></protecdarea-item>
                              </div>
                              <div v-if="result_publisher.length != 0">
                                <p class="clearfix small m-t-1">
                                  <span
                                    class="discreet--very text-uppercase"
                                    translate="search.species"
                                    >{{
                                      $t("nbds_lang.submenu_publisher")
                                    }}</span
                                  >
                                  <a
                                    @click="searchPublisher()"
                                    class="discreet--very text-uppercase pull-right"
                                  >
                                    <span style="margin-right: 5px"
                                      >{{ $n(result_publisher.length) }}
                                    </span>
                                    <span>{{ $t("nbds_lang.results") }}</span>
                                  </a>
                                </p>
                                <article
                                  class="card m-b-05 searchCard rtl-supported"
                                  v-for="(
                                    item, index
                                  ) in result_publisher.slice(0, 9)"
                                >
                                  <div class="card__stripe">
                                    <div class="card__content">
                                      <span
                                        class="searchCard__type"
                                        v-if="item.organization_type"
                                        >{{ item.organization_type }}</span
                                      >
                                      <h3 class="searchCard__headline">
                                        <a
                                          :href="'/detail/publisher/' + item.id"
                                          >{{ item.name }}</a
                                        >
                                      </h3>
                                    </div>
                                  </div>
                                  <div class="card__stripe">
                                    <div
                                      class="card__content searchCard__content clearfix"
                                    >
                                      <div class="searchCard__meta">
                                        <span
                                          >{{ $t("nbds_lang.joined") + " " }}
                                          {{
                                            item.created_at
                                              | moment("from", "now")
                                          }}</span
                                        >
                                      </div>
                                      <p
                                        v-if="
                                          item.description != null &&
                                          item.description.length >= 200
                                        "
                                      >
                                        {{
                                          item.description.substring(0, 200) +
                                          "..."
                                        }}
                                      </p>
                                      <p v-else>{{ item.description }}</p>

                                      <ul class="list-chips">
                                        <li v-if="item.parent">
                                          <a
                                            :href="
                                              '/detail/publisher/' +
                                              item.parent.id
                                            "
                                            >{{
                                              $t(
                                                "nbds_lang.organizations.parent_organization_id"
                                              ) +
                                              ": " +
                                              item.parent.name
                                            }}</a
                                          >
                                        </li>
                                        <li
                                          v-if="
                                            item.dataset_resources_count > 0
                                          "
                                        >
                                          <a
                                            :href="
                                              '/search/dataset?publisher=' +
                                              item.id
                                            "
                                            >{{
                                              item.dataset_resources_count +
                                              " " +
                                              $t("nbds_lang.submenu_dataset")
                                            }}</a
                                          >
                                        </li>
                                      </ul>
                                    </div>
                                  </div>
                                </article>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
<script>
import * as env from "../env.js";
import Multiselect from "vue-multiselect";
import Loading from "./loading.vue";
import ProtecdareaItem from "./protectedarea/protecedarea-item.vue";
import datasetItem from "./dataset/dataset-item.vue";
/*import Datepicker from "vuejs-datepicker";
    import {en, vi} from "vuejs-datepicker/dist/locale";
    import moment from "../../../../node_modules/moment/moment.js";*/

export default {
  components: {
    Multiselect,
    Loading,
    ProtecdareaItem,
    datasetItem,
  },
  data: function () {
    return {
      loading: false,
      data: [],
      result_protectedarea: [],
      result_species: [],
      result_datatset: [],
      result_publisher: [],
      info: null,
    };
  },
  mounted() {
    if (this.$store.state.keySearch) {
      this.info = this.$store.state.keySearch;
      this.submitSearch();
      this.$store.commit("setKeySearch", { amount: null });
    } else {
      this.submitSearch();
    }
  },
  methods: {
    submitSearch() {
      if (this.info) {
        this.loading = true;
        axios
          .get(env.endpointhttp + "/searchEverything?search=" + this.info)
          .then((response) => {
            this.data = response.data;
            document.location.hash = "show_picture";
            this.result_species = response.data.result_species;
            this.result_datatset = response.data.result_datatset;
            this.result_protectedarea = response.data.result_protectedarea;
            this.result_publisher = response.data.result_publisher;
            this.loading = false;
          })
          .catch((error) => {
            this.loading = false;
          })
          .finally({});
      } else {
        this.result_species = [];
        this.result_datatset = [];
        this.result_protectedarea = [];
        this.result_publisher = [];
      }
    },
    searchSpecies() {
      window.location.href = "/search/species";
      this.setKeySearch(this.info);
    },
    searchSelectSpecies(value) {
      window.location.href = "/search/species";
      this.setValueSelectSearch(value);
    },
    searchDataset() {
      window.location.href = "/search/dataset";
      this.setKeySearch(this.info);
    },
    searchProtectedarea() {
      window.location.href = "/search/protectedarea";
      this.setKeySearch(this.info);
    },
    searchPublisher() {
      window.location.href = "/search/publisher";
      this.setKeySearch(this.info);
    },
    setKeySearch(value) {
      this.$store.commit("setKeySearch", { amount: value });
    },
    setValueSelectSearch(value, key = null) {
      console.log("value::", value);
      console.log("key::", key);
      this.$store.commit("setValueSelect", { amount: value, amount1: key });
    },
    seachBySubLoc(value, key) {
      window.location.href = "/search/protectedarea";
      this.setValueSelectSearch(value, key);
    },
    seachByDesig(value, key) {
      window.location.href = "/search/protectedarea";
      this.setValueSelectSearch(value, key);
    },
    seachByStatus(value, key) {
      window.location.href = "/search/protectedarea";
      this.setValueSelectSearch(value, key);
    },
    setRankSearch(value) {
      window.location.href = "/search/species";
      this.setValueSelectSearch(value);
    },
    seachByGovType(value) {
      if (value) {
        window.location.href = "/search/protectedarea?type=" + value;
      }
    },
  },
};
</script>
<style scoped>
.d-flex {
  display: flex;
}
.justify-center {
  justify-content: center;
}
</style>
