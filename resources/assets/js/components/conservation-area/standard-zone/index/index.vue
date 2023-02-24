<template>
  <page-filter
    :titleFilter="$t('conservation-area.standard-zone.title')"
    :titleSearch="$t('conservation-area.standard-zone.title')"
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
    </template>
    <template v-slot:tabs>
      <ul>
        <li
          v-for="(type, idx) in typeFilter"
          :key="type.name"
          class="tab cursor-pointer"
          :class="{
            isActive: tab === idx,
          }"
          @click="clickTab(idx)"
        >
          <a>{{ type.label }}</a>
        </li>

        <li
          class="tab cursor-pointer"
          :class="{ isActive: tab === 'map' }"
          @click="clickTab('map')"
        >
          <a>{{ $t("nbds_lang.map") }}</a>
        </li>
      </ul>
    </template>

    <template v-slot:item="{ item }">
      <AreaItem
        :item="item"
        :detailUrl="detailUrl"
        :aFilter="aFilter"
        :liFilter="liFilter"
        :pFilter="pFilter"
        @categoryChanged="setCategoryFilter($event)"
      />
    </template>
    <template v-slot:other-tab>
      <MapList
        ref="map"
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
import filterMixin from "../../components/filter.mixin";
import { category, type } from "./filter";
import AreaItem from "./AreaItem.vue";
import MapList from "../../components/MapList/Map.vue";
export default {
  mixins: [filterMixin],
  components: { PageFilter, FilterSearch, AreaItem, MapList },
  data: function () {
    return {
      searchUrl: "/search/standard-zone/data",
      detailUrl: "/search/standard-zone/",
      categoryUrl: "/search/standard-zone/category",
      mapUrl: "/search/standard-zone/map",
      category,
      type,
      mapListGetters: {
        legends: null,
        data: (data) =>
          data.map((item) => ({
            id: item.id,
            geometry: item.geom,
            name: item.ten,
            type: item.loai_hinh ? item.loai_hinh.name : "Khác",
          })),
      },
    };
  },
  mounted() {},
  watch: {},
  methods: {},
};
</script>


