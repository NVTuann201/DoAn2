<template>
  <div class="search-area">
    <h4>Tìm kiếm</h4>
    <input
      v-model="params.search"
      class="form-control"
      placeholder="Tìm kiếm theo tên"
      type="text"
    />
    <multiselect
      style="margin-top: 10px"
      v-model="quanHuyenSelect"
      :options="quan_huyen"
      :searchable="true"
      :multiple="true"
      track-by="id"
      label="name_vietnamese"
      placeholder="Chọn một quận huyện"
      :show-labels="false"
    >
    </multiselect>
    <div style="margin-top: 10px">
      <button class="btn btn-success btn-sm" @click="$emit('search', params)">
        Tìm kiếm
      </button>
      <button
        class="btn btn-success btn-sm"
        style="margin-left: 10px"
        @click="
          params.search = '';
          params.quan_huyen = [];
          quanHuyenSelect = [];
          $emit('search', params);
        "
      >
        Làm mới
      </button>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
export default {
  props: ["quan_huyen"],
  data() {
    return {
      quanHuyenSelect: [],
      params: {
        search: "",
        quan_huyen: [],
      },
    };
  },
  watch: {
    quanHuyenSelect(val) {
      this.params.quan_huyen = val.map((i) => i.id);
    },
  },
  components: { Multiselect },
};
</script>
<style lang="scss">
.search-area {
  position: absolute;
  z-index: 3;
  top: 10px;
  left: 10px;
  width: 300px;
  background: white;
  padding: 10px;
}
</style>