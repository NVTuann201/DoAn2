<template>
  <div class="filter-group">
    <div class="inherit rtl-bootstrap" role="button">
      <h4 @click="toggle()">
        <span v-if="title">{{ title }}</span>
        <span v-else>{{ $t("nbds_lang.label_year") }}</span>
        <span
          class="pull-right"
          :class="open ? 'gb-icon-angle-up' : 'gb-icon-angle-down'"
        ></span>
      </h4>
    </div>
    <div class="filter-group__filter" v-if="open">
      <div class="is-padded">
        <div
          class="filter-group__filter__interval"
          v-for="(item, index) in selectedFilters"
        >
          <div
            class="filter-group__filter__interval__option-trigger dropdown"
            v-bind:class="{ open: item.openDropdownMenu }"
          >
            <a
              class="dropdown-toggle"
              role="button"
              @click="toggleDropdownMenuItem(item)"
            >
              <span>{{ item.option.text }}</span>
              <i></i>
            </a>
            <ul class="dropdown-menu">
              <li v-for="option in options">
                <a
                  class="small"
                  role="button"
                  :title="option.title"
                  @click="chooseItem(option, item)"
                  >{{ option.text }}</a
                >
              </li>
            </ul>
            <a
              role="button"
              class="gb-icon-close pull-right"
              @click="closeSelectedFilter(index)"
            ></a>
          </div>
          <div
            class="filter-group__filter__interval__input"
            v-if="item.option.key == 'between'"
          >
            <input
              type="text"
              :placeholder="item.value_from"
              v-model="item.value_range[0]"
            />
            <input
              type="text"
              :placeholder="item.value_to"
              class="pull-right text-right"
              v-model="item.value_range[1]"
            />
            <div class="range-slider">
              <v-range-slider
                :max="item.value_to"
                :min="item.value_from"
                :step="1"
                height="28"
                :color="colors.active"
                v-model="item.value_range"
                @change="changeFilter"
              ></v-range-slider>
            </div>
          </div>
          <div
            class="filter-group__filter__interval__input"
            v-else-if="item.option.key == 'after_start_of'"
          >
            <input
              type="text"
              :placeholder="item.value_from"
              v-model="item.value"
            />
            <div class="range-slider">
              <v-slider
                :max="item.value_to"
                :min="item.value_from"
                :step="1"
                height="28"
                :color="colors.inactive"
                :track-color="colors.active"
                v-model="item.value"
                @change="changeFilter"
              ></v-slider>
            </div>
          </div>
          <div class="filter-group__filter__interval__input" v-else>
            <input
              type="text"
              :placeholder="item.value_from"
              v-model="item.value"
            />
            <div class="range-slider">
              <v-slider
                :max="item.value_to"
                :min="item.value_from"
                :step="1"
                height="28"
                :color="colors.active"
                v-model="item.value"
                @change="changeFilter"
              ></v-slider>
            </div>
          </div>
        </div>
      </div>

      <div class="is-padded" v-if="newFilter">
        <div class="filter-group__filter__interval">
          <div
            class="filter-group__filter__interval__option-trigger dropdown"
            v-bind:class="{ open: newFilter.openDropdownMenu }"
          >
            <a
              class="dropdown-toggle"
              role="button"
              style="text-decoration: none;"
              @click="toggleDropdownMenuItem(newFilter)"
            >
              <span v-if="filter">{{ filter }}</span>
              <span v-else>{{ $t("nbds_lang.label_none_filter") }}</span>
              <i></i>
            </a>
            <ul class="dropdown-menu">
              <li v-for="option in options">
                <a
                  class="small"
                  role="button"
                  :title="option.title"
                  @click="choose(option, newFilter)"
                  >{{ option.text }}</a
                >
              </li>
            </ul>
            <a
              class="gb-icon-close pull-right"
              role="button"
              @click="closeNewFilter()"
            ></a>
          </div>
          <div class="filter-group__filter__interval__input">
            <input
              type="text"
              :placeholder="newFilter.value_from"
              v-model="newFilter.value_range[0]"
            />
            <input
              type="text"
              :placeholder="newFilter.value_to"
              class="pull-right text-right"
              v-model="newFilter.value_range[1]"
            />
            <div class="range-slider">
              <v-range-slider
                :max="newFilter.value_to"
                :min="newFilter.value_from"
                :step="1"
                height="28"
                :color="colors.active"
                disabled
                v-model="newFilter.value_range"
              ></v-range-slider>
            </div>
          </div>
        </div>
      </div>
      <div class="site-drawer__bar">
        <a
          class="site-drawer__bar__action"
          role="button"
          v-if="selectedFilters.length > 0"
          @click="clearSelectedFilter()"
          ><span>{{ $t("nbds_lang.clear") }}</span></a
        >
        <a
          class="site-drawer__bar__action"
          v-if="!newFilter"
          role="button"
          @click="addNewFilter()"
          ><span>{{ $t("nbds_lang.add") }}</span></a
        >
      </div>
    </div>

    <div class="filter-group__filter filter-group__filter--collapsed" v-else>
      <div class="is-padded rtl-bootstrap">
        <div class="checkbox" v-for="(item, index) in selectedFilters">
          <label>
            <input type="checkbox" @change="onCheck($event, index)" checked />
            <span>
              <span v-if="item.option.key == 'between'">{{
                "Between start of " +
                item.value_from +
                " and end of " +
                item.value_to
              }}</span>
              <span v-else-if="item.option.key == 'is'">{{
                "Is " + item.value
              }}</span>
              <span v-else-if="item.option.key == 'before_end_of'">{{
                "Before end of " + item.value
              }}</span>
              <span v-else-if="item.option.key == 'after_start_of'">{{
                "After start of " + item.value
              }}</span>
            </span>
          </label>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as constants from "../constants.js";

