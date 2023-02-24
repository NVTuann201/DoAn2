<template>
  <filter-container :title="label">
    <template v-slot:defail-filter="{ open }">
      <div class="is-padded py-2" v-show="open">
        <h4 class="px-0 pt-0">Từ</h4>
        <VueSlider
          style="margin-left: 6px"
          :min="1970"
          :max="2021"
          :lazy="true"
          :value="value[0] || null"
          @change="
            value[0] = $event;
            $emit('input', [value[0], value[1]]);
          "
        />
        <h4 class="px-0">Đến</h4>
        <VueSlider
          style="margin-left: 6px"
          :min="1970"
          :max="2021"
          :lazy="true"
          :value="value[1] || null"
          @change="
            value[1] = $event;
            $emit('input', [value[0], value[1]]);
          "
        />
      </div>
      <div class="is-padded py-2" v-show="!open && (value[0] || value[1])">
        <p class="my-0">
          {{ value[0] }} - {{ value[1] }}
          <i
            @click="$emit('input', ['', ''])"
            style="float: right; cursor: pointer"
            class="fa fa-trash"
            aria-hidden="true"
          ></i>
        </p>
      </div>
    </template>
  </filter-container>
</template>

<script>
import FilterContainer from "@/modules/filter-group/filter-container.vue";
import VueSlider from "vue-slider-component";
import "vue-slider-component/theme/antd.css";
export default {
  components: { FilterContainer, VueSlider },
  props: {
    label: {
      type: String,
      required: true,
    },
    value: {
      type: Array,
      required: true,
      default: () => [null, null],
    },
  },
};
</script>
