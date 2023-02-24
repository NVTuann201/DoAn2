<template>
  <DynamicRadio v-model="form" :items="items" />
</template>

<script>
import DynamicRadio from "./dynamic-radio.vue";
export default {
  components: { DynamicRadio },
  props: {
    value: { type: [Boolean, Number], default: false },
  },
  computed: {
    items() {
      return [
        { value: "false", text: "Không" },
        { value: "true", text: "Có" },
      ];
    },
    form: {
      get() {
        return !!this.value ? "true" : "false";
      },
      set(value) {
        this.$emit("input", value == "true" ? true : false);
      },
    },
  },
};
</script>

<style></style>
