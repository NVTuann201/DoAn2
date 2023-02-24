<template>
  <page-filter
    titleFilter="Tri thức truyền thông"
    titleSearch="Tri thức truyền thông"
    :count_result="resultCount"
    :number_of_filter="filtersCount"
    :page.sync="page"
    :page_count="pagesCount"
    :items="data"
    :loading="loading"
    :search.sync="filter.search"
    @click:search="search"
    :showOther="isShowMap"
  >
    <template v-slot:extra-filter>
      <filter-search
        v-for="f in categoryFilters"
        :key="f.key"
        :title="f.label"
        :route_suggest="categoryUrl"
        field_name="name"
        :route_search="categoryUrl"
        :params="{ name: f.name }"
        :showCount="false"
        v-model="filter[f.key]"
        @change="search"
      >
      </filter-search>
      <FilterYear
        v-for="f in yearFilter"
        :key="f.key"
        :label="f.label"
        v-model="filter[f.key]"
      />
    </template>

    <template v-slot:item="{ item }">
      <AreaItem
        :item="item"
        :detailUrl="detailUrl"
        :aFilter="aFilter"
        :liFilter="liFilter"
        :pFilter="pFilter"
        @categoryChanged="setCategoryFilter($event)"
        @yearChanged="setYearFilter($event)"
      />
    </template>
  </page-filter>
</template>

<script>
import PageFilter from "@/components/layouts/page-filter.vue";
import FilterSearch from "@/modules/filter-group/filter-search.vue";
import FilterYear from "@/modules/filter-group/filter-year.vue";
import filterMixin from "../../components/filter.mixin";
import { category, type, year } from "./filter";
import AreaItem from "./AreaItem.vue";
export default {
  mixins: [filterMixin],
  components: { PageFilter, FilterSearch, AreaItem, FilterYear },
  data: function () {
    return {
      searchUrl: "/search/genetic-knowledge/data",
      detailUrl: "/search/genetic-knowledge/",
      categoryUrl: "/search/genetic-knowledge/category",
      mapUrl: "/search/genetic-knowledge/map",
      category,
      type,
      year,
    };
  },
  mounted() {},
  watch: {},
  methods: {},
};
</script>


