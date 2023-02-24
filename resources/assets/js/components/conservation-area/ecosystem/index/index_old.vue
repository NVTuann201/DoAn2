<template>
  <page-filter
    :titleFilter="$t('conservation-area.ecosystem.title')"
    :titleSearch="$t('conservation-area.ecosystem.title')"
    :count_result="resultCount"
    :number_of_filter="filtersCount"
    :page.sync="page"
    :page_count="pagesCount"
    :items="data"
    :loading="loading"
    :search.sync="filter.search"
    @click:search="search"
    showOther
  >
    <template v-slot:extra-filter>
      <filter-search
        v-for="f in categoryFilters"
        :key="f.key"
        :title="f.label"
        :route_suggest="!f.disabledSuggest ? categoryUrl : ''"
        field_name="name"
        :route_search="!f.disabledSearch ? categoryUrl : ''"
        :params="{ name: f.name }"
        :showCount="!!f.field_count"
        v-model="filter[f.key]"
        @change="search"
        :field_count="f.field_count"
      >
      </filter-search>
    </template>

    <template v-slot:other-tab>
      <MapList
        ref="map"
        @change-data="onUpdateData"
        :mapListGetters="mapListGetters"
        :mapUrl="mapUrl"
        :detailUrl="detailUrl"
      />
    </template>
  </page-filter>
</template>

<script>
import PageFilter from "@/components/layouts/page-filter.vue";
import FilterSearch from "@/modules/filter-group/filter-search.vue";
import MapList from "@/modules/map/list/map-list.vue";
import filterMixin from "../../components/filter.mixin";
import { category, type } from "./filter";
export default {
  mixins: [filterMixin],
  components: { PageFilter, FilterSearch, MapList },
  data: function () {
    return {
      searchUrl: "/search/ecosystem/data",
      detailUrl: "/search/ecosystem/",
      categoryUrl: "/search/ecosystem/category",
      mapUrl: "/search/ecosystem/map",
      category,
      type,
      mapListGetters: {
        legends: null,
        data: (data) =>
          data.map((item) => ({
            id: item.id,
            geometry: item.geom,
            name: item.ten,
            type: item.he_sinh_thai ? item.he_sinh_thai.name : "Khác",
          })),
      },
    };
  },
  watch: {},
  methods: {
    onUpdateData(items = []) {
      this.resultCount = items.length;
    },
    getParamSearch() {
      var url_string = window.location.href;
      var url = new URL(url_string);
      return url.searchParams.get("hesinhthai");
    },
    async search() {
      let param = this.getParamSearch();
      if (this.$refs.map) {
        try {
          let filter = this.convertFilter(this.filter)
        
          this.loading = true;
          await this.$refs.map.getMapData(filter);
        } finally {
          this.loading = false;
        }
      }
    },
  },
};
</script>
