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
    <div class="filter-group__filter pb-2" v-if="open">
      <div class="is-padded">
        <div
          class="filter-group__filter__interval"
          v-for="(item, index) in selectedFilters"
          :key="index"
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
              <span>{{ $t(item.option.text) }}</span>
              <i></i>
            </a>
            <ul class="dropdown-menu">
              <li v-for="(option, index) in options" :key="index">
                <a
                  class="small"
                  role="button"
                  :title="$t(option.title)"
                  @click="chooseItem(option, item)"
                  >{{ $t(option.text) }}</a
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
            class="filter-group__filter__interval__input pa-0 ma-0"
            v-if="item.option.key == 'between'"
          >
            <div class="range-slider ma-0">
              <div class="pb-2">
                <field-date
                  v-model="item.value_from"
                  @input="changeFilter"
                  :max="item.value_to"
                ></field-date>
              </div>
              <div>{{ $t("filter_date.and") }}</div>
              <div>
                <field-date
                  :min="item.value_from"
                  v-model="item.value_to"
                  @input="changeFilter"
                ></field-date>
              </div>
            </div>
          </div>
          <div
            class="filter-group__filter__interval__input"
            v-else-if="item.option.key == 'after_start_of'"
          >
            <div class="range-slider">
              <field-date
                v-model="item.value"
                @input="changeFilter"
              ></field-date>
            </div>
          </div>
          <div class="filter-group__filter__interval__input" v-else>
            <div class="range-slider">
              <field-date
                v-model="item.value"
                @input="changeFilter"
              ></field-date>
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
              style="text-decoration: none"
              @click="toggleDropdownMenuItem(newFilter)"
            >
              <span v-if="filter">{{ filter }}</span>
              <span v-else>{{ $t("nbds_lang.label_none_filter") }}</span>
              <i></i>
            </a>
            <ul class="dropdown-menu">
              <li v-for="(option, index) in options" :key="index">
                <a
                  class="small"
                  role="button"
                  :title="$t(option.title)"
                  @click="choose(option, newFilter)"
                  >{{ $t(option.text) }}</a
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
            <div class="range-slider">
              <v-range-slider
                :max="1990"
                :min="new Date().getFullYear()"
                :step="1"
                height="28"
                :color="colors.active"
                disabled
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
        <div
          class="checkbox"
          v-for="(item, index) in selectedFilters"
          :key="index"
        >
          <label>
            <input type="checkbox" @change="onCheck($event, index)" checked />
            <span>
              <span v-if="item.option.key == 'between'">
                {{
                  $t("filter_date.option.between.label", {
                    startDay: formatDate(item.value_from),
                    endDay: formatDate(item.value_to),
                  })
                }}
              </span>
              <span v-else-if="item.option.key == 'is'">
                {{
                  $t("filter_date.option.is.label", {
                    day: formatDate(item.value),
                  })
                }}
              </span>
              <span v-else-if="item.option.key == 'before_end_of'">
                {{
                  $t("filter_date.option.before_end_of.label", {
                    day: formatDate(item.value),
                  })
                }}
              </span>
              <span v-else-if="item.option.key == 'after_start_of'">
                {{
                  $t("filter_date.option.after_start_of.label", {
                    day: formatDate(item.value),
                  })
                }}
              </span>
            </span>
          </label>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "../../../../node_modules/moment/moment.js";
import * as constants from "../constants.js";
import fieldDate from "./fieldDate.vue";
export default {
  components: { fieldDate },
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
        value_from: "1990-01-01",
        value_to: "2020-01-01",
        value: null,
      },
      options: [
        {
          key: "between",
          text: "filter_date.option.between.text",
          title: "filter_date.option.between.title",
        },
        {
          key: "is",
          text: "filter_date.option.is.text",
          title: "filter_date.option.is.title",
        },
        {
          key: "before_end_of",
          text: "filter_date.option.before_end_of.text",
          title: "filter_date.option.before_end_of.title",
        },
        {
          key: "after_start_of",
          text: "filter_date.option.after_start_of.text",
          title: "filter_date.option.after_start_of.title",
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
    formatDate(value) {
      if (value) {
        return moment(String(value)).format("DD-MM-YYYY");
      }
    },
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
      setTimeout(() => {
        this.changeFilter();
      }, 0);
    },
    choose(option_filter, filter) {
      var value = filter.value;
      if (option_filter.key != "between") {
        value = filter.value_from;
      }
      this.selectedFilters.push({
        option: option_filter,
        value_from: filter.value_from,
        value_to: filter.value_to,
        value: value,
        openDropdownMenu: false,
      });

      this.closeNewFilter();
      setTimeout(() => {
        this.changeFilter();
      }, 0);
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
        value_from: "1990-01-01",
        value_to: "2020-01-01",
        value: null,
      };
      setTimeout(() => {
        this.changeFilter();
      }, 0);
    },
    changeFilter() {
      var inputEmit = [];

      this.selectedFilters.forEach(function (item, index) {
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
  padding-bottom: 16px;
}
</style>
