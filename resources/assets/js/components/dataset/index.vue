<template>
  <page-filter
    :titleFilter="$t('nbds_lang.submenu_dataset')"
    :titleSearch="$t('nbds_lang.submenu_dataset_desc')"
    :count_result="count_result"
    :number_of_filter="number_of_filter"
    :page.sync="current_page"
    :page_count="page_count"
    :items="data"
    :loading="loading"
    :search.sync="keyword"
    @click:clearAllFilter="clearAllFilter"
    @click:search="search"
  >
    <template v-slot:extra-filter>
      <filter-search
        :title="$t('nbds_lang.publisher')"
        :route_suggest="gettoppublishers"
        field_count="dataset_resources_count"
        field_name="name_vietnamese"
        :route_search="getpublishers"
        v-model="filter_publishers"
      ></filter-search>

      <filter-search
        :title="$t('nbds_lang.publishing_of_area')"
        :route_suggest="gettoppublishingofareas"
        field_count="dataset_resources_count"
        :route_search="getpublishingofareas"
        v-model="filter_publishing_of_areas"
      ></filter-search>

      <FilterDay
        v-model="filter_years"
        :title="$t('nbds_lang.dataset_resources.publication_date')"
      ></FilterDay>
      <filter-container
        :title="$t('nbds_lang.label_adv_srch_taxon')"
        v-model="taxonFilter"
      >
        <template v-slot:defail-filter="{ open }">
          <div class="is-padded mt-2" v-show="open" style="margin-left: -10px">
            <taxon-tree @click:item="taxonAddItem"></taxon-tree>
          </div>
        </template>
      </filter-container>

      <filter-choose
        :title="$t('nbds_lang.resource_type')"
        v-model="resourceFilter"
        :route_suggest="asyncResourcetypes"
      >
      </filter-choose>
    </template>

    <template v-slot:tabs>
      <ul>
        <li
          class="tab cursor-pointer"
          @click="clickTab()"
          :class="{
            isActive: filter_dataset_type == null,
          }"
        >
          <a>{{ $t("nbds_lang.all") }}</a>
        </li>
        <li
          class="tab cursor-pointer"
          @click="clickTab('Occurrence')"
          :class="{
            isActive: filter_dataset_type == 'Occurrence',
          }"
        >
          <a>{{ $t("dataset_type.occurrence") }}</a>
        </li>
        <li
          class="tab cursor-pointer"
          @click="clickTab('Taxon')"
          :class="{
            isActive: filter_dataset_type == 'Taxon',
          }"
        >
          <a>{{ $t("dataset_type.taxon") }}</a>
        </li>
      </ul>
    </template>
    <template v-slot:item="{ item }">
      <dataset-item
        :item="item"
        @click:searchTaxon="searchTaxon"
      ></dataset-item>
    </template>
  </page-filter>
</template>

<script>
import * as env from "../../env.js";
import Multiselect from "vue-multiselect";
import * as routes from "../../routes.js";
import Loading from "../loading.vue";
import FilterDay from "../filterDay.vue";
import datasetItem from "./dataset-item.vue";
import FilterSearch from "@/modules/filter-group/filter-search.vue";
import FilterChoose from "@/modules/filter-group/filter-choose.vue";
import TaxonTree from "@/modules/filter-group/taxontree.vue";
import FilterContainer from "@/modules/filter-group/filter-container.vue";

