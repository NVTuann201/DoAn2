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
            <label class="form-label">Quận huyện</label>
            <multiselect v-model="quanHuyenSelect" :options="danhmucs.quan_huyen" :searchable="true" track-by="id"
              label="name_vietnamese" placeholder="Địa điểm" :show-labels="false" />
          </div>

          <div class="line-form" style="width: 65%">
            <label class="form-label">Tên cơ sở bảo tồn<span class="red-dot">*</span></label>
            <input v-model="form.ten" class="form-control" placeholder="Tên cơ sở bảo tồn" v-validate="'required'"
              name="ten" />
            <span v-show="errors.has('ten')" class="help is-danger" style="color: red">Tên cơ sở bảo tồn không thể bỏ
              trống</span>
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label">Địa chỉ<span class="red-dot"></span></label>
            <input v-model="form.dia_chi" class="form-control" placeholder="Địa chỉ" />
          </div>
          <div class="line-form" style="width: 65%">
            <label class="form-label">Cơ quan chủ quản</label>
            <input class="form-control" v-model="form.co_quan_chu_quan" placeholder="Cơ quan chủ quản" />
          </div>
        </div>
        <div class="d-flex container-line">
          <div class="line-form">
            <label class="form-label">Quyết định thành lập/Giấy phép
              <span data-toggle="tooltip" data-placement="top"
                title="Văn bản phê duyệt (số hiệu, ngày tháng, cơ quan ban hành)">
                <i class="fas fa-info-circle"></i>
              </span>
            </label>

            <input class="form-control" v-model="form.giay_phep" placeholder="Quyết định thành lập/Giấy phép" />
          </div>
          <div class="line-form" style="width: 65%">
            <div class="c-flex">
              <div class="d-flex">
                <label style="flex: 1">Đính kèm văn bản
                  <span data-toggle="tooltip" data-placement="top" title="Đính kèm văn bản, quyết định ....">
                    <i class="fas fa-info-circle"></i>
                  </span>
                </label>
                <div>
                  <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                    data-bs-target="#exampleModal" data-bs-placement="top" title="Tải lên" @click="clickUpload">
                    <i class="fas fa-thumbtack"></i> Tải tệp lên
                  </button>
                </div>
                <input type="file" id="myfile" name="myfile" multiple ref="upload-files" style="display: none"
                  @change="handleUpload" />
              </div>
              <br />
              <div>
                <div class="progress" style="height: 10px" v-show="
                  tienTrinhUpload &&
                  tienTrinhUpload != 0 &&
                  tienTrinhUpload != 100
                ">
                  <div class="progress-bar" role="progressbar" style="font-weight: bold" aria-valuenow="25"
                    aria-valuemin="0" aria-valuemax="100" :style="{ width: tienTrinhUpload + 'px' }">
                    {{ tienTrinhUpload }}
                  </div>
                </div>
                <div class="c-flex">
                  <div class="d-flex" v-for="item in fileList" :key="item.id">
                    <div @click="taiTapTin(item)" style="flex: 1; padding-bottom: 5px; cursor: pointer"
                      :title="item.name">
                      {{
                          item.name && item.name.length < 40 ? item.name : "..." + item.name.substr(-39)
                      }} </div>
                        <div>
                          <i class="fas fa-trash-alt" style="cursor: pointer" @click="xoaTapTin(item.id)"></i>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group container-line"></div>
          <div class="d-flex container-line">
            <div class="line-form">
              <label class="form-label">Phân cấp quản lý<span class="red-dot"></span></label>
              <multiselect v-model="quanLySelect" :options="danhmucs.quan_ly" :searchable="true" track-by="id"
                label="name" placeholder="Đơn vị quản lý" :show-labels="false" />
            </div>
            <div class="line-form" style="width: 65%">
              <label class="form-label">Cơ quan quản lý<span class="red-dot"></span></label>
              <input v-model="form.co_quan_quan_ly" class="form-control" placeholder="Cơ quan quản lý" />
            </div>
          </div>
          <div class="d-flex container-line">
            <div class="line-form">
              <label class="form-label">Diện tích (ha)</label>
              <input class="form-control" v-model="form.dien_tich" placeholder="Diện tích (ha)" type="number" />
            </div>
            <div class="line-form">
              <label class="form-label">Loại hình</label>
              <multiselect v-model="loaiHinhSelect" :options="danhmucs.loai_hinh" :searchable="true" track-by="id"
                label="name" placeholder="Loại hình" :show-labels="false" />
            </div>
            <div class="line-form">
              <label class="form-label">Loại hình tổ chức</label>
              <multiselect v-model="loaiHinhToChucSelect" :options="danhmucs.loai_hinh_to_chuc" :searchable="true"
                track-by="id" label="name" placeholder="Loại hình tổ chức" :show-labels="false" />
            </div>
          </div>

          <div class="d-flex container-line">
            <div class="line-form" style="width: 65%">
              <label class="form-label">Chức năng<span class="red-dot"></span></label>
              <multiselect v-model="form.chuc_nang" :options="chucNangs" :searchable="true" placeholder="Chức năng"
                :show-labels="false" />
            </div>

            <div class="line-form">
              <label class="form-label">Hình thức bảo tồn<span class="red-dot"></span></label>
              <multiselect v-model="form.hinh_thuc_bao_ton" :options="hinhThucBaoTons" :searchable="true"
                placeholder="Hình thức bảo tồn" :show-labels="false" />
            </div>
          </div>

          <div class="d-flex container-line">
            <div class="line-form">
              <label class="form-label">Cơ sở vật chất</label>
              <input class="form-control" v-model="form.co_so_vat_chat" placeholder="Cơ sở vật chất" />
            </div>
            <div class="line-form">
              <label class="form-label">Quy trình kỹ thuật</label>
              <input v-model="form.quy_trinh_ky_thuat" class="form-control" />
            </div>
            <div class="line-form">
              <label class="form-label">Nhân lực</label>
              <input v-model="form.nhan_luc" class="form-control" />
            </div>
          </div>

          <div class="d-flex container-line">
            <div class="line-form">
              <label class="form-label">Tài chính</label>
              <multiselect v-model="form.tai_chinh" :options="taiChinhs" :searchable="true"
                placeholder="Nguồn lực tài chính" :show-labels="false" />
            </div>
            <div class="line-form">
              <label class="form-label">Trạng thái</label>
              <multiselect v-model="form.status" :options="trangThai" :show-labels="true" />
            </div>
            <div class="line-form"></div>
          </div>
          <div class="c-flex" style="padding-bottom: 30px">
            <div class="d-flex" style="padding-bottom: 20px">
              <label class="form-label">Không gian</label>
            </div>
            <UploadableMap ref="uploadable-map" @change="form.geom = $event" />
          </div>

          <div class="d-flex" style="justify-content: space-between">
            <div>
              <button type="button" class="btn btn-inverse" data-bs-toggle="modal" data-bs-target="#exampleModal"
                data-bs-placement="top" @click="back">
                {{ $t("admin.button_title.back_to_list") }}
              </button>
            </div>
            <div>
              <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal"
                data-bs-placement="top" @click="add" :disabled="disableButton">
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
import UploadableMap from "../../../UploadableMap.vue";

