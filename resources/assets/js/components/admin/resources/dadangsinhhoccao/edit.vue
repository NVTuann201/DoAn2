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
              >Tên khu vực<span class="red-dot">*</span></label
            >
            <input
              v-model="form.ten"
              class="form-control"
              placeholder="Tên khu vực"
              v-validate="'required'"
              name="ten"
            />
            <span
              v-show="errors.has('ten')"
              class="help is-danger"
              style="color: red"
              >Tên khu vực không thể bỏ trống</span
            >
          </div>
          <div class="line-form" style="width: 65%">
            <label class="form-label">Mô tả<span class="red-dot"></span></label>
            <input
              v-model="form.mo_ta"
              class="form-control"
              placeholder="Mô tả vị trí địa lý"
            />
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label"
              >Diện tích (ha)<span class="red-dot"></span
            ></label>
            <input
              v-model="form.dien_tich"
              class="form-control"
              type="number"
              placeholder="Diện tích (ha)"
              name="ten"
            />
          </div>
          <div class="line-form">
            <label class="form-label"
              >Tình trạng<span class="red-dot"></span
            ></label>
            <multiselect
              v-model="tinhTrangSelect"
              :options="danhmucs.tinh_trang"
              :searchable="true"
              track-by="id"
              label="name"
              placeholder="Tình trạng"
              :show-labels="false"
            />
          </div>

          <div class="line-form">
            <label class="form-label"
              >Căn cứ đề xuất<span class="red-dot"></span
            ></label>
            <input
              v-model="form.can_cu_de_xuat"
              class="form-control"
              placeholder="Căn cứ đề xuất"
            />
          </div>
        </div>

        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label">Quyết định thành lập</label>
            <input
              class="form-control"
              v-model="form.quyet_dinh_thanh_lap"
              placeholder="Quyết định thành lập"
            />
          </div>
          <div class="line-form">
            <label class="form-label">Ngày thành lập</label>
            <input
              v-model="form.ngay_thanh_lap"
              type="date"
              class="form-control"
            />
          </div>
          <div class="line-form">
            <!-- <label class="form-label">Cơ quan ban hành</label>
            <input v-model="form.co_quan_ban_hanh" class="form-control" /> -->
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label"
              >Cơ quan ban hành<span class="red-dot"></span
            ></label>
            <input
              v-model="form.co_quan_ban_hanh"
              class="form-control"
              placeholder="Cơ quan ban hành"
            />
          </div>
          <div class="line-form">
            <label class="form-label"
              >Cơ quan quản lý<span class="red-dot"></span
            ></label>
            <input
              v-model="form.co_quan_quan_ly"
              class="form-control"
              placeholder="Cơ quan quản lý"
            />
          </div>
          <div class="line-form">
            <label class="form-label"
              >Quốc tế công nhận<span class="red-dot"></span
            ></label>
            <br />
            <div>
              <input v-model="form.quoc_te_cong_nhan" type="checkbox" />
              Được quốc tế công nhận
            </div>
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label">Danh hiệu quốc tế</label>
            <input
              class="form-control"
              v-model="form.danh_hieu_quoc_te"
              placeholder="Danh hiệu quốc tế"
            />
          </div>
          <div class="line-form">
            <label class="form-label">Mức độ đang dạng sinh học</label>
            <input v-model="form.muc_do" class="form-control" />
          </div>
          <div class="line-form">
            <label class="form-label">Đề xuất quản lý</label>
            <input v-model="form.de_xuat_quan_ly" class="form-control" />
          </div>
        </div>

        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label">Tỉnh thành</label>
            <multiselect
              v-model="tinhThanhSelect"
              :options="danhmucs.tinh_thanh"
              :searchable="true"
              :multiple="true"
              track-by="id"
              label="name_vietnamese"
              placeholder="Địa điểm"
              :show-labels="false"
            />
          </div>

          <div class="line-form" style="width: 65%">
            <label class="form-label">Ghi chú</label>
            <input v-model="form.ghi_chu" class="form-control" />
          </div>
        </div>

        <!-- 
        <div class="c-flex" style="padding-bottom: 30px">
          <div class="d-flex" style="padding-bottom: 20px">
            <label>Không gian</label>
            <div class="d-flex" style="padding-left: 50px">
              <button
                type="button"
                class="btn btn-info btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                data-bs-placement="top"
                title="Tải lên"
                @click="clickUpload"
              >
                <i class="fas fa-upload"></i> Tải tệp lên
              </button>
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
          </div>
          <Map />
        </div> -->

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
              <i class="fas fa-arrow-left"></i>
              Trở lại
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
              @click="add"
              :disabled="disableButton"
            >
              <i class="fas fa-check"></i> Cập nhật
            </button>
          </div>
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
    tienTrinhUpload: null,
    fileList: [],
    disableButton: false,
    khuBaoTonSelect: null,
    tinhTrangSelect: null,
    phanCapSelect: null,
    tinhThanhSelect: null,
    form: {
      ten: null,
      mo_ta: null,
      dien_tich: null,
      tinh_trang_id: null,
      can_cu_de_xuat: null,
      quyet_dinh_thanh_lap: null,
      ngay_thanh_lap: null,
      co_quan_ban_hanh: null,
      co_quan_quan_ly: null,
      quoc_te_cong_nhan: true,
      danh_hieu_quoc_te: null,
      muc_do: null,
      de_xuat_quan_ly: null,
      tinh_thanh_id: [],
      ghi_chu: null,
    },
  }),
  mounted() {
    this.getData();
  },
  methods: {
    back() {
      window.history.back();
    },
    getData() {
      for (let item in this.form) {
        this.form[item] = this.data[item];
      }
      this.form.id = this.data.id;
      this.tinhTrangSelect = Object.assign({}, this.data.tinh_trang);
      let tinhThanhId = this.data.tinh_thanh_id
        ? JSON.parse(this.data.tinh_thanh_id)
        : [];
      this.tinhThanhSelect = this.danhmucs.tinh_thanh.filter((el) =>
        tinhThanhId.includes(el.id)
      );
    },
    resetForm() {
      this.tinhTrangSelect = null;
      this.tinhThanhSelect = null;
      this.form = {
        ten: null,
        mo_ta: null,
        dien_tich: null,
        tinh_trang_id: null,
        can_cu_de_xuat: null,
        quyet_dinh_thanh_lap: null,
        ngay_thanh_lap: null,
        co_quan_ban_hanh: null,
        co_quan_quan_ly: null,
        quoc_te_cong_nhan: true,
        danh_hieu_quoc_te: null,
        muc_do: null,
        de_xuat_quan_ly: null,
        tinh_thanh_id: [],
        ghi_chu: null,
      };
    },

    add() {
      this.$validator.validateAll().then((validate) => {
        this.form.tinh_trang_id = this.tinhTrangSelect
          ? this.tinhTrangSelect.id
          : null;
        this.form.tinh_thanh_id = [];
        if (this.tinhThanhSelect && this.tinhThanhSelect.length) {
          this.tinhThanhSelect.forEach((el) => {
            this.form.tinh_thanh_id.push(el.id);
          });
        }
        if (validate) {
          this.disableButton = true;
          axios
            .post(
              env.endpointhttp + "admin/khuvucdadangsinhhoccao/edit",
              this.form
            )
            .then(() => {
              this.typeNotification = 2;
              this.textNotification = "Cập nhật thành công";
            })
            .catch(() => {
              this.typeNotification = 1;
              this.textNotification = "Cập nhật thất bại";
            })
            .finally(() => {
              this.disableButton = false;
              this.loading = false;
              this.typeNotification = null;
              this.textNotification = null;
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