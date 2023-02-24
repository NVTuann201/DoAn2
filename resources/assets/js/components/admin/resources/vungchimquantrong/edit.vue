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
              >Tên vùng chim<span class="red-dot">*</span></label
            >
            <input
              v-model="form.ten"
              class="form-control"
              placeholder="Tên vùng chim"
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
            <label class="form-label"
              >Cơ quan ban hành<span class="red-dot"></span
            ></label>
            <input
              v-model="form.co_quan_ban_hanh"
              class="form-control"
              placeholder="Cơ quan ban hành"
            />
          </div>
        </div>

        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label"
              >Vùng chim đặc hữu<span class="red-dot"></span
            ></label>
            <input
              v-model="form.vung_chim_dac_huu"
              class="form-control"
              placeholder="Vùng chim đặc hữu"
            />
          </div>
          <div class="line-form">
            <label class="form-label"
              >Cảnh quan ưu tiên<span class="red-dot"></span
            ></label>
            <input
              v-model="form.canh_quan_uu_tien"
              class="form-control"
              placeholder="Cảnh quan ưu tiên"
            />
          </div>

          <div class="line-form">
            <label class="form-label"
              >Sinh cảnh<span class="red-dot"></span
            ></label>
            <input
              v-model="form.sinh_canh"
              class="form-control"
              placeholder="Sinh cảnh"
            />
          </div>
        </div>

        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label"
              >Khu bảo tồn<span class="red-dot"></span
            ></label>
            <multiselect
              v-model="khuBaoTonSelect"
              :options="danhmucs.khu_bao_ton"
              :searchable="true"
              track-by="id"
              label="orig_name"
              placeholder="Chọn Địa điểm"
              :show-labels="false"
            >
              <template slot-scope="props" slot="option">
                <div>
                  {{ props.option.name || props.option.orig_name }}
                </div>
              </template>
            </multiselect>
          </div>
          <div class="line-form">
            <label class="form-label"
              >Mức độ quan trọng<span class="red-dot"></span
            ></label>
            <multiselect
              v-model="mucDoQuanTrongSelect"
              :options="danhmucs.do_quan_trong"
              :searchable="true"
              track-by="id"
              label="name"
              placeholder="Mức độ quan trọng"
              :show-labels="false"
            />
          </div>
          <div class="line-form">
            <label class="form-label">Hiện trạng quản lý</label>
            <input v-model="form.hien_trang_quan_ly" class="form-control" />
          </div>
        </div>

        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label"
              >Phân cấp quản lý<span class="red-dot"></span
            ></label>
            <input class="form-control" v-model="form.phan_cap_quan_ly" />
          </div>
          <div class="line-form">
            <label class="form-label">Cơ quan quản lý</label>
            <input class="form-control" v-model="form.co_quan_quan_ly" />
          </div>
          <div class="line-form">
            <label class="form-label">Hoạt động bảo tồn</label>
            <input v-model="form.hoat_dong_bao_ton" class="form-control" />
          </div>
        </div>

        <div class="d-flex container-line">
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
          <div class="line-form">
            <label class="form-label">Danh hiệu quốc tế</label>
            <input
              class="form-control"
              v-model="form.danh_hieu_quoc_te"
              placeholder="Danh hiệu quốc tế"
            />
          </div>
          <div class="line-form">
            <label class="form-label">Thời gian công nhận</label>
            <input
              v-model="form.thoi_gian_cong_nhan"
              type="date"
              class="form-control"
            />
          </div>
        </div>

        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label">Quận huyện</label>
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
          <div class="line-form">
            <label class="form-label">Quy hoạch tỉnh</label>
            <input v-model="form.quy_hoach_tinh" class="form-control" />
          </div>
          <div class="line-form">
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
              @click="update"
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
    quanHuyenSelect: null,
    mucDoQuanTrongSelect: null,
    form: {
      ten: null,
      mo_ta: null,
      dien_tich: null,
      tinh_trang_id: null,
      can_cu_de_xuat: null,
      quyet_dinh_thanh_lap: null,
      ngay_thanh_lap: null,
      co_quan_ban_hanh: null,
      vung_chim_dac_huu: null,
      canh_quan_uu_tien: null,
      sinh_canh: null,
      khu_bao_ton_id: null,
      muc_do_quan_trong_id: null,
      hien_trang_quan_ly: null,
      phan_cap_quan_ly: null,
      co_quan_quan_ly: null,
      hoat_dong_bao_ton: null,
      quoc_te_cong_nhan: true,
      danh_hieu_quoc_te: null,
      thoi_gian_cong_nhan: null,
      quan_huyen: [],
      quy_hoach_tinh: null,
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
      let quanHuyenId = this.data.quan_huyen
        ? JSON.parse(this.data.quan_huyen)
        : [];
      this.quanHuyenSelect = this.danhmucs.quan_huyen.filter((el) =>
        quanHuyenId.includes(el.id)
      );
      this.khuBaoTonSelect = Object.assign({}, this.data.khu_bao_ton);
      this.mucDoQuanTrongSelect = Object.assign({}, this.data.do_quan_trong);
    },
    resetForm() {
      this.tinhTrangSelect = null;
      this.khuBaoTonSelect = null;
      this.quanHuyenSelect = null;
      this.mucDoQuanTrongSelect = null;
      this.form = {
        ten: null,
        mo_ta: null,
        dien_tich: null,
        tinh_trang_id: null,
        can_cu_de_xuat: null,
        quyet_dinh_thanh_lap: null,
        ngay_thanh_lap: null,
        co_quan_ban_hanh: null,
        vung_chim_dac_huu: null,
        canh_quan_uu_tien: null,
        sinh_canh: null,
        khu_bao_ton_id: null,
        muc_do_quan_trong_id: null,
        hien_trang_quan_ly: null,
        phan_cap_quan_ly: null,
        co_quan_quan_ly: null,
        hoat_dong_bao_ton: null,
        quoc_te_cong_nhan: true,
        danh_hieu_quoc_te: null,
        thoi_gian_cong_nhan: null,
        quan_huyen: [],
        quy_hoach_tinh: null,
        ghi_chu: null,
      };
    },

    update() {
      this.$validator.validateAll().then((validate) => {
        this.form.tinh_trang_id = this.tinhTrangSelect
          ? this.tinhTrangSelect.id
          : null;

        this.form.khu_bao_ton_id = this.khuBaoTonSelect
          ? this.khuBaoTonSelect.id
          : null;

        this.form.muc_do_quan_trong_id = this.mucDoQuanTrongSelect
          ? this.mucDoQuanTrongSelect.id
          : null;

        this.form.quan_huyen = [];
        if (this.quanHuyenSelect && this.quanHuyenSelect.length) {
          this.quanHuyenSelect.forEach((el) => {
            this.form.quan_huyen.push(el.id);
          });
        }
        if (validate) {
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/vungchimquantrong/edit", this.form)
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