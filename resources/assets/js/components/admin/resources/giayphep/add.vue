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
      <!-- <div class="d-flex">
        <div style="font-size: 18px; font-weight: 500; flex: 1">
          <button
            type="button"
            class="btn btn-rounded"
            data-bs-toggle="modal"
            data-bs-target="#exampleModal"
            data-bs-placement="top"
            title="Trở lại"
            @click="back"
          >
            <i class="fas fa-arrow-left"></i>
          </button>
          <span style="padding-left: 10px">
            Thêm dữ liệu hợp tác quốc tế và Khoa Học Công Nghệ</span
          >
        </div>
        <button
          type="button"
          class="btn btn-info btn-rounded"
          data-bs-toggle="modal"
          data-bs-target="#exampleModal"
          data-bs-placement="top"
          title="Thêm mới"
          @click="add"
          :disabled="disableButton"
        >
          <i class="fas fa-plus"></i> Thêm mới
        </button>
      </div> -->
      <div class="c-flex">
        <div class="d-flex container-line">
          <div class="line-form" style="width: 65%">
            <label class="form-label"
              >Loại giấy phép đa dạng sinh học<span class="red-dot"
                >*</span
              ></label
            >
            <multiselect
              v-model="loaiGiaySelect"
              :options="danhmucs.loai_giay"
              :searchable="true"
              track-by="code"
              name="loaigiayphep"
              label="name"
              placeholder="Chọn một phân loại"
              :show-labels="false"
               v-validate="'required'"
              @input="changeLoaiGiay()"
            >
            </multiselect>
            <span
              v-show="errors.has('loaigiayphep')"
              class="help is-danger"
              style="color: red"
              >Loại giấy phép không thể bỏ trống</span
            >
          </div>
          <div class="line-form">
            <label class="form-label"
              >Số hiệu văn bản<span class="red-dot"></span
            ></label>
            <input
              v-model="form.so_hieu"
              class="form-control"
              placeholder="Số hiệu văn bản"
            />
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label">Ngày cấp</label>
            <input type="date" class="form-control" v-model="form.ngay_cap" />
          </div>
          <div class="line-form">
            <label class="form-label">Ngày hiệu lực</label>
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
        </div>
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label">Cơ quan cấp</label>
            <input
              v-model="form.co_quan_cap"
              placeholder="Tên cơ quan cấp giấy phép"
              class="form-control"
            />
          </div>
          <div class="line-form">
            <label class="form-label">Loại cấp phép</label>
            <multiselect
              v-model="loaiCapSelect"
              :options="danhmucs.loai_cap"
              :searchable="true"
              track-by="id"
              label="name"
              placeholder="Chọn một loại hình"
              :show-labels="false"
            />
          </div>
          <div class="line-form">
            <label class="form-label">Đối tượng cấp phép</label>
            <multiselect
              v-model="doiTuongSelect"
              :options="danhmucs.doi_tuong"
              :searchable="true"
              track-by="id"
              label="name"
              placeholder="Chọn một cấp"
              :show-labels="false"
            />
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label"
              >Tên đơn vị được cấp<span class="red-dot"></span
            ></label>
            <input
              v-model="form.don_vi_duoc_cap"
              placeholder="Tên đơn vị được cấp"
              class="form-control"
            />
          </div>
          <div class="line-form" style="width: 65%">
            <label class="form-label">Địa chỉ</label>
            <input
              v-model="form.dia_chi"
              placeholder="Địa chỉ"
              class="form-control"
            />
          </div>
        </div>

        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label">Tên nguồn gen</label>
            <input
              v-model="form.ten"
              placeholder="Tên thông thường"
              class="form-control"
            />
          </div>
          <div class="line-form">
            <label class="form-label">Tên khoa học</label>
            <input v-model="form.ten_khoa_hoc" class="form-control" />
          </div>
          <div class="line-form" v-if="chonLoaiGiayPhep.loai1">
            <label class="form-label">Mẫu nguồn gen</label>
            <input v-model="form.mau_nguon_gen" class="form-control" />
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form" v-if="chonLoaiGiayPhep.loai3">
            <label class="form-label">Chủng loại</label>
            <input v-model="form.chung_loai" class="form-control" />
          </div>

          <div
            class="line-form"
            v-if="chonLoaiGiayPhep.loai1 || chonLoaiGiayPhep.loai3"
          >
            <label class="form-label">Số lượng</label>
            <input
              v-model="form.so_luong"
              placeholder="Tên thông thường"
              class="form-control"
              type="number"
            />
          </div>
          <div class="line-form" v-if="chonLoaiGiayPhep.loai1">
            <label class="form-label">Khối lượng</label>
            <input
              v-model="form.khoi_luong"
              placeholder="Khối lượng"
              class="form-control"
            />
          </div>
          <div
            class="line-form"
            v-if="chonLoaiGiayPhep.loai1 || chonLoaiGiayPhep.loai3"
          >
            <label class="form-label">Mục đích</label>
            <multiselect
              v-model="mucDichSelect"
              :options="danhmucs.muc_dich"
              :searchable="true"
              track-by="id"
              label="name"
              placeholder="Chọn một mục đich"
              :show-labels="false"
            />
          </div>
        </div>

        <div class="d-flex container-line" v-if="chonLoaiGiayPhep.loai1">
          <div class="line-form">
            <label class="form-label">Đơn vị cung cấp</label>
            <input
              v-model="form.don_vi_cung_cap"
              placeholder="Tên đơn vị cung cấp"
              class="form-control"
            />
          </div>
          <div class="line-form">
            <label class="form-label">Địa điểm</label>
            <input
              v-model="form.dac_diem"
              placeholder="Đặc điểm"
              class="form-control"
            />
          </div>
          <div class="line-form">
            <label class="form-label">Cách thức tiếp cận</label>
            <input
              v-model="form.cach_tiep_can"
              placeholder="Cách tiếp cận"
              class="form-control"
            />
          </div>
        </div>

        <div class="d-flex container-line" v-if="chonLoaiGiayPhep.loai2">
          <div class="line-form">
            <label class="form-label">Sự kiện chuyển gen</label>
            <input v-model="form.su_kien_chuyen_gen" class="form-control" />
          </div>
          <div class="line-form">
            <label class="form-label">Tình trạng liên quan</label>
            <input
              v-model="form.tinh_trang_lien_quan"
              placeholder="Tình trạng liên quan đến gen chuyển"
              class="form-control"
            />
          </div>
          <div class="line-form">
            <label class="form-label">Mã</label>
            <input
              v-model="form.ma"
              placeholder="Mã nhận diện duy nhất"
              class="form-control"
            />
          </div>
        </div>

        <div class="d-flex container-line" v-if="chonLoaiGiayPhep.loai3">
          <div class="line-form">
            <label class="form-label">Địa điểm khai thác</label>
            <input v-model="form.dac_diem_khai_thac" class="form-control" />
          </div>
          <div class="line-form">
            <label class="form-label">Phương tiện khai thác</label>
            <input
              v-model="form.phuong_tien_khai_thac"
              placeholder="Phương tiện khai thác"
              class="form-control"
            />
          </div>
          <div class="line-form">
            <label class="form-label">Hình thức khai thác</label>
            <input
              v-model="form.hinh_thuc_khai_thac"
              placeholder="Hình thức khai thác"
              class="form-control"
            />
          </div>
        </div>

        <div class="d-flex container-line">
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
        <div class="d-flex" style="justify-content: space-between">
          <div>
            <button
              type="button"
              class="btn btn-inverse"
              data-bs-toggle="modal"
              data-bs-target="#exampleModal"
              data-bs-placement="top"             
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
              title="Thêm mới"
              @click="add"
              :disabled="disableButton"
            >
              {{ $t("admin.buttons.add") }}
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
  props: ["danhmucs"],
  data: () => ({
    typeNotification: null,
    textNotification: null,
    loading: false,
    loaiGiaySelect: null,
    loaiCapSelect: null,
    doiTuongSelect: null,
    mucDichSelect: null,
    tienTrinhUpload: null,
    fileList: [],
    disableButton: false,
    form: {
      ten: null,
      ten_khoa_hoc: null,
      mau_nguon_gen: null,
      giay_phep_id: null,
      so_hieu: null,
      co_quan_cap: null,
      ngay_cap: null,
      ngay_het_han: null,
      ngay_hieu_luc: null,
      loai_cap_phep_id: null,
      don_vi_duoc_cap: null,
      doi_tuong_id: null,
      dia_chi: null,
      chung_loai: null,
      so_luong: null,
      khoi_luong: null,
      muc_dich_id: null,
      ghi_chu: null,
      don_vi_cung_cap: null,
      dac_diem: null,
      cach_tiep_can: null,
      su_kien_chuyen_gen: null,
      tinh_trang_lien_quan: null,
      ma: null,
      dac_diem_khai_thac: null,
      phuong_tien_khai_thac: null,
      hinh_thuc_khai_thac: null,
      files: [],
    },
    chonLoaiGiayPhep: {
      loai1: false,
      loai2: false,
      loai3: false,
    },
  }),
  methods: {
    back() {
      window.history.back();
    },
    resetForm() {
      this.loaiGiaySelect = null;
      this.loaiCapSelect = null;
      this.doiTuongSelect = null;
      this.mucDichSelect = null;
      this.fileList = [];
      this.form = {
        ten: null,
        ten_khoa_hoc: null,
        mau_nguon_gen: null,
        giay_phep_id: null,
        so_hieu: null,
        co_quan_cap: null,
        ngay_cap: null,
        ngay_het_han: null,
        ngay_hieu_luc: null,
        loai_cap_phep_id: null,
        don_vi_duoc_cap: null,
        doi_tuong_id: null,
        dia_chi: null,
        chung_loai: null,
        so_luong: null,
        khoi_luong: null,
        muc_dich_id: null,
        ghi_chu: null,
        don_vi_cung_cap: null,
        dac_diem: null,
        cach_tiep_can: null,
        su_kien_chuyen_gen: null,
        tinh_trang_lien_quan: null,
        ma: null,
        dac_diem_khai_thac: null,
        phuong_tien_khai_thac: null,
        hinh_thuc_khai_thac: null,
        files: [],
        files: [],
      };
    },
    changeLoaiGiay() {
      this.form.so_luong = null;
      this.form.muc_dich_id = null;
      this.form.cach_tiep_can = null;
      this.form.su_kien_chuyen_gen = null;
      this.form.tinh_trang_lien_quan = null;
      this.form.ma = null;
      this.form.dac_diem_khai_thac = null;
      this.form.phuong_tien_khai_thac = null;
      this.form.phuong_tien_khai_thac = null;
      this.form.dac_diem = null;
      this.form.mau_nguon_gen = null;
      if (this.loaiGiaySelect) {
        if (this.loaiGiaySelect.code == "chung_nhan_uu_tien") {
          this.chonLoaiGiayPhep = {
            loai1: false,
            loai2: false,
            loai3: true,
          };
        } else if (this.loaiGiaySelect.code == "chung_nhan_an_toan") {
          this.chonLoaiGiayPhep = {
            loai1: false,
            loai2: true,
            loai3: false,
          };
        } else {
          this.chonLoaiGiayPhep = {
            loai1: true,
            loai2: false,
            loai3: false,
          };
        }
      } else {
        this.chonLoaiGiayPhep = {
          loai1: false,
          loai2: false,
          loai3: false,
        };
      }
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        this.form.giay_phep_id = this.loaiGiaySelect ? this.loaiGiaySelect.id : null
        this.form.loai_cap_phep_id = this.loaiCapSelect ? this.loaiCapSelect.id : null;
        this.form.doi_tuong_id = this.doiTuongSelect ? this.doiTuongSelect.id : null;
        this.form.muc_dich_id = this.mucDichSelect ? this.mucDichSelect.id : null
        this.disableButton = true;
        if (validate) {
          axios
            .post(env.endpointhttp + "admin/giayphep/addv2", this.form)
            .then(() => {
              this.typeNotification = 2;
              this.textNotification = "Thêm mới thành công";
              this.resetForm();
            })
            .catch(() => {
              this.typeNotification = 1;
              this.textNotification = "Thêm mới thất bại";
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