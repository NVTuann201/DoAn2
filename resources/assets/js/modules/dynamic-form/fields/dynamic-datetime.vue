<template>
  <input
    v-model="form"
    class="form-control"
    type="datetime-local"
    pattern="[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}"
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
        let values = value.split(" ");
        this.form = `${values[0]}T${values[1]}`;
      },
      immediate: true,
    },
  },
  methods: {
    onChange() {
      let values = this.form.split("T");
      this.$emit("input", `${values[0]} ${values[1]}`);
    },
  },
};
</script>

<style></style>
