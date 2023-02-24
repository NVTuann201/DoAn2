<template>
  <div v-if="formReady" class="container-fluid white-box">
    <div>
      <components-notifications
        :typeInput="typeNotification"
        :textInput="textNotification"
      ></components-notifications>
      <div class="loading-roles" v-if="loading">
        <components-loading-page></components-loading-page>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <h2>Thông tin kết quả thanh tra</h2>
      </div>
      <div class="col-sm-4">
        <label class="form-label">Đoàn thanh tra (*)</label>
        <multiselect
          :options="danhmucs['doan_thanh_tra']"
          :searchable="true"
          disabled
          track-by="id"
          label="so_quyet_dinh"
          :show-labels="false"
          :placeholder="'Chọn đoàn thanh tra'"
          v-model="doanThanhTraSelect"
          v-validate="'required'"
        />
      </div>

      <div class="col-sm-4" style="height: 100px">
        <label class="form-label">Tỉnh thành</label>
        <multiselect
          :options="danhmucs['tinh_thanh']"
          v-model="tinhThanhSelect"
          :searchable="true"
          track-by="id"
          label="name_vietnamese"
          :show-labels="false"
          :placeholder="'Chọn tỉnh thành'"
        />
      </div>
      <div
        style="height: 100px"
        class="col-sm-4"
        v-for="(item, idx) in info"
        :key="idx"
      >
        <label v-if="item.label" class="form-label">{{ item.label }}</label>
        <multiselect
          v-if="item.type === 'select'"
          :options="danhmucs[item.items]"
          :searchable="true"
          track-by="id"
          :label="item.display"
          :show-labels="false"
          :placeholder="'Chọn ' + item.label.toLowerCase()"
          v-model="doanThanhTraSelect"
        />

        <input
          v-else
          v-model="form[item.field]"
          :placeholder="'Nhập ' + item.label.toLowerCase()"
          :type="item.type"
          class="form-control"
        />
      </div>

      <div style="height: 100px" class="col-sm-4">
        <FileUpload :form="form" field="files" />
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <h2>Kết quả thanh tra</h2>
      </div>
      <div class="col-sm-12 ketquathanhtra">
        <tabs>
          <tab v-for="g in result" :name="g.group" :key="g.id">
            <div class="row" style="height: 500px">
              <div
                v-for="(item, idx) in g.children"
                :key="idx"
                class="col-sm-4"
                style="height: 80px"
              >
                <label v-if="item.label" class="form-label">{{
                  item.label
                }}</label>
                <template v-if="item.type === 'boolean'">
                  <div>
                    <label class="radio-inline">
                      <input
                        type="radio"
                        :value="1"
                        v-model="form[item.field]"
                        :name="item.field"
                      />
                      Có
                    </label>
                    <label class="radio-inline">
                      <input
                        type="radio"
                        :value="0"
                        v-model="form[item.field]"
                        :name="item.field"
                      />
                      Không
                    </label>
                  </div>
                </template>
                <FileUpload
                  v-else-if="item.type === 'file'"
                  :form="form"
                  field="files_xu_phat"
                />
                <input
                  v-else
                  :placeholder="'Nhập ' + item.label.toLowerCase()"
                  :type="item.type"
                  class="form-control"
                  v-model="form[item.field]"
                />
              </div>
            </div>
          </tab>
        </tabs>
      </div>
      <div class="col-sm-12" style="margin-top: 20px">
        <button class="btn" @click="updateData()">Cập nhật</button>
      </div>
    </div>
    <div class="row" style="margin-top: 20px">
      <div class="col-sm-12">
        <h2>Thành phần đoàn thanh tra</h2>
      </div>
      <div class="col-sm-3">
        <input
          type="text"
          v-model="thanhPhanDoan.ten"
          placeholder="Tên"
          class="form-control"
        />
      </div>
      <div class="col-sm-3">
        <input
          type="text"
          v-model="thanhPhanDoan.chuc_vu"
          placeholder="Chức vụ"
          class="form-control"
        />
      </div>
      <div class="col-sm-3">
        <input
          type="text"
          v-model="thanhPhanDoan.don_vi_cong_tac"
          placeholder="Đơn vị công tác"
          class="form-control"
        />
      </div>
      <div class="col-sm-3">
        <button class="btn btn-success" @click="themThanhPhanDoan">Thêm</button>
      </div>
      <div class="col-sm-12">
        <div class="table-responsive">
          <table
            class="
              table table-hover
              contact-list
              footable footable-1 footable-paging footable-paging-center
              breakpoint-lg
            "
          >
            <thead>
              <tr class="footable-header">
                <th style="width: 60px">STT</th>
                <th style="display: table-cell; min-width: 70px">Tên</th>
                <th style="display: table-cell">Chức vụ</th>
                <th style="display: table-cell; min-width: 70px">
                  Đơn vị công tác
                </th>

                <th width="100" style="text-align: center"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, idx) in form.thanh_phan_doan" :key="idx">
                <td>{{ idx + 1 }}</td>
                <td>{{ item.ten }}</td>
                <td>{{ item.chuc_vu }}</td>
                <td>{{ item.don_vi_cong_tac }}</td>
                <td>
                  <button
                    type="button"
                    title="Xóa"
                    @click="xoaThanhPhanDoan(idx)"
                    class="
                      btn btn-info btn-outline btn-circle btn-small
                      d-inline-block
                      mx-auto
                    "
                  >
                    <i class="ti-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { info, result } from "./fields";
