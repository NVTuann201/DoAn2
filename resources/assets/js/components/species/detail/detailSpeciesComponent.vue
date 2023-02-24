<template>
  <main id="main" class="main" role="main">
    <div ui-view="main" class="viewContentWrapper" style>
      <AsideSpecies :value="data"></AsideSpecies>
      <div class="site-content site-container">
        <div class="site-content__page">
          <div class="species-search-results light-background">
            <div class="wrapper-horizontal-stripes">
              <div
                class="horizontal-stripe article-header white-background p-b-1"
              >
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-xs-12">
                      <nav
                        class="article-header__category article-header__category--deep"
                        style="text-align: center"
                      >
                        <span
                          class="article-header__category__upper"
                          style="width: unset"
                          >{{ item ? item.rank : "" }}</span
                        >
                        <span
                          class="article-header__category__lower ng-isolate-scope"
                        >
                          {{ item ? item.status : "" }}
                        </span>
                      </nav>
                    </div>
                  </div>
                </div>
                <div class="container--desktop">
                  <div class="row">
                    <header class="col-xs-12 text-center">
                      <h1 class="ng-isolate-scope">
                        <span class="ng-binding">{{
                          item ? item.name : ""
                        }}</span>
                      </h1>
                      <div v-if="item && item.name_vietnamese">
                        <span class="vernacular ng-binding">
                          {{ item.name_vietnamese }}
                        </span>
                        {{ $t("nbds_lang.in_vietnam") }}
                      </div>
                    </header>
                    <div
                      v-if="accepted && accepted.length > 0"
                      class="article-header__highlights"
                      style="text-align: center"
                    >
                      <div>
                        <span
                          dir="auto"
                          class="article-header__meta ng-binding"
                        >
                          Synonym of</span
                        >
                        <span dir="auto" class="discreet--very">
                          <a
                            class="inherit text-transform__none ng-isolate-scope"
                            :href="'/detail/indexspecies/' + accepted[0].id"
                          >
                            <span class="ng-binding">{{
                              accepted[0].name
                            }}</span>
                          </a>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div
                class="horizontal-stripe--paddingless white-background seperator--b"
              >
                <div class="container--desktop">
                  <div class="tabs__wrapper">
                    <div class="tabs__actions">
                      <ul>
                        <li class="tab tab-right">
                          <span>
                            <a class="gb-button--brand ng-binding"
                              >{{ $n(occurrencesCount) }}
                              <span class="gb-button--brand__label">{{
                                $t("nbds_lang.occurrences")
                              }}</span>
                            </a>
                          </span>
                        </li>
                        <li class="tab tab-right" ng-if="speciesKey2.isNub">
                          <span>
                            <a class="gb-button--primary ng-binding"
                              >{{ item ? $n(item.number_count) : 0 }}
                              <span class="gb-button--primary__label">{{
                                $t("nbds_lang.specie")
                              }}</span>
                            </a>
                          </span>
                        </li>
                      </ul>
                    </div>
                    <div class="tabs tabs--noBorder">
                      <ul class="anchorTabs">
                        <li
                          class="tab"
                          :class="popUpTab == 1 ? 'isActive' : ''"
                          @click="popUpTab = 1"
                        >
                          <a>{{ $t("nbds_lang.overview") }}</a>
                        </li>
                        <li
                          class="tab"
                          :class="popUpTab == 2 ? 'isActive' : ''"
                          @click="popUpTab = 2"
                        >
                          <a>{{ $t("nbds_lang.metrics") }}</a>
                        </li>
                        <li
                          v-if="imagesCount > 0"
                          class="tab"
                          :class="popUpTab == 3 ? 'isActive' : ''"
                          @click="popUpTab = 3"
                        >
                          <a>{{ $t("nbds_lang.gallery") }}</a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="submenu-wrapper rtl-bootstrap">
                <div class="ng-scope" v-if="popUpTab == 1 && item">
                  <Overview :item="item"> </Overview>
                </div>
              </div>
              <div class="submenu-wrapper rtl-bootstrap">
                <div class="ng-scope" v-show="popUpTab == 2">
                  <metricsSpecies
                    :data="value"
                    @occurrencesCount="occurrencesCount = $event"
                  >
                  </metricsSpecies>
                </div>
              </div>
              <div class="submenu-wrapper rtl-bootstrap">
                <div class="ng-scope" v-if="popUpTab == 3 && imagesCount > 0">
                  <Gallery :url="urlImage"></Gallery>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="fab hide-on-laptop">
          <a href class="gb-button--brand">
            <span>
              <span class="gb-icon-filters"></span>
              <span class="ng-scope">{{ $t("nbds_lang.filters") }}</span>
            </span>
          </a>
        </div>
        <footer-component></footer-component>
      </div>
    </div>
  </main>
</template>

<script>
import * as env from "../../../env.js";
import Multiselect from "vue-multiselect";
import AsideSpecies from "./asideSpecies";
import metricsSpecies from "./metrics";
import Overview from "./overview";

import Gallery from "@/modules/file/image-gallery.vue";
export default {
  components: {
    AsideSpecies,
    Multiselect,
    metricsSpecies,
    Gallery,
    Overview,
  },
  props: ["value"],
  data: function () {
    return {
      item: null,
      data: null,
      accepted: [],
      popUpSearch: -1,
      popUpTab: 1,
      occurrencesCount: 0,
      imagesCount: 0,
      taxon: [],
      datasets: [],
    };
  },
  created() {
    axios
      .get(env.endpointhttp + "detail/species/" + this.value)
      .then((response) => {
        this.item = response.data.result;
        this.data = response.data;
        this.accepted = response.data.accepted;
        this.imagesCount = response.data.images;
      });
  },
  computed: {
    urlImage() {
      return `/detail/indexspecies/images/${this.value}`;
    },
  },
  methods: {},
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
</style>
