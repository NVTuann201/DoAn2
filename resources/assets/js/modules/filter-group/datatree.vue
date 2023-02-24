<template>
  <v-jstree :data="tree" @item-click="itemClick" no-dots size="large">
    <template v-slot:default="{ vm, model }">
      <div class="tree-title">
        <span style="cursor: pointer">{{
          model.name ? model.name : "Unknown"
        }}</span>
      </div>
    </template>
  </v-jstree>
</template>

<script>
import VJstree from "vue-jstree";
export default {
  components: {
    VJstree,
  },
  props: ["data"],
  data: () => ({
    tree: [
    ],
  }),
  created() {
   this.tree =  this.data ? this.data : []
  },
  methods: {
    open(array) {
      let data = this.tree[0].children;
      array.forEach((item) => {
        if (data && data.length) {
          let item = data.find((x) => x.id == item.id);

          if (item) {
            data = item.children;
          }
        }
      });
    },
    itemClick(node, item, e) {
      this.$emit("click:item", item);
    },
  },
};
</script>

<style scoped>
/deep/ .tree-default .tree-node {
  margin-left: 20px;
}
/deep/ .tree-default-large .tree-node {
  margin-left: 20px;
}
/deep/ .tree-title {
  background: #f7f7f7;
  white-space: nowrap;
  display: inline-block;
  padding: 0 8px;
  height: 24px;
  line-height: 24px;
  position: relative;
}
</style>
