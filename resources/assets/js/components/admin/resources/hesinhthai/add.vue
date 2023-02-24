<template>
  <div class="white-box p-0">
    <div>
      <components-notifications :typeInput="typeNotification" :textInput="textNotification"></components-notifications>
      <div class="loading-roles" v-if="loading">
        <components-loading-page></components-loading-page>
      </div>
    </div>
    <div style="padding: 25px">
      <div class="c-flex">
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label">Hệ sinh thái<span class="red-dot">*</span></label>
            <multiselect v-model="heSinhThaiSelect" :options="danhmucs.he_sinh_thai" :searchable="true" track-by="code"
              name="loaigiayphep" label="name" placeholder="Chọn một phân loại" :show-labels="false"
              v-validate="'required'" @input="changeHeSinhThai()">
            </multiselect>
            <span v-show="errors.has('loaigiayphep')" class="help is-danger" style="color: red">Hệ sinh thái không thể
              bỏ trống</span>
          </div>
          <div class="line-form" v-if="showName">
            <label class="form-label">Tên hệ sinh thái<span class="red-dot">*</span></label>
            <input v-model="form.ten" class="form-control" placeholder="Tên hệ sinh thái" v-validate="'required'" />
            <span v-show="errors.has('loaigiayphep')" class="help is-danger" style="color: red">Hệ sinh thái không thể
              bỏ trống</span>
          </div>
          <div class="line-form" v-else>
            <div>
              <label class="form-label">Phân loại</label>
              <multiselect v-model="phanLoaiSelect" :options="
                heSinhThaiSelect
                  ? heSinhThaiSelect.code == 'hst_rung_tren_can'
                    ? danhmucs.loai_hst_can
                    : heSinhThaiSelect &&
                      heSinhThaiSelect.code == 'hst_dat_ngap_nuoc'
                      ? danhmucs.loai_hst_nuoc
                      : danhmucs.loai_hst_can_cu
                  : []
              " :searchable="true" track-by="id" label="name" placeholder="Phân loại" :show-labels="false" />
            </div>
          </div>
          <div class="line-form">
            <label class="form-label">Địa điểm (Vườn quốc gia, KBT)</label>
            <multiselect v-model="diaDiemSelect" :options="danhmucs.dia_diem" :searchable="true" track-by="id"
              label="name" placeholder="Địa điểm" :show-labels="false">
              <template slot-scope="props" slot="option">
                <div>
                  {{ props.option.name || props.option.orig_name }}
                </div>
              </template>
            </multiselect>
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form">
            <label for="soquocgiathamgia" class="form-label">Kỳ báo cáo </label>
            <input class="form-control" v-model="form.ky_bao_cao" placeholder="Kỳ báo cáo" name="kybaocao"
              v-validate="'max:255'" />
            <span v-show="errors.has('kybaocao')" class="help is-danger" style="color: red">Kỳ báo cáo không vượt quá
              255 kí tự</span>
          </div>
          <div class="line-form">
            <label class="form-label">Thời gian bắt đầu</label>
            <input class="form-control" v-model="form.bat_dau" type="date" />
          </div>
          <div class="line-form">
            <label class="form-label">Thời gian kết thúc</label>
            <input class="form-control" v-model="form.ket_thuc" type="date" />
          </div>
        </div>

        <div>
          <div class="d-flex container-line" v-if="showDoPhu">
            <div class="line-form">
              <label class="form-label">Độ phủ (%)</label>
              <input class="form-control" v-model="form.do_phu" placeholder="Độ phủ" type="number" />
            </div>
            <div class="line-form">
              <label class="form-label">Trữ lượng</label>
              <input v-model="form.tru_luong" class="form-control" type="number" />
            </div>
            <div class="line-form">
              <label class="form-label">Đơn vị tính trữ lượng</label>
              <input v-model="form.don_vi_tinh_tru_luong" class="form-control" />
            </div>
          </div>

          <div class="d-flex container-line">
            <div class="line-form">
              <label class="form-label">Tổng diện tích (ha)</label>
              <div class="d-flex" v-if="showDienTich">
                <input type="number" v-model="form.dien_tich" class="form-control" placeholder="ha"
                  style="width: 70%; margin-right: 15px" />
                <b-button v-b-toggle.collapse-1 variant="primary">Chi tiết</b-button>
              </div>
              <div v-else>
                <input type="number" v-model="form.dien_tich" class="form-control" placeholder="ha" />
              </div>
            </div>
            <div class="line-form" v-if="showDienTichCD">
              <label class="form-label">Diện tích chuyển đổi</label>
              <input type="number" v-model="form.dien_tich_chuyen_doi" class="form-control" placeholder="ha" />
            </div>
            <div class="line-form" v-else>
              <label for="soquocgiathamgia" class="form-label">Quận huyện
              </label>
              <multiselect v-model="quanHuyenSelect" :options="danhmucs.quan_huyen" :searchable="true" :multiple="true"
                track-by="id" label="name_vietnamese" placeholder="Chọn một quận huyện" :show-labels="false">
              </multiselect>
            </div>
            <div class="line-form">
              <label class="form-label">Nguồn tài liệu</label>
              <input v-model="form.nguon_tai_lieu" class="form-control" name="nguondulieu" v-validate="'max:255'" />
              <span v-show="errors.has('nguondulieu')" class="help is-danger" style="color: red">Nguồn dữ liệu không
                vượt quá 255 kí tự</span>
            </div>
          </div>
          <div class="d-flex container-line">
            <div class="line-form" style="width: 65%">
              <b-collapse id="collapse-1" class="mt-2" v-if="showDienTich">
                <b-card>
                  <table v-if="hstRung">
                    <tr>
                      <th>STT</th>
                      <th>Loại hình</th>
                      <th style="width: 300px">Diện tích (ha)</th>
                    </tr>
                    <tr v-for="(item, index) in loaiHinhRung" :key="item.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ item.name }}</td>
                      <td>
                        <input type="number" class="form-control" placeholder="ha" v-model="formDienTich[item.model]" />
                      </td>
                    </tr>
                  </table>
                  <table v-if="hstNuoc">
                    <tr>
                      <th>STT</th>
                      <th>Loại hình</th>
                      <th style="width: 300px">Diện tích (ha)</th>
                    </tr>
                    <tr v-for="(item, index) in loaiDatNgapNuoc" :key="item.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ item.name }}</td>
                      <td>
                        <input type="number" class="form-control" placeholder="ha" v-model="formDienTich[item.model]" />
                      </td>
                    </tr>
                  </table>
                </b-card>
              </b-collapse>
            </div>
          </div>

          <div class="d-flex container-line" v-if="showDienTichCD">
            <div class="line-form">
              <label for="soquocgiathamgia" class="form-label">Quận huyện
              </label>
              <multiselect v-model="quanHuyenSelect" :options="danhmucs.quan_huyen" :searchable="true" :multiple="true"
                track-by="id" label="name_vietnamese" placeholder="Chọn một quận huyện" :show-labels="false">
              </multiselect>
            </div>
          </div>
        </div>
        <div style="padding-bottom: 30px">
          <label class="form-label">Không gian</label>
          <UploadableMap ref="uploadable-map" @change="form.geom = $event" />
        </div>
        <div class="d-flex" style="padding-bottom: 20px">
          <div class="d-flex full-width" style="justify-content: space-between">
            <div>
              <button type="button" class="btn btn-inverse" data-bs-toggle="modal" data-bs-target="#exampleModal"
                data-bs-placement="top" title="Trở lại" @click="back">
                <i class="fas fa-arrow-left"></i>
                Hủy
              </button>
            </div>
            <div>
              <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal"
                data-bs-placement="top" title="Thêm mới" @click="add" :disabled="disableButton">
                <i class="fas fa-plus"></i> Thêm mới
              </button>
            </div>
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
import UploadableMap from "../../../UploadableMap.vue";

