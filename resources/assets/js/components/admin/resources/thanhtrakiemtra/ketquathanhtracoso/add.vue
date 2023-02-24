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
          track-by="id"
          label="so_quyet_dinh"
          :show-labels="false"
          :placeholder="'Chọn đoàn thanh tra'"
          v-model="doanThanhTraSelect"
          v-validate="'required'"
        />
      </div>
      <div class="col-sm-4">
        <label class="form-label">Cơ quan ký</label>
        <multiselect
          :options="danhmucs['co_quan']"
          :searchable="true"
          track-by="id"
          label="name"
          :show-labels="false"
          :placeholder="'Chọn cơ quan ký'"
          v-model="coQuanKySelect"
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
      <div class="col-sm-4" style="height: 100px">
        <label class="form-label">Loại cơ sở TT</label>
        <multiselect
          :options="loaiCSTT"
          v-model="coSoTypeSelect"
          :searchable="true"
          track-by="id"
          label="text"
          :show-labels="false"
          :placeholder="'Chọn loại cở sở thanh tra'"
        />
      </div>
      <div class="col-sm-4" style="height: 100px">
        <label class="form-label">Tên cơ sở TT</label>
        <multiselect
          :options="
            form.co_so_type
              ? danhmucs[
                  `${
                    form.co_so_type === 'App\\ProtectedArea'
                      ? 'vuon_quoc_gia'
                      : 'co_so_bao_ton'
                  }`
                ]
              : []
          "
          :searchable="true"
          track-by="id"
          :label="
            form.co_so_type === 'App\\ProtectedArea' ? 'orig_name' : 'ten'
          "
          :show-labels="false"
          :placeholder="'Chọn'"
          v-model="coSoSelect"
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
        <button class="btn" @click="createData">Lưu</button>
        <button class="btn" style="margin-left: 20px" @click="createData(true)">
          Lưu và thêm tiếp
        </button>
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
  props: ["danhmucs"],
  components: { Multiselect, FileUpload, ValidationProvider },
  data() {
    return {
      info,
      result,
      loaiCSTT: [
        {
          id: "App\\ProtectedArea",
          text: "Vườn quốc gia",
        },
        {
          id: "App\\CoSoBaoTon",
          text: "Cơ sở bảo tồn",
        },
      ],
      doanThanhTraSelect: null,
      coQuanKySelect: null,
      coSoTypeSelect: {
        id: "App\\ProtectedArea",
        text: "Vườn quốc gia",
      },
      coSoSelect: null,
      form: {},
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
    coSoTypeSelect(val) {
      this.form.co_so_type = val ? val.id : null;
      this.coSoSelect = null;
    },
    coSoSelect(val) {
      this.form.co_so_id = val ? val.id : null;
    },
    coQuanKySelect(val) {
      this.form.co_quan_ky_id = val ? val.id : null;
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
      this.form.co_so_type = "App\\ProtectedArea";
      this.form.co_so_id = null;
      this.form.co_quan_ky_id = null;
      this.form.doan_thanh_tra_id = null;

      this.coQuanKySelect = null;
      this.coSoSelect = null;
      this.formReady = true;
    },
    async createData(next = false) {
      const validate = await this.$validator.validateAll();
      console.log(validate);
      if (!validate) {
        this.typeNotification = 1;
        this.textNotification = "Bạn chưa chọn đoàn thanh tra";
        setTimeout(() => {
          this.typeNotification = null;
          this.textNotification = null;
        }, 1000);

        return;
      }
      try {
        this.loading = true;
        await axios.post(
          env.endpointhttp + "admin/ketquathanhtracoso",
          this.form
        );
        this.typeNotification = 2;
        this.textNotification = "Thêm mới thành công";
        if (next) {
          const doanThanhTra = this.danhmucs.doan_thanh_tra.find(
            (i) => i.id === this.form.doan_thanh_tra_id
          );
          this.getFormFields();
          this.doanThanhTraSelect = doanThanhTra;
          this.form.doan_thanh_tra_id = doanThanhTra.id;
        } else window.back();
      } catch (error) {
        this.typeNotification = 1;
        this.textNotification = "Thêm mới thất bại";
      } finally {
        this.loading = false;
        setTimeout(() => {
          this.typeNotification = null;
          this.textNotification = null;
        }, 1000);
      }
    },
  },
  created() {
    this.getFormFields();
  },
};
</script>