export default {
  components: { ValidationProvider, Multiselect, UploadableMap },
  props: ["danhmucs"],
  data: () => ({
    typeNotification: null,
    textNotification: null,
    loading: false,
    loaiHinhSelect: null,
    loaiHinhToChucSelect: null,
    diaDiemSelect: null,
    quanLySelect: null,
    quanHuyenSelect: null,
    tienTrinhUpload: null,
    fileList: [],
    disableButton: false,
    chucNangs: [
      "Tiếp nhận động vật hoang dã thu từ các vụ săn bắt, vận chuyển, buôn bán trái phép, nuôi phục hồi, thả lại môi trường tự nhiên",
      "Bảo tồn nguồn gen các loài động vật, thực  vật nguy cấp, quý, hiếm",
      "Nuôi, trồng bảo tồn các loài động vật, thực vật nguy cấp, quý, hiếm",
      "Nghiên cứu khoa học",
      "Nuôi trưng bày phục vụ tham quan, du lịch",
      "Dịch vụ giống, chuyển giao kỹ thuật cho các trung tâm nhân nuôi",
      "Nuôi, trồng vì mục đích kinh doanh",
      "Bảo tồn tai chỗ, chuyển chỗ các nguồn gen quý hiếm, gen đặc sản, vườn cây thuốc",
      "Chức năng khác",
    ],
    hinhThucBaoTons: ["Bảo tồn tại chỗ", "Bảo tồn chuyển chỗ"],
    taiChinhs: [
      "Ngân sách nhà nước",
      "Hỗ trợ từ dự án quốc tế",
      "Buôn bán nguồn giống",
      "Trao đổi",
      "Thương mại",
      "Khác",
    ],
    form: {
      ten: null,
      dia_chi: null,
      co_quan_chu_quan: null,
      mo_ta: null,
      giay_phep: null,
      dien_tich: null,
      loai_hinh_id: null,
      loai_hinh_to_chuc_id: null,
      chuc_nang: null,
      don_vi_cap: null,
      ngay_cap: null,
      quan_ly_id: null,
      co_so_vat_chat: null,
      quy_trinh_ky_thuat: null,
      nhan_luc: null,
      tai_chinh: null,
      hinh_thuc_luu_giu: null,
      dien_tich_luu_giu: null,
      dia_diem_id: null,
      quan_huyen_id: null,
      ghi_chu: null,
      geom: null,
      files: [],
      co_quan_quan_ly: "",
      hinh_thuc_bao_ton: "",
      status: 'Đang đề xuất'
    },
    trangThai: ['Đang đề xuất', 'Được công nhận']
  }),
  methods: {
    back() {
      window.history.back();
    },
    resetForm() {
      this.loaiHinhSelect = null;
      this.loaiHinhToChucSelect = null;
      this.diaDiemSelect = null;
      this.quanLySelect = null;
      this.quanHuyenSelect = null;
      this.fileList = [];
      this.form = {
        ten: null,
        dia_chi: null,
        co_quan_chu_quan: null,
        mo_ta: null,
        giay_phep: null,
        dien_tich: null,
        loai_hinh_id: null,
        loai_hinh_to_chuc_id: null,
        chuc_nang: null,
        don_vi_cap: null,
        ngay_cap: null,
        quan_ly_id: null,
        co_so_vat_chat: null,
        quy_trinh_ky_thuat: null,
        nhan_luc: null,
        tai_chinh: null,
        hinh_thuc_luu_giu: null,
        dien_tich_luu_giu: null,
        dia_diem_id: null,
        quan_huyen_id: null,
        ghi_chu: null,
        geom: null,
        files: [],
        co_quan_quan_ly: "",
        hinh_thuc_bao_ton: "",
        status: 'Đang đề xuất'
      };
      this.$refs["uploadable-map"].removeFeature();
    },

    add() {
      this.$validator.validateAll().then((validate) => {
        this.form.loai_hinh_id = this.loaiHinhSelect
          ? this.loaiHinhSelect.id
          : null;
        this.form.loai_hinh_to_chuc_id = this.loaiHinhToChucSelect
          ? this.loaiHinhToChucSelect.id
          : null;
        this.form.dia_diem_id = this.diaDiemSelect
          ? this.diaDiemSelect.id
          : null;
        this.form.quan_ly_id = this.quanLySelect ? this.quanLySelect.id : null;
        this.form.quan_huyen_id = this.quanHuyenSelect
          ? this.quanHuyenSelect.id
          : null;
        this.form.status = this.form.status == 'Đang đề xuất' ? 'Proposed' : 'Designated'
        if (validate) {
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/cosobaoton/add", this.form)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Thêm mới thành công";
                window.location = '/admin/cosobaoton'
              }
              else {
                this.typeNotification = 1;
                this.textNotification = response.data.error[Object.keys(response.data.error)[0]];
              }
            })
            .catch(() => {
              console.log('ahihi')
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