export default {
  components: { ValidationProvider, Multiselect, UploadableMap },
  props: ["danhmucs"],
  data: () => ({
    typeNotification: null,
    textNotification: null,
    loading: false,
    heSinhThaiSelect: null,
    phanLoaiSelect: null,
    nguonGocSelect: null,
    diaDiemSelect: null,
    phanLoaiRungSelect: null,
    phanLoaiHeSinhThaiSelect: null,
    tienTrinhUpload: null,
    fileList: [],
    disableButton: false,
    quanHuyenSelect: null,
    form: {
      he_sinh_thai_lookup_id: null,
      ten: null,
      dien_tich: null,
      dien_tich_chuyen_doi: null,
      dia_diem_id: null,
      phan_loai_id: null,
      do_phu: null,
      tru_luong: null,
      don_vi_tinh_tru_luong: null,
      nguon_tai_lieu: null,
      geom: null,
    },
    showName: false,
    showDoPhu: false,
    showDienTichCD: false,
    showDienTich: false,
    formDienTich: {},
    hstRung: false,
    hstNuoc: false,
    loaiHinhRung: [
      { name: "Rừng đặc dụng", model: "dien_tich_rung_dac_dung" },
      { name: "Rừng phòng hộ", model: "dien_tich_rung_phong_ho" },
      { name: "Rừng tự nhiên", model: "dien_tich_rung_tu_nhien" },
      { name: "Rừng trồng", model: "dien_tich_rung_trong" },
      { name: "Rừng trồng mới", model: "dien_tich_rung_trong_moi" },
      { name: "Rừng mới bị chết", model: "dien_tich_moi_chet" },
      {
        name: "Rừng chuyển đổi mục đích sử dụng",
        model: "dien_tich_rung_chuyen_doi",
      },
      { name: "Rừng được cấp chứng chỉ FSC", model: "dien_tich_rung_fsc" },
      {
        name: "Rừng được chi trả dịch vụ môi trường rừng",
        model: "dien_tich_rung_dvtm",
      },
    ],

    loaiDatNgapNuoc: [
      {
        name: "ĐNN chuyển đổi mục đích sử dung",
        model: "dien_tich_dnn_chuyen_doi",
      },
      {
        name: "Rừng ngập nước (ngọt, phèn)",
        model: "dien_tich_rung_ngap_nuoc",
      },
      { name: "Sông, ngòi, kênh, rạch, suối", model: "dien_tich_song_ngoi" },
      { name: "Đất mặt nước chuyên dùng", model: "dien_tich_dat_mat_nuoc" },
      { name: "Rừng được cấp chứng chỉ FSC", model: "dien_tich_rung_fsc" },
      {
        name: "Rừng được chi trả dịch vụ môi trường rừng",
        model: "dien_tich_rung_dvtm",
      },
    ],
  }),
  methods: {
    back() {
      window.history.back();
    },
    resetForm() {
      this.heSinhThaiSelect = null;
      this.phanLoaiSelect = null;
      this.diaDiemSelect = null;
      this.phanLoaiHeSinhThaiSelect = null;
      this.form = {
        he_sinh_thai_lookup_id: null,
        ten: null,
        dien_tich: null,
        dien_tich_chuyen_doi: null,
        dia_diem_id: null,
        phan_loai_id: null,
        nguon_goc_id: null,
        phan_loai_rung_id: null,
        phan_loai_he_sinh_thai_id: null,
        do_phu: null,
        tru_luong: null,
        don_vi_tinh_tru_luong: null,
        dien_tich_moi_phuc_hoi: null,
        dien_tich_moi_chet: null,
        dien_tich_rung_phong_ho: null,
        dien_tich_rung_fsc: null,
        dien_tich_rung_dvtm: null,
        nguon_tai_lieu: null,
        mo_ta: null,
        geom: null,
      };
      this.$refs["uploadable-map"].removeFeature();
    },
    changeHeSinhThai() {
      this.hstRung = false;
      this.hstNuoc = false;
      this.showDoPhu = false;
      this.showDienTichCD = false;
      this.showName = false;
      this.showDienTich = false;
      if (
        this.heSinhThaiSelect.code == "hst_nong_nghiep" ||
        this.heSinhThaiSelect.code == "hst_dat_co_cay_trang_bui"
      ) {
        this.showName = true;
      } else {
        this.showName = false;
      }

      if (this.heSinhThaiSelect.code == "hst_rung_tren_can") {
        this.showDoPhu = true;
        this.hstRung = true;
      }
      if (
        this.heSinhThaiSelect.code == "hst_nong_nghiep" ||
        this.heSinhThaiSelect.code == "hst_dat_co_cay_trang_bui"
      ) {
        this.showDienTichCD = true;
      }

      if (
        this.heSinhThaiSelect.code == "hst_rung_tren_can" ||
        this.heSinhThaiSelect.code == "hst_dat_ngap_nuoc"
      ) {
        this.showDienTich = true;
      }
      if (this.heSinhThaiSelect.code == "hst_dat_ngap_nuoc") {
        this.hstNuoc = true;
      }
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        this.form.he_sinh_thai_lookup_id = this.heSinhThaiSelect
          ? this.heSinhThaiSelect.id
          : null;
        this.form.phan_loai_id = this.phanLoaiSelect
          ? this.phanLoaiSelect.id
          : null;

        this.form.dia_diem_id = this.diaDiemSelect
          ? this.diaDiemSelect.id
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
            .post(
              env.endpointhttp + "admin/hesinhthai/add",
              Object.assign(this.form, this.formDienTich)
            )
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Thêm mới thành công";
                window.location = '/admin/hesinhthai'
              }
              else if (response.data.message == 'datetime') {
                this.typeNotification = 1;
                this.textNotification = "Ngày bắt đầu phải nhỏ hơn ngày kết thúc";
              }
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

table {
  border-collapse: collapse;
  width: 100%;
}

th,
td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

tr:hover {
  background-color: #eaeded;
}
</style>
