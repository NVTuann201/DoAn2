<template>
  <div class="form-check-group">
    <div class="form-check" v-for="item in items" :key="item[itemValue]">
      <input
        class="form-check-input"
        type="radio"
        :name="idInput"
        :id="idInput + item[itemValue]"
        :value="item[itemValue]"
        :checked="item[itemValue] == value"
      />
      <label class="form-check-label" :for="idInput + item[itemValue]">
        {{ item[itemText] }}
      </label>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    value: {},
    items: { type: Array, default: () => [] },
    itemValue: { type: String, default: "value" },
    itemText: { type: String, default: "text" },
  },
  created() {
    this.idInput = "input-checkbox-" + Math.random() + "-";
  },
  data: () => ({ idInput: "" }),
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

<style scoped>
.form-check-group {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}
.form-check {
  padding-left: 5px; /* this */
  padding-top: 5px; /* this */
}
</style>
