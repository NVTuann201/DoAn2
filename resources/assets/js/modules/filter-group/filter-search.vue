<template>
  <filter-container
    v-model="checked"
    v-bind="$attrs"
    :field_name="field_name"
    :field_count="field_count"
    @click:unCheck="onUnCheck"
    @click:un-check-all="onClearCheck"
  >
    <template v-slot:name-filter="{ item }">
      <slot name="name-filter" :item="item"></slot>
    </template>
    <template v-slot:defail-filter="{ open }">
      <div
        class="filter-group__filter__search"
        v-show="open"
        v-if="route_search"
      >
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
            v-for="(item, index) in suggestShows"
            :key="index"
            @mouseover="hoverSuggest($event, index)"
            v-bind:class="{ active: item.isActive }"
            @click="selectSuggest(index)"
          >
            <a style="cursor: pointer">
              <span class="dropdown--expandOnFocus">{{
                item[field_name]
              }}</span>
            </a>
          </li>
        </ul>
      </div>
      <template v-if="open">
        <div class="is-padded overflow-hidden" v-if="unchecked.length > 0">
          <div class="checkbox" v-for="(item, index) in unchecked" :key="index">
            <label>
              <input type="checkbox" @change="onCheck($event, index)" />
              <span class="filter-group__filter__name">
                <span
                  class="filter-group__filter__name__title"
                  :title="item[field_name]"
                  >{{ item[field_name] ? item[field_name] : item[other_name]}}</span
                >
                <span
                  class="filter-group__filter__name__count"
                  v-if="item[field_count] && showCount"
                  >{{ $n(item[field_count]) }}</span
                >
              </span>
            </label>
          </div>
        </div>
        <Loading v-else></Loading>
      </template>
    </template>
  </filter-container>
</template>

<script>
import Loading from "../loading.vue";
import vClickOutside from "v-click-outside";
import FilterContainer from "./filter-container.vue";
export default {
  inheritAttrs: false,
  directives: {
    clickOutside: vClickOutside.directive,
  },
  components: { FilterContainer, Loading },
  props: {
    field_name: {
      default: "name",
      type: String,
    },
    other_name: {
      default: "name",
      type: String,
    },
    field_count: {
      default: "count",
      type: String,
    },
    route_search: {
      required: true,
      type: String,
    },
    route_suggest: {
      type: String,
    },
    value: {
      type: Array,
    },
    params: {
      type: Object,
    },
    showCount: {
      default: true,
      type: Boolean,
    },
  },
  data: () => ({
    showSuggest: true,
    keyword: null,
    suggest: [],
    unchecked: [],
    initCheckData: [],
  }),
  computed: {
    suggestShows() {
      return this.suggest.slice(0, 10);
    },
    checked: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      },
    },
  },
  watch: {
    // params: function (value) {
    //   this.getTopSuggest();
    // },
  },
  created() {
    this.getTopSuggest();
  },
  methods: {
    onClearCheck() {
      this.unchecked = this.initCheckData.slice();
      this.checked = [];
    },
    getTopSuggest() {
      this.unchecked = [];
      if (this.route_suggest)
        axios
          .get(this.route_suggest, {
            params: Object.assign({ "top-suggest": true }, this.params),
          })
          .then((response) => {
            this.initCheckData = response.data.result;
            this.onClearCheck();
          });
    },
    onCheck($event, index) {
      $event.target.checked = false;
      var obj = this.unchecked[index];
      this.checked.push(obj);
      this.unchecked[index].show = false;
      this.unchecked.splice(index, 1);
      console.log(this.checked, $event)
    },
    onUnCheck(obj) {
      this.unchecked.push(obj);
      if (this.showCount)
        this.unchecked.sort(this.compareValues(this.field_count, "desc"));
    },
    compareValues(key, order = "asc") {
      return function (a, b) {
        if (!a.hasOwnProperty(key) || !b.hasOwnProperty(key)) return 0;
        let comparison = 0;
        if (Number(a[key]) > Number(b[key])) comparison = 1;
        else if (Number(a[key]) < Number(b[key])) comparison = -1;
        return order == "desc" ? comparison * -1 : comparison;
      };
    },
    offSuggestOutSide() {
      this.showSuggest = false;
    },
    showSuggestOutSide() {
      this.showSuggest = true;
      this.search();
    },
    search() {
      this.showSuggest = true;
      if (this.suggest.length > 0) {
        this.suggest = [];
      }
      var url = this.route_search;
      if (this.keyword) {
        url = url + "?search=" + this.keyword;
      }
      axios
        .get(url, {
          params: this.params,
        })
        .then((response) => {
          response.data.result.forEach(function (value, index) {
            value.isActive = false;
            value.isResultSearch = true;
          });
          this.suggest = response.data.result.map(function (value, index) {
            value.isActive = false;
            value.isResultSearch = true;
            return value;
          });
        });
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
      this.checked.push(this.suggest[index]);
      this.suggest = [];
      this.keyword = null;
    },
  },
};
</script>

<style></style>
