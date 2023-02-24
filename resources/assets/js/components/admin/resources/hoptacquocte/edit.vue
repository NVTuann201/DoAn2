<template>
  <div class="white-box p-0">
    <div>
      <components-notifications
        :typeInput="typeNotification"
        :textInput="textNotification"
      ></components-notifications>
      <div class="loading-roles" v-if="loading">
        <components-loading-page></components-loading-page>
      </div>
    </div>
    <div style="padding: 25px">
      <div class="c-flex">
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label"
              >Tên văn bản / Dự án <span class="red-dot">*</span></label
            >
            <input
              v-model="form.ten"
              placeholder="Nhân tên văn bản, dự án, thỏa thuận"
              class="form-control"
              v-validate="'required'"
              name="ten"
            />
            <span
              v-show="errors.has('ten')"
              class="help is-danger"
              style="color: red"
              >Loại giấy phép không thể bỏ trống</span
            >
          </div>
          <div class="line-form">
            <label class="form-label"
              >Phân loại <span class="red-dot"></span
            ></label>
            <multiselect
              v-model="phanLoaiSelect"
              :options="danhmucs.phan_loai"
              :searchable="true"
              track-by="code"
              name="tinhChat"
              label="name"
              placeholder="Chọn một phân loại"
              :show-labels="false"
            >
            </multiselect>
          </div>
          <div class="line-form">
            <label class="form-label"
              >Ngày ban hành <span class="red-dot"></span
            ></label>
            <input
              v-model="form.ngay_ban_hanh"
              type="date"
              class="form-control"
            />
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label">Ngày hết hiệu lực</label>
            <input
              type="date"
              class="form-control"
              v-model="form.ngay_hieu_luc"
            />
          </div>
          <div class="line-form">
            <label class="form-label">Ngày hết hạn</label>
            <input
              type="date"
              v-model="form.ngay_het_han"
              class="form-control"
            />
          </div>
          <div class="line-form">
            <label class="form-label">Đối tác</label>
            <input
              v-model="form.doi_tac"
              placeholder="Tên đối tác nước ngoài"
              class="form-control"
            />
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label">Cơ quan chủ trì</label>
            <input
              v-model="form.co_quan_chu_tri"
              placeholder="Tên cơ quan chủ trì"
              class="form-control"
            />
          </div>
          <div class="line-form">
            <label class="form-label">Tình trạng</label>
            <br /><br />
            <input
              class="form-check-input"
              type="checkbox"
              id="inlineCheckbox1"
              v-model="form.hieu_luc"
            />
            <label class="form-check-label" for="inlineCheckbox1"
              >Cón hiệu lực</label
            >
          </div>
          <div class="c-flex line-form">
            <div class="d-flex">
              <label class="form-label" style="flex: 1">Tệp đính kèm </label>
              <div>
                <button
                  type="button"
                  class="btn btn-info btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#exampleModal"
                  data-bs-placement="top"
                  title="Tải lên"
                  @click="clickUpload"
                >
                  <i class="fas fa-thumbtack"></i> Tải tệp lên
                </button>
              </div>
              <input
                type="file"
                id="myfile"
                name="myfile"
                multiple
                ref="upload-files"
                style="display: none"
                @change="handleUpload"
              />
            </div>
            <br />
            <div>
              <div
                class="progress"
                style="height: 10px"
                v-show="
                  tienTrinhUpload &&
                  tienTrinhUpload != 0 &&
                  tienTrinhUpload != 100
                "
              >
                <div
                  class="progress-bar"
                  role="progressbar"
                  style="font-weight: bold"
                  aria-valuenow="25"
                  aria-valuemin="0"
                  aria-valuemax="100"
                  :style="{ width: tienTrinhUpload + 'px' }"
                >
                  {{ tienTrinhUpload }}
                </div>
              </div>
              <div class="c-flex">
                <div class="d-flex" v-for="item in fileList" :key="item.id">
                  <div
                    @click="taiTapTin(item)"
                    style="flex: 1; padding-bottom: 5px; cursor: pointer"
                    :title="item.name"
                  >
                    {{
                      item.name && item.name.length < 40
                        ? item.name
                        : "..." + item.name.substr(-39)
                    }}
                  </div>
                  <div>
                    <i
                      class="fas fa-trash-alt"
                      style="cursor: pointer"
                      @click="xoaTapTin(item.id)"
                    ></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form" style="width: 100%">
            <label class="form-label">Nội dung chính</label>
            <textarea
              rows="3"
              placeholder="Nhập nội dung"
              class="form-control"
              v-model="form.noi_dung_chinh"
            />
          </div>
        </div>
      </div>
      <div class="d-flex" style="justify-content: space-between">
        <div>
          <button
            type="button"
            class="btn btn-inverse"
            data-bs-toggle="modal"
            data-bs-target="#exampleModal"
            data-bs-placement="top"
            title="Trở lại"
            @click="back"
          >
            {{ $t("admin.buttons.cancel") }}
          </button>
        </div>
        <div>
          <button
            type="button"
            class="btn btn-info"
            data-bs-toggle="modal"
            data-bs-target="#exampleModal"
            data-bs-placement="top"
            title="Cập nhật"
            @click="update"
            :disabled="disableButton"
          >
            {{ $t("admin.buttons.edit") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ValidationProvider } from "vee-validate";
import * as env from "../../../../env.js";
import Multiselect from "vue-multiselect";
export default {
  components: { ValidationProvider, Multiselect },
  props: ["danhmucs", "data"],
  data: () => ({
    typeNotification: null,
    textNotification: null,
    loading: false,
    tinhChatSelect: null,
    phanLoaiSelect: null,
    tinhThanhSelect: null,
    danhNghiaSelect: null,
    phanCapSelect: null,
    tinhChats: [
      { name: "Đa phương", code: "da_phuong" },
      { name: "Song phương", code: "song_phuong" },
    ],
    tienTrinhUpload: null,
    fileList: [],
    disableButton: false,
    form: {
      ten: null,
      tinh_chat: null,
      ngay_ban_hanh: null,
      ngay_hieu_luc: null,
      ngay_het_han: null,
      doi_tac: null,
      co_quan_chu_tri: null,
      phan_cap_id: null,
      danh_nghia_id: null,
      nguoi_ky: null,
      hieu_luc: true,
      noi_dung_chinh: null,
      tinh_thanh_id: null,
      noi_dung_toan_van: null,
      ghi_chu: null,
      files: [],
    },
  }),
  mounted() {
    for (let item in this.form) {
      this.form[item] = this.data[item];
    }
    this.form.id = this.data.id;
    this.tinhChatSelect = this.tinhChats.find(
      (el) => el.code == this.data.tinh_chat
    );
    if (this.data.files) {
      this.form.files = [...JSON.parse(this.data.files)];
    }
    this.tinhThanhSelect = this.danhmucs.tinh_thanh.find(
      (el) => el.id == this.data.tinh_thanh_id
    );
    this.danhNghiaSelect = Object.assign({}, this.data.danh_nghia);
    this.phanCapSelect = Object.assign({}, this.data.phan_cap);
    this.phanLoaiSelect = Object.assign({}, this.data.phan_loai);
    this.fileList = [...this.data.fileList];
  },
  methods: {
    back() {
      window.location.href = '/admin/hoptacquocte'
    },
    resetForm() {
      this.tinhChatSelect = null;
      this.tinhThanhSelect = null;
      this.danhNghiaSelect = null;
      this.phanCapSelect = null;
      this.phanLoaiSelect = null;
      this.fileList = [];
      this.form = {
        ten: null,
        tinh_chat: null,
        ngay_ban_hanh: null,
        ngay_hieu_luc: null,
        ngay_het_han: null,
        doi_tac: null,
        phan_cap_id: null,
        co_quan_chu_tri: null,
        phan_cap_id: null,
        danh_nghia_id: null,
        nguoi_ky: null,
        hieu_luc: true,
        noi_dung_chinh: null,
        tinh_thanh_id: null,
        noi_dung_toan_van: null,
        ghi_chu: null,
        files: [],
      };
    },
    update() {
      this.$validator.validateAll().then((validate) => {
        this.form.tinh_chat = this.tinhChatSelect
          ? this.tinhChatSelect.code
          : null;
        this.form.tinh_thanh_id = this.tinhThanhSelect
          ? this.tinhThanhSelect.id
          : null;
        this.form.phan_cap_id = this.phanCapSelect
          ? this.phanCapSelect.id
          : null;
        this.form.danh_nghia_id = this.danhNghiaSelect
          ? this.danhNghiaSelect.id
          : null;
        this.form.phan_loai_id = this.phanLoaiSelect
          ? this.phanLoaiSelect.id
          : null;
        if (validate) {
          axios
            .put(env.endpointhttp + "admin/hoptacquocte/edit", this.form)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Cập nhật thành công";
                window.location = '/admin/hoptacquocte'
              }
              else{
                this.typeNotification = 1;
                this.textNotification = response.data.message;
              }
            })
            .catch(() => {
              this.typeNotification = 1;
              this.textNotification = "Cập nhật thất bại";
            })
            .finally(() => {
              this.loading = false;
              this.showForm = false;
              this.typeNotification = null;
              this.textNotification = null;
              this.disableButton = false;
            });
        }
      });
    },
    clickUpload() {
      this.$refs["upload-files"].click();
    },
    async handleUpload(e) {
      var isValidate = true;
      let files = e.target.files;
      let data = new FormData();
      if (!files || files.length == 0) {
        return;
      }
      for (let el of files) {
        const isLt2M = el.size / 1024 / 1024 < 50;
        if (!isLt2M) {
          this.typeNotification = 1;
          this.textNotification = "File phải nhỏ hơn 50Mb";
          isValidate = false;
          this.files = [];
          break;
        }
        data.append("file[]", el);
      }

      if (!isValidate) return;

      try {
        this.tienTrinhUpload = 1;
        let File_id = await axios.post("/admin/uploadfiles", data, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
          onUploadProgress: (progressEvent) => {
            var percentCompleted = Math.round(
              (progressEvent.loaded * 100) / progressEvent.total
            );
            this.tienTrinhUpload = percentCompleted;
          },
        });
        this.fileList = [...this.fileList, ...File_id.data.files];
        this.form.files = this.fileList.map((el) => el.id);
      } catch (error) {
        console.log(error);
      }
      this.$refs["upload-files"].value = null;
    },
    xoaTapTin(id) {
      this.fileList = this.fileList.filter((el) => el.id != id)
        ? this.fileList.filter((el) => el.id != id)
        : [];
      this.form.files = this.fileList.map((el) => el.id)
        ? this.fileList.map((el) => el.id)
        : [];
    },
    taiTapTin(data) {
      window.open("/" + data.disk, "_blank");
    },
  },
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