<template>
  <input v-model="form" class="form-control" v-bind="$attrs" />
</template>

<script>
export default {
  props: {
    value: { type: [String, Number], default: "" },
  },
  computed: {
    form: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value || "");
      },
    },
  },
};
</script>

<style></style>
