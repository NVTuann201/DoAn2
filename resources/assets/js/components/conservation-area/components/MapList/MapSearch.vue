<template>
  <div class="search-map" v-show="data && data.length > 0">
    <div class="filter-group__filter__search">
      <input
        type="text"
        class="fit-suggestions"
        :placeholder="$t('nbds_lang.search')"
        v-model="keyword"
        @input="search()"
        @click="showSuggestOutSide()"
      />
      <ul
        class="dropdown-menu ng-isolate-scope"
        style="top: 37px; left: 0px; display: block"
        v-if="suggest.length > 0"
        v-show="showSuggest"
        v-click-outside="offSuggestOutSide"
      >
        <li
          v-for="(item, index) in suggest"
          @mouseover="hoverSuggest(index)"
          v-bind:class="{ active: indexActive == index }"
          @click="selectSuggest(item)"
          :key="item.properties.id"
        >
          <a style="cursor: pointer">
            <span class="dropdown--expandOnFocus">{{
              $t("conservation-area.conservation-infrastructure.item.name") +
              ": " +
              item.properties.name
            }}</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Array,
      default: () => [],
    },
    detailUrl: String,
  },
  data() {
    return {
      keyword: "",
      indexActive: -1,
      showSuggest: true,
      suggest: [],
    };
  },
  methods: {
    showSuggestOutSide() {
      this.showSuggest = true;
      this.search();
    },
    search() {
      this.showSuggest = true;
      if (this.suggest.length > 0) {
        this.suggest = [];
      }
      this.suggest = this.data
        .filter((x) =>
          x.properties.name.toLowerCase().includes(this.keyword.toLowerCase())
        )
        .slice(0, 10);
    },
    hoverSuggest(index) {
      this.indexActive = index;
    },
    offSuggestOutSide() {
      this.showSuggest = false;
    },
    selectSuggest(e) {
      if (e.properties && e.properties.id) {
        window.open(this.detailUrl + e.properties.id, "_blank");
      }
    },
  },
};
</script>

<style lang="scss">
.search-map {
  position: absolute;
  z-index: 3;
  top: 10px;
  right: 10px;
  width: 300px;
  background: white;
}
</style>