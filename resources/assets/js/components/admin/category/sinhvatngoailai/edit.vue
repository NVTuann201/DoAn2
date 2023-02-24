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
              >Tên loài ngoại lai xâm hại<span class="red-dot">*</span></label
            >
            <input
              v-model="form.ten"
              class="form-control"
              placeholder="Tên loài"
              v-validate="'required'"
              name="ten"
            />
            <span
              v-show="errors.has('ten')"
              class="help is-danger"
              style="color: red"
              >Tên loài không thể bỏ trống</span
            >
          </div>
          <div class="line-form">
            <label class="form-label"
              >Tên khoa học<span class="red-dot"></span
            ></label>
            <input v-model="form.ten_khoa_hoc" class="form-control" />
          </div>
          <div class="line-form">
            <label class="form-label"
              >Phân loại<span class="red-dot"></span
            ></label>
            <multiselect
              v-model="phanLoaiSelect"
              :options="danhmucs.phan_loai"
              :searchable="true"
              track-by="id"
              label="name"
              placeholder="Phân loại"
              :show-labels="false"
            />
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label"
              >Nguy cơ xâm hại<span class="red-dot"></span
            ></label>
            <multiselect
              v-model="nguyCoSelect"
              :options="danhmucs.nguy_co"
              :searchable="true"
              track-by="id"
              label="name"
              placeholder="Nguy cơ xâm hại"
              :show-labels="false"
            />
          </div>
          <div class="line-form">
            <label class="form-label"
              >Nguồn gốc<span class="red-dot"></span
            ></label>
            <input v-model="form.nguon_goc" class="form-control" />
          </div>

          <div class="line-form">
            <label class="form-label"
              >Diện tích phân bổ (ha)<span class="red-dot"></span
            ></label>
            <input
              v-model="form.dien_tich_phan_bo"
              class="form-control"
              type="number"
              placeholder="Diện tích phân bổ (ha)"
            />
          </div>
        </div>

        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label"
              >Mật độ<span class="red-dot"></span
            ></label>
            <input v-model="form.mat_do" class="form-control" type="number" />
          </div>
          <div class="line-form">
            <label class="form-label"
              >Đơn vị tính mật độ<span class="red-dot"></span
            ></label>
            <input v-model="form.don_vi_tinh_mat_do" class="form-control" />
          </div>

          <div class="line-form">
            <label class="form-label"
              >Nơi phân bổ<span class="red-dot"></span
            ></label>
            <input
              v-model="form.noi_phan_bo"
              class="form-control"
              placeholder="Nơi phân bổ"
            />
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label"
              >Địa điểm<span class="red-dot"></span
            ></label>
            <multiselect
              v-model="khuBaoTonSelect"
              :multiple="true"
              :options="danhmucs.khu_bao_ton"
              :searchable="true"
              track-by="id"
              label="orig_name"
              placeholder="Chọn Địa điểm"
              :show-labels="false"
            >
              <template slot-scope="props" slot="option">
                <div>
             {{props.option.name || props.option.orig_name}}
                </div>
              </template>
            </multiselect>
          </div>
          <div class="line-form">
            <label class="form-label"
              >Thời gian<span class="red-dot"></span
            ></label>
            <input
              v-model="form.thoi_gian"
              type="number"
              class="form-control"
            />
          </div>
          <div class="line-form">
            <label class="form-label">Tỉnh thành</label>
            <multiselect
              v-model="quanHuyenSelect"
              :options="danhmucs.quan_huyen"
              :searchable="true"
              :multiple="true"
              track-by="id"
              label="name_vietnamese"
              placeholder="Quận huyện"
              :show-labels="false"
            />
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label"
              >Đặc điểm hình thái<span class="red-dot"></span
            ></label>
            <input v-model="form.dac_diem_hinh_thai" class="form-control" />
          </div>
          <div class="line-form">
            <label class="form-label"
              >Đặc điểm sinh thái<span class="red-dot"></span
            ></label>
            <input v-model="form.dac_diem_sinh_thai" class="form-control" />
          </div>
          <div class="line-form">
            <label class="form-label">Kinh nghiệm phòng ngừa</label>
            <input v-model="form.kinh_nghiem_phong_ngua" class="form-control" />
          </div>
        </div>

        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label"
              >Phân bố Việt Nam<span class="red-dot"></span
            ></label>
            <input v-model="form.phan_bo_viet_nam" class="form-control" />
          </div>
          <div class="line-form" style="width: 65%">
            <label class="form-label">Ghi nhận trên thế giới</label>
            <input v-model="form.ghi_nhan_the_gioi" class="form-control" />
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label">Nguồn dữ liệu</label>
            <input v-model="form.nguon_du_lieu" class="form-control" />
          </div>
          <div class="line-form" style="width: 65%">
            <label class="form-label">Ghi chú</label>
            <input v-model="form.ghi_chu" class="form-control" />
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
              @click="edit"
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
    quanHuyenSelect: null,
    phanLoaiSelect: null,
    nguyCoSelect: null,
    form: {
      ten: null,
      ten_khoa_hoc: null,
      phan_loai_id: null,
      nguy_co_id: null,
      nguon_goc: null,
      dien_tich_phan_bo: null,
      mat_do: null,
      don_vi_tinh_mat_do: null,
      noi_phan_bo: null,
      dia_diem_id: [],
      thoi_gian: null,
      nguon_du_lieu: null,
      ghi_chu: null,
      quan_huyen: [],
      dac_diem_hinh_thai: null,
      dac_diem_sinh_thai: null,
      kinh_nghiem_phong_ngua: null,
      phan_bo_viet_nam: null,
      ghi_nhan_the_gioi: null,
    },
  }),
  mounted() {
    this.getData();
  },
  methods: {
    back() {
      window.location.href = "/admin/sinhvatngoailai";
    },
    getData() {
      for (let item in this.form) {
        this.form[item] = this.data[item];
      }
      this.form.id = this.data.id;
      this.phanLoaiSelect = Object.assign({}, this.data.phan_loai);
      let quanHuyenId = this.data.quan_huyen
        ? JSON.parse(this.data.quan_huyen)
        : [];
      this.quanHuyenSelect = this.danhmucs.quan_huyen.filter((el) =>
        quanHuyenId.includes(el.id)
      );
      this.nguyCoSelect = Object.assign({}, this.data.nguy_co);
      this.khuBaoTonSelect = [...this.data.dia_diem];
    },
    resetForm() {
      this.nguyCoSelect = null;
      this.khuBaoTonSelect = null;
      this.phanLoaiSelect = null;
      this.quanHuyenSelect = null;
      this.form = {
        ten: null,
        ten_khoa_hoc: null,
        phan_loai_id: null,
        nguy_co_id: null,
        nguon_goc: null,
        dien_tich_phan_bo: null,
        mat_do: null,
        don_vi_tinh_mat_do: null,
        noi_phan_bo: null,
        dia_diem_id: [],
        thoi_gian: null,
        nguon_du_lieu: null,
        ghi_chu: null,
        quan_huyen: [],
        dac_diem_hinh_thai: null,
        dac_diem_sinh_thai: null,
        kinh_nghiem_phong_ngua: null,
        phan_bo_viet_nam: null,
        ghi_nhan_the_gioi: null,
      };
    },

    edit() {
      this.$validator.validateAll().then((validate) => {
        this.form.phan_loai_id = this.phanLoaiSelect
          ? this.phanLoaiSelect.id
          : null;

        this.form.dia_diem_id = [];
        if (this.khuBaoTonSelect && this.khuBaoTonSelect.length) {
          this.khuBaoTonSelect.forEach((el) => {
            this.form.dia_diem_id.push(el.id);
          });
        }

        this.form.nguy_co_id = this.nguyCoSelect ? this.nguyCoSelect.id : null;
        this.form.quan_huyen = [];
        if (this.quanHuyenSelect && this.quanHuyenSelect.length) {
          this.quanHuyenSelect.forEach((el) => {
            this.form.quan_huyen.push(el.id);
          });
        }
        if (validate) {
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/sinhvatngoailai/edit", this.form)
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