<template>
  <v-jstree
    :data="tree"
    :async="loadData"
    @item-click="itemClick"
    no-dots
    size="large"
  >
    <template v-slot:default="{ vm, model }">
      <div v-if="model.isLoadMoreBtn">
        <div class="tree-title load-more">...</div>
      </div>
      <div class="tree-title" v-else>
        <span style="cursor: pointer">
          {{ model.name_vietnamese || model.name || "Unknown" }}
        </span>
      </div>
    </template>
  </v-jstree>
</template>

<script>
import * as env from "../../env.js";
import VJstree from "vue-jstree";
export default {
  components: {
    VJstree,
  },
  data: () => ({
    tree: [
      {
        id: 0,
        name: "Tải...",
        value: "Loading...",
        opened: false,
        selected: false,
        disabled: true,
        loading: true,
        children: [],
      },
    ],
  }),
  created() {
    this.getData({ rank: "kingdom" }).then((res) => {
      this.tree = [
        {
          id: 0,
          name: "Mở rộng",
          value: "Explore",
          opened: true,
          selected: false,
          disabled: true,
          children: res,
        },
      ];
    });
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
    getData(filter) {
      return axios
        .get(env.endpointhttp + "api/search/taxon", { params: filter })
        .then((res) => {
          let data = res.data.result;
          let paginate = res.data.paginate;
          let dataReturn = data.map((x) => {
            Object.assign(x, {
              opened: false,
              disabled: false,
              selected: false,
            });
            x.children = !x.isLeaf
              ? [
                  {
                    id: x.id + new Date().getTime(),
                    name: "Tải...",
                    value: "Loading...",
                    icon: "",
                    opened: false,
                    selected: false,
                    disabled: true,
                    loading: true,
                    children: [],
                  },
                ]
              : [];
            return x;
          });
          if (dataReturn.length == 0) {
            dataReturn = [
              {
                name: "No data",
                disabled: true,
                isLeaf: true,
              },
            ];
          }
          if (paginate.hasMorePages) {
            dataReturn.push({
              id: 1,
              name: "...",
              value: "loadMore",
              isLoadMoreBtn: true,
              isLeaf: false,
              filter: Object.assign({}, filter, {
                page: parseInt(paginate.page) + 1,
              }),
              rank: "kingdom",
              children: [],
            });
          }
          return dataReturn;
        });
    },
    loadData(oriNode, resolve) {
      if (oriNode.data.opened) {
        let filter = oriNode.data.filter;
        this.getData(filter).then((res) => {
          resolve(res);
        });
      }
    },
    itemClick(node, item, e) {
      if (item.isLoadMoreBtn) {
        this.getData(item.filter).then((res) => {
          node.$parent.data.children.splice(-1, 1);
          node.$parent.data.children.push(...res);
        });
      } else {
        this.$emit("click:item", item);
      }
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