export default {
  props: ["dataset_type", "publisher", "protectedarea"],
  components: {
    Multiselect,
    FilterChoose,
    FilterSearch,
    Loading,
    FilterDay,
    datasetItem,
    FilterContainer,
    TaxonTree,
  },
  data() {
    return {
      current_page: 1,
      page_count: 0,
      data: [],
      keyword: null,
      count_result: null,
      loading: false,
      filter_publishers: [],
      filter_publishing_of_areas: [],
      filter_dataset_type: null,
      suggest: [],
      filter_years: [],
      dataset_inheritance_type: [{ dataset_inheritance_type: "Public" }],
      taxonFilter: [],
      resourceFilter: [],
    };
  },
  mounted() {
    if (this.dataset_type == "Occurrence" || this.dataset_type == "Taxon") {
      this.filter_dataset_type = this.dataset_type;
    }
    if (this.publisher && this.publisher.hasOwnProperty("id")) {
      this.filter_publishers.push(this.publisher);
    }
    if (this.protectedarea && this.protectedarea.hasOwnProperty("id")) {
      this.filter_publishing_of_areas.push(this.protectedarea);
    }
    if (this.$store.state.keySearch) {
      this.keyword = this.$store.state.keySearch;
      this.search();
      this.$store.commit("setKeySearch", { amount: null });
    } else {
      this.search();
    }
  },
  methods: {
    search(page) {
      this.loading = true;
      let url = env.endpointhttp + routes.getdatasetresources;
      let filter_publisher_ids = [];
      let filter_protected_area_ids = [];
      let filter_dataset_inheritance_type = [];
      this.filter_publishers.forEach(function (item, index) {
        filter_publisher_ids.push(item.id);
      });
      this.filter_publishing_of_areas.forEach(function (item, index) {
        filter_protected_area_ids.push(item.id);
      });
      if (
        this.dataset_inheritance_type &&
        this.dataset_inheritance_type.length
      ) {
        filter_dataset_inheritance_type = this.dataset_inheritance_type.map(
          (x) => x.dataset_inheritance_type
        );
      }
      let params = {
        page,
        publishers: filter_publisher_ids,
        protected_area: filter_protected_area_ids,
        dataset_type: this.filter_dataset_type,
        search: this.keyword,
        filter_years: this.filter_years,
        filter_dataset_inheritance_type: filter_dataset_inheritance_type,
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
          let res = response.data.result;
          this.data = res.data;
          this.count_result = res.total;
          this.page_count = res.last_page;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    clearAllFilter: function () {
      this.filter_publishers = [];
      this.taxonFilter = [];
      this.filter_publishing_of_areas = [];
      this.resourceFilter = [];
      this.filter_years = [];
      this.current_page = 1;
    },
    clickCallback(pageNum) {
      this.current_page = pageNum;
      this.search();
    },
    clickTab(type) {
      if (this.current_page > 1) this.current_page = 1;
      this.filter_dataset_type = type;
    },
    getSuggestSearch() {
      if (this.suggest.length > 0) {
        this.suggest = [];
      }
      if (this.keyword) {
        let url = env.endpointhttp + routes.asyncdatasetresources;
        axios
          .get(url, {
            params: {
              search: this.keyword,
            },
          })
          .then((response) => {
            response.data.result.forEach(function (value, index) {
              value.isActive = false;
            });
            this.suggest = response.data.result;
          })
          .catch(function (error) {
            console.log(error);
          });
      }
    },
    hoverSuggest($event, index) {
      this.suggest.forEach(function (value, index) {
        if (value.isActive) {
          value.isActive = false;
          return;
        }
      });
      this.suggest[index].isActive = true;
    },
    selectSuggest(index) {
      var model = this.suggest[index];
      if (model && model.hasOwnProperty("id")) {
        window.location.href = routes.detaildataset + model.id;
      } else {
        console.log("Object suggest do not have property id");
      }
    },
    searchAll() {
      this.setKeySearch(this.keyword);
      window.location.href = "/search";
    },
    setKeySearch(value) {
      this.$store.commit("setKeySearch", { amount: value });
    },
    searchTaxon(value) {
      this.setValueSearch(value);
      window.location.href = "/search/taxon";
    },
    setValueSearch(value) {
      this.$store.commit("setValueSelect", { amount: value });
      console.log(
        "this.$store.state.valueSelect::",
        this.$store.state.valueSelect
      );
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
  },
  computed: {
    getpublishers: function () {
      return env.endpointhttp + routes.asynchronouspublishers;
    },
    gettoppublishers: function () {
      return env.endpointhttp + routes.gettoppublishers;
    },
    gettoppublishingofareas: function () {
      return env.endpointhttp + routes.gettoppublishingofareas;
    },
    getpublishingofareas: function () {
      return env.endpointhttp + routes.getpublishingofareas;
    },
    number_of_filter() {
      return (
        this.filter_publishers.length +
        this.filter_publishing_of_areas.length +
        this.filter_years.length +
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
      this.search();
    },
    filter_publishing_of_areas: function () {
      this.search();
    },
    filter_dataset_type: function () {
      this.search();
    },
    filter_years: function () {
      this.search();
    },
    dataset_inheritance_type() {
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
