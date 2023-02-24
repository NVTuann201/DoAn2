<template>
  <div class="col-sm-12 col-md-6">
    <div class="form-group col-md-12">
      <label class="form-label">
        {{ $t("admin.label.role") }} <span class="red-dot">*</span>
      </label>
      <div>
        <multiselect
          v-model="model.role"
          :options="danhmucs.roles"
          :searchable="false"
          track-by="id"
          label="name"
          v-validate="'required'"
          name="role"
          :show-labels="false"
          :placeholder="$t('admin.user.input_role')"
        ></multiselect>
      </div>
      <p v-if="!model.role && this.submit"  class="help-block is-danger">
        ( Phải chọn vai trò người dùng )
      </p>
    </div>
    <div
      class="form-group col-md-12"
      v-if="model.role && ['provincial-level'].includes(model.role.code)"
    >
      <label class="form-label">
        Tỉnh thành quản lý
        <span class="red-dot">*</span>
      </label>
      <div>
        <multiselect
          v-model="model.provinces"
          :options="danhmucs.provinces"
          :searchable="false"
          track-by="type"
          label="name"
          v-validate="'required'"
          name="provinces"
          :show-labels="false"
          multiple
          placeholder="Mời bạn chọn tỉnh thành"
        ></multiselect>
      </div>
      <p class="help-block help is-danger" v-show="errors.has('provinces')">
        Vai trò này yêu cầu cần chọn tỉnh thành quản lý
      </p>
    </div>
    <template v-if="model.role && ['local-level'].includes(model.role.code)">
      <div class="form-group col-md-12">
        <label class="form-label">
          Loại cơ sở quản lý
          <span class="red-dot">*</span>
        </label>
        <select
          class="form-control"
          v-model="model.coso_type"
          v-validate="'required'"
          name="role_local_types"
        >
          <option v-for="item in types" :key="item.type" :value="item.type">
            {{ item.name }}
          </option>
        </select>
        <p
          class="help-block help is-danger"
          v-show="errors.has('role_local_types')"
        >
          Vai trò này yêu cầu cần chọn loại cơ sở quản lý
        </p>
      </div>
      <div class="form-group col-md-12" v-if="model.coso_type">
        <label class="form-label">
          Cơ sở quản lý
          <span class="red-dot">*</span>
        </label>
        <div>
          <multiselect
            v-model="model.coso"
            :options="coso.items"
            :searchable="true"
            track-by="id"
            :show-labels="false"
            label="name"
            name="role_local"
            :placeholder="`Mời bạn chọn cơ sở`"
            :loading="coso.loading"
            @search-change="onCosoSearchChange"
          >
          </multiselect>
        </div>
        <p class="help-block help is-danger" v-show="errors.has('role_local')">
          Vai trò này yêu cầu cần chọn cơ sở quản lý
        </p>
      </div>
    </template>
  </div>
</template>

<script>
import { debounce } from "lodash";
import Multiselect from "vue-multiselect";
import { coso_role_get } from "@/routes";
export default {
  components: { Multiselect },
  props: { danhmucs: {}, value: {}, submit: {} },
  computed: {
    model: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      },
    },
  },
  data: () => ({
    coso: { items: [], loading: false },
    types: [
      { name: "Khu bảo tồn", type: "khu-bao-ton" },
      { name: "Cơ sở bảo tồn", type: "co-so-bao-ton" },
      {
        name: "Hành lang đa dạng sinh học",
        type: "hanh-lang-da-dang-sinh-hoc",
      },
      { name: "Đa dạng sinh học cao", type: "da-dang-sinh-hoc-cao" },
      {
        name: "Vùng ngập nươc quan trọng",
        type: "vung-ngap-nuoc-quan-trong",
      },
      { name: "Vùng chim quan trọng", type: "vung-chim-quan-trong" },
      { name: "Cảnh quan sinh thái", type: "canh-quan-sinh-thai" },
      { name: "Khu dự trữ sinh quyển", type: "khu-du-tru-sinh-quyen" },
    ],
  }),
  mounted() {console.log(1,this.submit)},
  watch: {
    "value.coso_type": {
      handler(value, old) {
        if (value && old && value != old) {
          this.coso.items = [];
          this.model.coso = null;
          this.onCosoSearchChange();
        }
      },
    },
  },
  mounted() {},
  methods: {
    onCosoSearchChange: debounce(function (e) {
      if (!this.model.coso_type) return;
      this.coso.loading = true;
      axios
        .get(coso_role_get, {
          params: { search: e, type: this.model.coso_type },
        })
        .then((res) => {
          this.coso.items = res.data.data;
        })
        .finally(() => {
          this.coso.loading = false;
        });
    }, 200),
  },
};
</script>

<style></style>
