<template>
  <span>{{ label }}</span>
</template>

<script>
export default {
  props: {
    value: {},
    enText: { type: String, default: "name" },
    vnText: { type: String, default: "name_vietnamese" },
    fallback: { type: String, default: "name" },
  },
  data: () => ({ label: "" }),
  watch: {
    "$i18n.locale": {
      handler(value) {
        this.label =
          this.value[value == "vn" ? this.vnText : this.enText] ||
          this.value[this.fallback];
      },
      immediate: true,
    },
  },
};
</script>

<style>
</style>
