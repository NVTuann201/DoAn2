<template>
  <input
    v-model="form"
    class="form-control"
    type="date"
    v-bind="$attrs"
    @input="onChange"
  />
</template>

<script>
export default {
  props: {
    value: { type: [String, Number], default: "" },
  },
  computed: {},
  data: () => ({ form: "" }),
  watch: {
    value: {
      handler(value) {
        if (value && value.length > 10) {
          value = value.split(" ")[0];
        }
        this.form = value;
      },
      immediate: true,
    },
  },
  methods: {
    onChange() {
      this.$emit("input", this.form);
    },
  },
};
</script>

<style></style>
