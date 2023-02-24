<template>
  <div class="filter-group rtl-bootstrap">
    <a class="inherit">
      <h4 @click="toggle()">
        <span>{{ title }}</span>
        <span
          class="pull-right ng-scope"
          :class="open ? 'gb-icon-angle-up' : 'gb-icon-angle-down'"
        ></span>
      </h4>
    </a>
    <div
      class="filter-group__filter"
      :class="{ 'filter-group__filter--collapsed': !open }"
    >
      <div
        class="is-padded"
        v-if="checked && checked.length > 0"
        v-show="!(disableShowCheckedWhenOpen && open)"
      >
        <div class="checkbox" v-for="(item, index) in checked" :key="index">
          <label>
            <input
              type="checkbox"
              @change="unCheck($event, index)"
              :checked="true"
              :value="true"
            />
            <span class="filter-group__filter__name">
              <div
                class="filter-group__filter__name__bar"
                style="width: 0%"
              ></div>
              <span class="filter-group__filter__name__title">
                <slot name="name-filter" :item="item">
                  {{ item[field_name] }}
                </slot>
              </span>
            </span>
          </label>
        </div>
      </div>
      <Loading v-if="loading && open"></Loading>
      <template v-else>
        <slot :open="open">
          <slot name="defail-filter" :open="open"> </slot>
        </slot>
      </template>
      <div class="site-drawer__bar" v-if="open && checked.length > 0">
        <a
          class="site-drawer__bar__action"
          style="cursor: pointer"
          @click="unCheckAll()"
        >
          <span>{{ $t("nbds_lang.clear") }}</span>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import Loading from "../loading.vue";
export default {
  components: { Loading },
  props: {
    title: {
      default: "Filter",
      type: String,
    },
    field_name: {
      default: "name",
      type: String,
    },
    field_count: {
      default: "count",
      type: String,
    },
    value: {
      type: Array,
      default: () => [],
    },
    disableShowCheckedWhenOpen: Boolean,
    loading: Boolean,
  },
  data: function () {
    return {
      unchecked: [],
      options: [],
      keyword: null,
      suggest: [],
      open: false,
    };
  },
  inject: {
    pageOption: {},
  },
  created() {
    this.pageOption &&
      this.pageOption.resigerFilter &&
      this.pageOption.resigerFilter(this);
  },
  methods: {
    onCheck($event, index) {
      $event.target.checked = false;
      var obj = this.unchecked[index];
      this.checked.push(obj);
      this.unchecked.splice(index, 1);
    },
    unCheck($event, index) {
      $event.target.checked = true;
      var obj = this.checked[index];
      if (!obj.isResultSearch) {
        this.unchecked.push(obj);
        this.unchecked.sort(this.compareValues(this.field_count, "desc"));
      }
      this.$emit("click:unCheck", obj);
      this.checked.splice(index, 1);
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
    unCheckAll() {
      this.checked = [];
      this.$emit("click:un-check-all");
    },
    toggle() {
      this.open = !this.open;
    },
  },
  computed: {
    checked: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      },
    },
  },
};
</script>
<style scoped>
.filter-group__filter.filter-group__filter--collapsed,
.filter-group__filter.filter-group__filter--collapsed > * {
  border: none;
}
.inherit {
  text-decoration: none;
  cursor: pointer;
}
</style>