import Multiselect from "vue-multiselect";
import FileUpload from "../../../components/FileUpload";
import * as env from "../../../../../env.js";
import { ValidationProvider } from "vee-validate";
export default {
  props: ["danhmucs", "ketquathanhtra"],
  components: { Multiselect, FileUpload, ValidationProvider },
  data() {
    return {
      info,
      result,

      doanThanhTraSelect: null,
      tinhThanhSelect: null,
      form: {
        thanh_phan_doan: [],
      },
      thanhPhanDoan: {
        ten: null,
        chu_vu: null,
        don_vi_cong_tac: null,
      },
      formReady: false,
      loading: false,
      typeNotification: null,
      textNotification: null,
    };
  },
  watch: {
    doanThanhTraSelect(val) {
      this.form.doan_thanh_tra_id = val.id;
    },
    tinhThanhSelect(val) {
      this.form.tinh_thanh_id = val.id;
    },
  },
  methods: {
    getFormFields() {
      const fields = this.info.concat(
        this.result.map((i) => i.children).flat()
      );
      fields.forEach((f) => {
        this.form[f.field] = null;
      });
      this.form.files = [];
      this.form.files_xu_phat = [];
      this.form.tinh_thanh_id = null;
      this.form.doan_thanh_tra_id = null;

      for (const field in this.form) {
        this.form[field] = this.ketquathanhtra[field];
      }
      this.form.thanh_phan_doan = [...this.ketquathanhtra.thanh_phan_doan];
      this.doanThanhTraSelect = this.ketquathanhtra.doan_thanh_tra;
      this.tinhThanhSelect = this.ketquathanhtra.tinh_thanh;

      this.formReady = true;
    },
    async updateData() {
      try {
        this.loading = true;
        await axios.put(
          env.endpointhttp +
            `admin/ketquathanhtratinh/${this.ketquathanhtra.id}`,
          this.form
        );
        this.typeNotification = 2;
        this.textNotification = "Cập nhật thành công";
      } catch (error) {
        console.log(error);
        this.typeNotification = 1;
        this.textNotification = "Cập nhật thất bại";
      } finally {
        this.loading = false;
        setTimeout(() => {
          this.typeNotification = null;
          this.textNotification = null;
        }, 1000);
      }
    },
    themThanhPhanDoan() {
      this.form.thanh_phan_doan.push(this.thanhPhanDoan);
      this.thanhPhanDoan = { ten: null, chu_vu: null, don_vi_cong_tac: null };
    },
    xoaThanhPhanDoan(idx) {
      this.form.thanh_phan_doan.splice(idx, 1);
    },
  },
  created() {
    this.getFormFields();
  },
};
</script>