export default {
  props: {
    title: {
      type: String,
    },
    filter: {
      type: String,
    },
  },
  data: function () {
    return {
      open: false,
      newFilter: {
        openDropdownMenu: false,
        option: null,
        value_from: 1990,
        value_to: new Date().getFullYear(),
        value: null,
        value_range: [1990, new Date().getFullYear()],
      },
      options: [
        { key: "between", text: "Between", title: "Filter between of years" },
        { key: "is", text: "Is", title: "Filter exact year" },
        {
          key: "before_end_of",
          text: "Before end of",
          title: "Filter before end of year",
        },
        {
          key: "after_start_of",
          text: "After start of",
          title: "Filter after start of year",
        },
      ],
      selectedFilters: [],
      colors: {
        active: "#888",
        inactive: "#d4d4d4",
      },
    };
  },
  mounted() {},
  methods: {
    toggle() {
      this.open = !this.open;
    },
    toggleDropdownMenuItem(filter) {
      if (filter.hasOwnProperty("openDropdownMenu"))
        filter.openDropdownMenu = !filter.openDropdownMenu;
    },
    chooseItem(option, item) {
      item.option = option;
      item.openDropdownMenu = false;
    },
    choose(option_filter, filter) {
      var value = filter.value;
      if (option_filter.key != "between") {
        value = Math.round((filter.value_from + filter.value_to) / 2, 0);
      }
      this.selectedFilters.push({
        option: option_filter,
        value_from: filter.value_from,
        value_to: filter.value_to,
        value: value,
        value_range: filter.value_range,
        openDropdownMenu: false,
      });
      this.closeNewFilter();
    },
    closeSelectedFilter(index) {
      this.selectedFilters.splice(index, 1);
      if (this.selectedFilters.length == 0) {
        this.addNewFilter();
      }
    },
    onCheck($event, index) {
      $event.target.checked = true;
      this.selectedFilters.splice(index, 1);
      if (this.selectedFilters.length == 0) {
        this.addNewFilter();
      }
    },
    closeNewFilter() {
      if (this.selectedFilters.length > 0) {
        this.newFilter = null;
      }
    },
    clearSelectedFilter() {
      this.selectedFilters = [];
      this.addNewFilter();
    },
    addNewFilter() {
      this.newFilter = {
        openDropdownMenu: false,
        option: null,
        value_from: 1990,
        value_to: new Date().getFullYear(),
        value: null,
        value_range: [1990, new Date().getFullYear()],
      };
    },
    changeFilter() {
      var inputEmit = [];

      this.selectedFilters.forEach(function (item, index) {
        if (item.option) {
          if (item.option.key == "between") {
            this.push({
              option: item.option.key,
              value: item.value_range,
            });
          } else {
            this.push({
              option: item.option.key,
              value: [item.value],
            });
          }
        }
      }, inputEmit);
      this.$emit("input", inputEmit);
    },
  },
  watch: {
    selectedFilters: function (newValue, oldValue) {
      var inputEmit = [];
      newValue.forEach(function (item, index) {
        if (item.option) {
          if (item.option.key == "between") {
            this.push({
              option: item.option.key,
              value: [item.value_from, item.value_to],
            });
          } else {
            this.push({
              option: item.option.key,
              value: [item.value],
            });
          }
        }
      }, inputEmit);
      this.$emit("input", inputEmit);
    },
  },
};
</script>

<style scoped>
a {
  text-decoration: none;
}
.range-slider {
  margin-top: -16px;
  margin-bottom: -24px;
}
</style>
