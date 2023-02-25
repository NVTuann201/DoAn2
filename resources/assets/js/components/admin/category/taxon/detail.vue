<template>
  <div class="white-box p-0">
    <div>
      <components-notifications
        :typeInput="typeNotification"
        :textInput="textNotification"
      ></components-notifications>
      <div v-if="loading">
        <components-loading-page></components-loading-page>
      </div>
    </div>
    <form @submit.prevent="onSubmit()">
      <div class="p-20">
        <b-card no-body>
          <b-card-header class="p-1" role="tab">
            <b-button v-b-toggle.accordion-1 variant="info">
              <b>I. Đặc tính loài</b>
            </b-button>
          </b-card-header>
          <b-collapse visible id="accordion-1">
            <DynamicForm
              v-model="form"
              :fields="fields_DacTinhLoai"
              class="p-t-20"
              ref="form1"
            />
          </b-collapse>
        </b-card>
        <br />
        <b-card no-body>
          <b-card-header class="p-1" role="tab">
            <b-button v-b-toggle.accordion-2 variant="info">
              <b>2. Thông tin phân loại học</b>
            </b-button>
          </b-card-header>
          <b-collapse visible id="accordion-2">
            <PhanLoaiLoai
              v-model="form"
              class="p-t-20"
              :danhmucs="danhmucs"
              ref="form2"
            />
          </b-collapse>
        </b-card>

        <br />
        <b-card no-body>
          <b-card-header class="p-1" role="tab">
            <b-button v-b-toggle.accordion-3 variant="info">
              <b>3. Thông tin bổ sung về phân loại học</b>
            </b-button>
          </b-card-header>
          <b-collapse visible id="accordion-3">
            <ThongTinBoXungPhanLoaiLoai
              v-model="form.taxon_extension"
              class="p-t-20"
              ref="form3"
            />
          </b-collapse>
        </b-card>
        <br />
        <b-card no-body>
          <b-card-header class="p-1" role="tab">
            <b-button v-b-toggle.accordion-5 variant="info">
              <b>4. Phát hiện loại</b>
            </b-button>
          </b-card-header>
          <b-collapse visible id="accordion-5">
            <DynamicForm
              v-model="form.darwin_core_occurrences"
              :fields="fields_PhatHienLoai"
              class="p-t-20"
              ref="form4"
            />
          </b-collapse>
        </b-card>
        <br />
        <b-card no-body>
          <b-card-header class="p-1" role="tab">
            <b-button v-b-toggle.accordion-6 variant="info">
              <b>5. Thông tin về sự xuất hiện</b>
            </b-button>
          </b-card-header>
          <b-collapse visible id="accordion-6">
            <DynamicForm
              v-model="form.darwin_core_occurrences"
              :fields="fields_ThongTinSuXuatHien"
              class="p-t-20"
              ref="form5"
            />
          </b-collapse>
        </b-card>
        <br />
        <b-card no-body>
          <b-card-header class="p-1" role="tab">
            <b-button v-b-toggle.accordion-7 variant="info">
              <b>6. Thông tin sự kiện</b>
            </b-button>
          </b-card-header>
          <b-collapse visible id="accordion-7">
            <DynamicForm
              v-model="form.darwin_core_occurrences"
              :fields="fields_ThongTinSuKien"
              class="p-t-20"
              ref="form6"
            />
          </b-collapse>
        </b-card>
        <br />
        <!-- <b-card no-body>
          <b-card-header class="p-1" role="tab">
            <b-button v-b-toggle.accordion-8 variant="info">
              <b>7. Thông tin địa điểm</b>
            </b-button>
          </b-card-header>
          <b-collapse visible id="accordion-8">
            <DynamicForm
              v-model="form.darwin_core_occurrences"
              :fields="fields_ThongTinDiaDiem"
              class="p-t-20"
              ref="form7"
            />
          </b-collapse>
        </b-card>
        <br />
        <b-card no-body>
          <b-card-header class="p-1" role="tab">
            <b-button v-b-toggle.accordion-9 variant="info">
              <b>8. Bối cảnh địa chất</b>
            </b-button>
          </b-card-header>
          <b-collapse visible id="accordion-9">
            <DynamicForm
              v-model="form.darwin_core_occurrences"
              :fields="fields_BoiCanhDiaChat"
              class="p-t-20"
              ref="form8"
            />
          </b-collapse>
        </b-card>
        <br />
        <b-card no-body>
          <b-card-header class="p-1" role="tab">
            <b-button v-b-toggle.accordion-10 variant="info">
              <b>9. Định danh loài</b>
            </b-button>
          </b-card-header>
          <b-collapse visible id="accordion-10">
            <DynamicForm
              v-model="form.darwin_core_occurrences"
              :fields="fields_DinhDanhLoai"
              class="p-t-20"
              ref="form9"
            />
          </b-collapse>
        </b-card>
        <br /> -->
        <b-card no-body>
          <b-card-header class="p-1" role="tab">
            <b-button v-b-toggle.accordion-4 variant="info">
              <b>7. Hình ảnh loài</b>
            </b-button>
          </b-card-header>
          <b-collapse visible id="accordion-4">
            <b-card-body>
              <TaxonUpload class="p-t-20" v-model="documents" v-if="isCreate" />
              <ImageUpload
                v-else
                :subject_id="id"
                :subject_type="subject_type"
              />
            </b-card-body>
          </b-collapse>
        </b-card>

        <div
          class="d-flex"
          style="justify-content: space-between; padding-top: 50px"
        >
          <div>
            <button
              type="button"
              class="btn btn-inverse"
              title="Trở lại"
              @click="back"
            >
              <i class="fas fa-arrow-left"></i>
              Trở lại
            </button>
          </div>
          <div>
            <button class="btn btn-info" type="submit">
              {{
                isCreate ? $t("admin.buttons.add") : $t("admin.buttons.edit")
              }}
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import PhanLoaiLoai from "./detail/phanloailoai.vue";
import ThongTinBoXungPhanLoaiLoai from "./detail/thongtinboxungphanloailoai.vue";
import TaxonUpload from "./file/taxonUpload.vue";
import DynamicForm from "@/modules/dynamic-form/dynamic-form.vue";
import ImageUpload from "@/modules/file/upload/image-upload.vue";
import { taxon_url_post } from "@/routes";
import {
  fields_PhatHienLoai,
  fields_ThongTinSuXuatHien,
  fields_ThongTinSuKien,
  fields_ThongTinDiaDiem,
  fields_BoiCanhDiaChat,
  fields_DacTinhLoai,
  fields_DinhDanhLoai,
} from "./field.js";
import { JsonToFormData } from "@/utils/json-to-form-data";
export default {
  components: {
    Multiselect,
    PhanLoaiLoai,
    ThongTinBoXungPhanLoaiLoai,
    DynamicForm,
    TaxonUpload,
    ImageUpload,
  },
  props: {
    type: { type: String, default: "create" },
    danhmucs: { type: Object, default: () => ({}) },
    id: { type: [String, Number] },
    subject_type: { type: String, default: "App\DarwinCoreTaxon" },
    value: { type: Object, default: () => ({}) },
  },
  data: () => ({
    typeNotification: null,
    textNotification: null,
    loading: false,
    form: {},
    fields_PhatHienLoai,
    fields_ThongTinSuXuatHien,
    fields_ThongTinSuKien,
    fields_ThongTinDiaDiem,
    fields_BoiCanhDiaChat,
    fields_DacTinhLoai,
    fields_DinhDanhLoai,
    documents: [],
  }),
  computed: {
    isCreate() {
      return this.type == "create";
    },
  },
  created() {
    this.form = Object.assign(
      {
        darwin_core_occurrences: {},
        taxon_extension: {},
        darwin_core_simple_images: [],
      },
      this.value
    );
    this.form.darwin_core_occurrences = this.form.darwin_core_occurrences
      ? this.form.darwin_core_occurrences
      : {};
    this.form.taxon_extension = this.form.taxon_extension || {};
    this.form.darwin_core_simple_images =
      this.form.darwin_core_simple_images || {};
  },
  methods: {
    back() {
      window.history.back();
    },
    resetForm() {
      this.form = {};
    },
    validateAll() {
      return Promise.all([
        this.$refs.form1.validateAll(),
        this.$refs.form2.validateAll(),
        this.$refs.form3.validateAll(),
        this.$refs.form4.validateAll(),
        this.$refs.form5.validateAll(),
        this.$refs.form6.validateAll(),
        this.$refs.form7.validateAll(),
        this.$refs.form8.validateAll(),
        this.$refs.form9.validateAll(),
      ]).then((res) => res.every((e) => !!e));
    },

    async onSubmit() {
      let validate = await this.validateAll();
      if (!validate) {
        this.typeNotification = 1;
        this.textNotification = "Dữ liệu không thỏa mãn";

        return;
      }
      try {
        this.loading = true;
        let data = Object.assign({}, this.form);
        [
          "dataset_resource",
          "kingdom",
          "phylum",
          "class",
          "order",
          "family",
          "genus",
          "species",
          "subspecies",
        ].forEach((key) => {
          delete data[key];
        });
        data["files"] = [];
        data["files_new_info"] = [];
        this.documents.forEach((document) => {
          if (document.file && document.file instanceof File) {
            data["files"].push(document.file);
            data["files_new_info"].push(JSON.stringify(document.properties));
          }
        });
        delete data["darwin_core_simple_images"];
        data = JsonToFormData(data);
        if (this.isCreate) {
          await axios.post(taxon_url_post, data);
        } else {
          await axios.post(taxon_url_post + "/" + this.id, data);
        }
        this.typeNotification = 2;
        if (this.isCreate) {
          this.textNotification = "Thêm mới thành công";
        } else {
          this.textNotification = "Cập nhật thành công";
        }
        window.location.reload();
      } catch (error) {
        console.error(error);
        this.typeNotification = 1;
        if (this.isCreate) {
          this.textNotification = "Thêm mới không thành công";
        } else {
          this.textNotification = "Cập nhật không thành công";
        }
      } finally {
        this.loading = false;
      }
    },
  },
  mounted() {},
};
</script>

<style scoped>
.line-form {
  width: 30%;
}
.container-line {
  justify-content: space-between;
  margin-bottom: 20px;
}
</style>
