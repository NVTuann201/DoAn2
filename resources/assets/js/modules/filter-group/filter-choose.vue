<template>
  <filter-container
    v-model="checked"
    v-bind="$attrs"
    :field_name="field_name"
    :field_count="field_count"
    @click:unCheck="unCheck"
    @click:un-check-all="unCheckAll"
    disable-show-checked-when-open
    :loading="loading"
  >
    <template v-slot:name-filter="{ item }">
      <slot name="name-filter" :item="item"> {{ item[field_name] }}</slot>
    </template>
    <template v-slot:defail-filter="{ open }">
      <div
        class="is-padded overflow-hidden"
        v-if="open && p_options.length > 0"
      >
        <div class="checkbox" v-for="(item, index) in p_options" :key="index">
          <label>
            <input
              type="checkbox"
              :value="item.checked"
              :checked="item.checked"
              @change="onCheck(index, $event.target.checked)"
            />
            <span
              class="filter-group__filter__name"
              @click.prevent="onCheck(index, !item.checked)"
            >
              <span class="filter-group__filter__name__title">
                <slot name="name-filter" :item="item">
                  {{ item[field_name] }}
                </slot>
              </span>
              <span
                class="filter-group__filter__name__count"
                v-if="item[field_count]"
                >{{ $n(item[field_count]) }}</span
              >
            </span>
          </label>
        </div>
      </div>
    </template>
  </filter-container>
</template>

<script>
import FilterContainer from "./filter-container.vue";
export default {
  inheritAttrs: false,
  components: { FilterContainer },
  props: {
    multiple: { type: Boolean, default: true },
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
    },
    options: {
      type: Array,
    },
    route_suggest: {
      type: String,
    },
  },
  data: () => ({
    p_options: [],
    loading: false,
  }),
  computed: {
    checked: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value ? value : this.defaultValue);
      },
    },
    defaultValue() {
      if (this.multiple) {
        return [];
      }
    },
  },
  watch: {
    value: {
      handler(value) {
        this.checkValue(value);
      },
      deep: true,
    },
    options: {
      handler(value) {
        this.p_options = value || [];
      },
      immediate: true,
    },
  },
  created() {
    if (this.route_suggest) {
      this.getTopSuggest();
    }
  },
  methods: {
    getTopSuggest() {
      this.loading = true;
      axios.get(this.route_suggest).then((response) => {
        response.data.result.forEach(function (value, index) {
          value.checked = false;
        });
        this.p_options.push(...response.data.result);
        this.checkValue(this.value);
        this.loading = false;
      });
    },
    unCheckAll() {
      this.checked = this.p_options.forEach((x) => (x.checked = false));
    },
    onCheck(index, checked) {
      if (!this.multiple) {
        this.p_options.forEach((x) => (x.checked = false));
      }
      this.$set(this.p_options[index], "checked", checked);

      this.checked = this.p_options.filter(function (item) {
        return item.checked;
      });
    },
    unCheck(value) {
      let obj = this.options.find(
        (x) => x[this.field_name] == value[this.field_name]
      );
      obj.checked = false;
    },
    checkValue(value) {
      if (!value || value.length == 0) {
        this.unCheckAll();
        return;
      }
      for (var i = 0; i < value.length; i++) {
        for (var j = 0; j < this.p_options.length; j++) {
          if (this.p_options[j][this.field_name] == value[i][this.field_name]) {
            this.$set(this.p_options[j], "checked", true);
            break;
          }
        }
      }
    },
  },
};
</script>

<style></style>
