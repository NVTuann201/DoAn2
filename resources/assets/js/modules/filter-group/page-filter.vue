<template>
  <main class="main">
    <div class="viewContentWrapper">
      <aside
        class="site-drawer is-locked"
        :class="activeFillter ? 'is-active' : ''"
      >
        <div class="site-drawer__content fill-height">
          <div class="site-drawer__header">
            <div class="site-drawer__bar">
              <a
                @click="searchAll()"
                class="gb-icon-angle-left site-drawer__bar__icon inherit"
                tooltip="Everything"
              ></a>
              <div class="site-drawer__bar__title">
                <a href class="inherit">
                  <span>{{ titleFilter }}</span>
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
                >
                  {{ number_of_filter }}
                </span>
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
                    v-on:keyup.enter="callSearch()"
                  />
                  <a
                    class="gb-icon-search search-bar__search"
                    style="cursor: pointer"
                    @click="callSearch()"
                  >
                    <span class="sr-only">{{ $t("nbds_lang.search") }}</span>
                  </a>
                </div>
              </div>
              <slot name="extra-filter"></slot>
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
      <div class="site-content fill-height">
        <div class="site-content__page fill-height" style="padding-top: 55px">
          <div class="species-search-results light-background fill-height">
            <div class="wrapper-horizontal-stripes fill-height">
              <div
                class="horizontal-stripe--paddingless white-background search-header"
              >
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-xs-12">
                      <nav
                        class="article-header__category article-header__category--deep"
                        style="text-align: center"
                      >
                        <span
                          class="article-header__category__upper ng-scope"
                          style="width: unset"
                        >
                          {{ titleSearch }}
                        </span>
                        <span
                          class="article-header__category__lower"
                          v-if="count_result != null"
                        >
                          {{ $n(count_result) + " " }}
                          {{ $t("nbds_lang.results") }}
                        </span>
                      </nav>
                    </div>
                  </div>
                </div>
              </div>
              <section
                class="horizontal-stripe--paddingless white-background"
                v-if="$slots['tabs']"
              >
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-md-12 col-xs-12">
                      <nav class="tabs">
                        <slot name="tabs"></slot>
                      </nav>
                    </div>
                  </div>
                </div>
              </section>
              <div v-show="loading" class="loading-container">
                <Loading :height="65" :width="65"></Loading>
              </div>
              <div
                class="horizontal-stripe light-background fill-height"
                v-show="!loading"
              >
                <div class="fill-height" v-show="showOther">
                  <slot name="other-tab"></slot>
                </div>
                <div
                  class="container--narrow"
                  v-show="!showOther"
                  style="width: 100%"
                >
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="card__stripe" v-show="items.length > 0">
                        <div class="card__content clearfix">
                          <div
                            :key="item.id"
                            v-for="item in items"
                            style="width: 100%"
                          >
                            <slot name="item" :item="item"></slot>
                          </div>
                        </div>
                        <paginate
                          v-show="page_count > 1"
                          v-model="current_page"
                          :page-count="page_count"
                          :page-range="3"
                          :margin-pages="2"
                          :click-handler="clickCallback"
                          :prev-text="'‹‹'"
                          :next-text="'››'"
                          :container-class="'pagination'"
                          :page-class="'page-item'"
                        ></paginate>
                      </div>
                      <div
                        class="emptyInfo"
                        v-show="items.length <= 0 && !loading"
                      >
                        <h3>
                          {{
                            $t(
                              "nbds_lang.no_results_try_to_loosen_your_filters"
                            )
                          }}
                        </h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="fab hide-on-laptop">
          <a class="gb-button--brand" @click="activeFillter = true">
            <span>
              <span class="gb-icon-filters"></span>
              <span>Filters</span>
            </span>
          </a>
        </div>
        <footer-component></footer-component>
      </div>
    </div>
  </main>
</template>

<script>
import Loading from "../loading.vue";
export default {
  props: {
    titleFilter: { type: String, require: true },
    titleSearch: { type: String, require: true },
    number_of_filter: { type: [String, Number], require: true },
    page: { type: [String, Number], require: true },
    search: { type: String, require: true },
    page_count: { type: [String, Number], require: true },
    count_result: { type: [String, Number], require: true },
    loading: Boolean,
    showOther: Boolean,
    items: { type: Array, require: true, default: () => [] },
  },
  components: { Loading },
  data: function () {
    return { activeFillter: false, filtersComponents: [] };
  },
  provide() {
    return {
      pageOption: {
        resigerFilter: this.resigerFilter,
      },
    };
  },
  mounted() {},
  methods: {
    resigerFilter(filter) {
      this.filtersComponents.push(filter);
    },
    clearAllFilter() {
      this.current_page = 1;
      this.filtersComponents.forEach((element) => {
        element.unCheckAll();
      });
      this.$emit("click:clearAllFilter");
    },
    callSearch() {
      this.$emit("click:search", this.current_page);
    },
    clickCallback(pageNum) {
      this.current_page = pageNum;
      setTimeout(() => {
        this.callSearch();
      });
    },
    searchAll() {
      this.setKeySearch(this.keyword);
      window.location.href = "/search";
    },
    setKeySearch(value) {
      this.$store.commit("setKeySearch", { amount: value });
    },
  },
  computed: {
    keyword: {
      get() {
        return this.search;
      },
      set(value) {
        this.$emit("update:search", value);
      },
    },
    current_page: {
      get() {
        return this.page;
      },
      set(value) {
        this.$emit("update:page", value);
      },
    },
  },
  watch: {},
};
</script>

<style scoped>
.fill-height {
  display: flex;
  flex-direction: column;
  flex: 1 1 auto;
}
.loading-container {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
