<template>
  <div class="color-table" v-show="legends.length > 0 && showLegend">
    <div v-for="item in legends.concat(diaGioi)" :key="item.id">
      <div class="color-name">
        <span v-bind:style="{ background: item.color }"></span>
      </div>
      <div class="color-value">
        <span> {{ item.name }}</span>
      </div>

      <input
        style="margin-top: 0; margin-left: auto"
        @change="handleChange($event, item.key || item.id)"
        :checked="item.show"
        type="checkbox"
      />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    legends: { type: Array, default: () => [] },
    diaGioi: { type: Array, default: () => [] },
    showLegend: Boolean,
  },
  methods: {
    handleChange(e, key) {
      this.$emit("layer-change", {
        id: key,
        value: e.target.checked,
      });
    },
  },
};
</script>
<style lang="scss">
.color-table {
  position: absolute;
  z-index: 3;
  bottom: 10px;
  left: 10px;
  width: 200px;
  background: white;
}
.color-table > div {
  display: flex;
  margin: 10px;
  align-items: center;
}
.color-name {
  padding-right: 10px;
  height: 20px;
}
.color-name span {
  display: inline-block;
  height: 100%;
  width: 40px;
}
</style>