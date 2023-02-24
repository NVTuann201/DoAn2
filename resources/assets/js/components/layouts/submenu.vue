<template>
  <div
    class="boxcontainer"
    v-if="subMenus && subMenus.length"
    :style="{ top, left }"
    @mouseleave="hideSubMenu"
  >
    <a
      class="item"
      v-for="item in subMenus"
      :key="item.name || item.ten"
      :href="link + item.id"
      >{{ item.name || item.ten }}</a
    >
  </div>
</template>

<script>
export default {
  props: {
    top: { type: String, default: "50px" },
    left: { type: String, default: "0px" },
    link: { type: String, default: "#" },
    dataList: { type: Array, default: null },
  },
  computed: {
    subMenus: {
      get() {
        return this.dataList;
      },
      set(value) {
        this.$emit("update:dataList", value);
      },
    },
  },
  methods: {
    hideSubMenu() {
      this.subMenus = [];
    },
  },
};
</script>

<style scoped>
.boxcontainer {
  width: 196px;
  position: absolute;
  color: #4e565f;
  background: white;
  z-index: 100;
}
.item {
  min-height: 40px;
  display: flex;
  align-items: center;
  padding: 10px;
  cursor: pointer;
}
.item:hover {
  background: #e5e7e9;
}
</style>