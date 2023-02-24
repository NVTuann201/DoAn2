<template>
  <div>
    <div class="table-responsive">
      <table
        class="table m-t-30 table-hover contact-list footable footable-1 footable-paging footable-paging-center breakpoint-lg"
      >
        <thead>
          <tr class="footable-header">
            <th class="text-center">{{ $t("admin.label.no") }}</th>
            <th
              :class="[`text-${header.algin || 'left'}`]"
              v-for="(header, i) in headers"
              :key="i"
            >
              {{ header.text }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading">
            <td :colspan="headers.length + 1">
              <components-loading></components-loading>
            </td>
          </tr>
          <tr v-else-if="items && items.length == 0">
            <td :colspan="headers.length + 1" class="emptyInfomation">
              <h5>{{ $t("admin.error.no_data") }}</h5>
            </td>
          </tr>
          <template v-else>
            <tr v-for="(item, index) in items" :key="index">
              <td class="position-relative">
                <span class="position-absolute text-center-td">
                  {{ (page - 1) * 10 + index + 1 }}
                </span>
              </td>
              <td
                class="position-relative"
                v-for="(header, i) in headers"
                :key="i"
              >
                <slot
                  :name="'item.' + header.value"
                  :item="item"
                  :header="header"
                >
                  {{ fetchFromObject(item, header) }}
                </slot>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <footer v-if="totalPage > 1">
      <paginate
        :value="page"
        :page-count="totalPage"
        :page-range="3"
        :margin-pages="2"
        :click-handler="clickCallback"
        :prev-text="'‹‹'"
        :next-text="'››'"
        :container-class="'pagination'"
        :page-class="'page-item'"
      >
      </paginate>
    </footer>
  </div>
</template>

<script>
import { get } from "lodash";
export default {
  props: {
    loading: Boolean,
    items: { type: Array, default: () => [] },
    headers: { type: Array, default: () => [] },
    page: { type: [String, Number], default: 1 },
    totalPage: { type: [String, Number], default: 1 },
  },
  methods: {
    fetchFromObject(object, header) {
      return get(object, header.value);
    },
    clickCallback(page) {
      this.$emit("update:page", page);
    },
  },
};
</script>

<style></style>
