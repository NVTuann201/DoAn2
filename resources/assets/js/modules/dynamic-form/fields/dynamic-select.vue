<template>
  <select class="form-control" v-model="form">
    <option
      v-for="item in items"
      :key="item[itemValue]"
      :value="item[itemValue]"
    >
      {{ item[itemText] }}
    </option>
  </select>
</template>

<script>
export default {
  props: {
    value: {},
    items: { type: Array, default: () => [] },
    itemValue: { type: String, default: "value" },
    itemText: { type: String, default: "text" },
  },
  computed: {
    form: {
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

<style></style>
