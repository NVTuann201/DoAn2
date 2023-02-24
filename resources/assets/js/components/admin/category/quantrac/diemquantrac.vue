<template>
  <div class="white-box p-0">
    <div class="page-aside">
      <components-notifications
        :typeInput="typeNotification"
        :textInput="textNotification"
      ></components-notifications>
      <div class="loading-roles" v-if="loading">
        <components-loading-page></components-loading-page>
      </div>
      <div>
        <div class="d-flex">
          <div style="flex: 1; font-size: 18px; font-weight: bold">
            Danh sách điểm quan trắc
          </div>
          <div class="d-flex">
            <input
              class="form-control"
              placeholder="Tìm kiếm"
              v-model="search"
              @keyup.enter="timKiem"
            />
          </div>
        </div>
        <div>
          <button
            type="button"
            class="btn btn-info btn-rounded"
            data-bs-toggle="modal"
            data-bs-target="#exampleModal"
            data-bs-placement="top"
            title="Thêm mới"
            @click="showFormAdd"
          >
            {{ $t("admin.buttons.add") }}
          </button>
        </div>
        <hr />
        <div class="scrollable">
          <div class="table-responsive">
            <table
              class="table table-hover contact-list footable footable-1 footable-paging footable-paging-center breakpoint-lg"
            >
              <thead>
                <tr class="footable-header">
                  <th style="width: 60px">STT</th>
                  <th style="display: table-cell; min-width: 70px">
                    Tên Điểm quan trắc
                  </th>
                  <th style="display: table-cell">Loại hình</th>
                  <th style="display: table-cell; min-width: 70px">Kết quả</th>
                  <th style="display: table-cell; min-width: 70px">Địa điểm</th>

                  <th width="100" style="text-align: center">Hành động</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="dataTable && dataTable.length == 0">
                  <td colspan="6" class="emptyInfomation">
                    <h5>Dữ liệu đang được cập nhật, bạn vui lòng truy cập lại sau</h5>
                  </td>
                </tr>
                <tr
                  v-for="(item, index) in dataTable"
                  :key="index"
                  style="cursor: context-menu"
                >
                  <td>
                    <!-- <span class="text-center-td">{{(page - 1)*10 + index + 1}}</span> -->
                    <div>{{ index + 1 }}</div>
                  </td>
                  <td>
                    <div style="white-space: initial">
                      <div>
                        <div style="font-size: 16px; font-weight: 400">
                          {{ item.ten }}
                        </div>
                        <div>Ký hiệu mẫu {{ item.ky_hieu_mau }}</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>{{ item.loai_hinh ? item.loai_hinh.ten : "" }}</div>
                  </td>
                  <td>
                    <div>{{ item.ket_qua }}</div>
                  </td>
                  <td>
                    <div>
                      {{ item.khu_bao_ton ? item.khu_bao_ton.orig_name : "" }}
                    </div>
                  </td>
                  <td>
                    <button
                      type="button"
                      title="Chỉnh sửa"
                      @click="showFormEdit(item)"
                      class="btn btn-info btn-outline btn-circle btn-small m-r-5"
                    >
                      <i class="ti-pencil-alt"></i>
                    </button>
                    <button
                      type="button"
                      title="Xóa"
                      @click="showXoa(item)"
                      class="btn btn-info btn-outline btn-circle btn-small m-r-5"
                    >
                      <i class="ti-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <td colspan="6">
            <paginate
              v-model="page"
              :page-count="pageCount"
              :page-range="3"
              :margin-pages="2"
              :click-handler="clickCallback"
              :prev-text="'‹‹'"
              :next-text="'››'"
              :container-class="'pagination'"
              :page-class="'page-item'"
            ></paginate>
          </td>
        </div>
      </div>
      <modal
        v-if="showForm"
        @close="showForm = false"
        :width="1200"
        :title="
          edit ? 'Cập nhật địa điểm quan trắc' : 'Thêm địa điểm quan trắc'
        "
      >
        <div slot="body">
          <div class="d-flex">
            <div style="padding-right: 15px; flex: 1">
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Tên điểm quan trắc<span class="red-dot">*</span>
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    name="tendichvu"
                    v-model="form.ten"
                    v-validate="'required'"
                  />
                  <span
                    v-show="errors.has('tendichvu')"
                    class="help is-danger"
                    style="color: red"
                    >Tên điểm quan trắc không thể bỏ trống</span
                  >
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Ký hiệu mẫu<span class="red-dot"></span>
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ky_hieu_mau"
                  />
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Loại hình quan trắc<span class="red-dot"></span>
                  </label>
                  <multiselect
                    v-model="loaiHinhSelect"
                    :options="loaiHinhs"
                    :searchable="true"
                    track-by="id"
                    label="ten"
                    placeholder="Chọn Địa điểm"
                    :show-labels="false"
                  >
                  </multiselect>
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Thời gian<span class="red-dot"></span>
                  </label>
                  <input
                    type="date"
                    class="form-control"
                    v-model="form.thoi_gian"
                  />
                </div>
              </div>
              <br />
              <div style="flex: 1; padding-right: 10px">
                <label for="ten" class="form-label"
                  >Địa điểm <span class="red-dot"></span
                ></label>
                <multiselect
                  v-model="diaDiemSelect"
                  :options="khuBaoTons"
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
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Kết quả
                  </label>
                  <input class="form-control" v-model="form.ket_qua" />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label">Đơn vị quan trắc</label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.don_vi_quan_trac"
                  />
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Mô tả
                  </label>
                  <input class="form-control" v-model="form.mo_ta" />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label">Ghi chú</label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ghi_chu"
                  />
                </div>
              </div>
            </div>
            <div style="padding-left: 15px; flex: 1">
              <div style="flex: 1">
                <div class="d-flex">
                  <div style="flex: 1">
                    <label for="ten" class="form-label">Không gian</label>
                  </div>
                </div>
                <UploadableMap
                  ref="uploadable-map"
                  @change="form.geom = $event"
                  :geometry="form.geom"
                  :height="250"
                />
              </div>
            </div>
          </div>
        </div>
        <div slot="footer">
          <button
            type="button"
            class="btn btn-success"
            v-if="!edit"
            @click="add"
            :disabled="disableButton"
          >
            {{ $t("admin.buttons.add") }}
          </button>
          <button
            type="button"
            class="btn btn-success"
            v-else
            @click="update"
            :disabled="disableButton"
          >
            {{ $t("admin.buttons.edit") }}
          </button>
        </div>
      </modal>

      <modal
        v-if="confirmDelete"
        @close="confirmDelete = false"
        title="Xóa dữ liệu"
      >
        <div slot="body">
          {{ contentDelete }}
        </div>
        <div slot="footer">
          <button type="button" class="btn btn-danger" @click="xoaDuLieu()">
            {{ $t("admin.buttons.delete") }}
          </button>
        </div>
      </modal>
    </div>
  </div>
</template>

<script>
import { ValidationProvider } from "vee-validate";
import * as env from "../../../../env.js";
import Multiselect from "../../../vue-multiselect";
import UploadableMap from "../../../UploadableMap.vue";

export default {
  components: { ValidationProvider, Multiselect, UploadableMap },
  props: ["diadiems"],
  data: () => ({
    search: null,
    typeNotification: null,
    textNotification: null,
    loading: false,
    showForm: false,
    disableButton: false,
    page: 1,
    pageCount: 0,
    edit: false,
    tienTrinhUpload: null,
    fileList: [],
    dataTable: [],
    confirmDelete: false,
    idDelete: null,
    delLink: null,
    contentDelete: "Bạn có chắc chắn muốn xóa dữ liệu này?",
    dataFilter: {},
    loaiHinhs: [],
    khuBaoTons: [],
    loaiHinhSelect: null,
    diaDiemSelect: null,
    form: {
      ten: null,
      ky_hieu_mau: null,
      loai_hinh_id: null,
      thoi_gian: null,
      khu_bao_ton_id: null,
      ket_qua: null,
      don_vi_quan_trac: null,
      mo_ta: null,
      ghi_chu: null,
      geom: null,
    },
    danhMucs: {},
    isStatus: 1,
    isBanHanh: 1,
    isHieuLuc: 1,
    isThamGia: 1,
    searchDatasetType: null,
    filter: {
      nguon_kinh_phi_id: null,
      ky_hieu_mau: null,
      loai_hinh_id: null,
    },
  }),
  mounted() {
    this.getData();
    this.getChiSo();
    this.getLoaHinh();
    this.getKhuBaoTon();
  },

  methods: {
    resetForm() {
      this.diaDiemSelect = null;
      this.loaiHinhSelect = null;
      this.form = {
        ten: null,
        ky_hieu_mau: null,
        loai_hinh_id: null,
        thoi_gian: null,
        khu_bao_ton_id: null,
        ket_qua: null,
        don_vi_quan_trac: null,
        mo_ta: null,
        ghi_chu: null,
        geom: null,
      };
      if (this.$refs["uploadable-map"])
        this.$refs["uploadable-map"].removeFeature();
    },
    getLoaHinh() {
      axios
        .get(env.endpointhttp + "admin/quantrac/loaihinh", {
          params: {
            perPage: 999999,
          },
        })
        .then((res) => {
          this.loaiHinhs = res.data.data;
        });
    },
    getKhuBaoTon() {
      axios.get(env.endpointhttp + "admin/quantrac/khubaoton").then((res) => {
        this.khuBaoTons = res.data;
      });
    },
    showXoa(data) {
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa điểm quan trắc " + data.ten + " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/quantrac/diemquantrac/delete/";
    },
    xoaDuLieu() {
      axios
        .delete(env.endpointhttp + this.delLink + this.idDelete)
        .then(() => {
          this.typeNotification = 2;
          this.textNotification = "Xóa thành công";
          this.getData();
        })
        .catch(() => {
          this.typeNotification = 1;
          this.textNotification = "Vui lòng xóa các dữ liệu trực thuộc";
        })
        .finally(() => {
          this.confirmDelete = false;
        });
    },
    showFormAdd() {
      this.resetForm();
      this.edit = false;
      this.showForm = true;
    },
    showFormEdit(data) {
      Object.assign(this.form, data);
      this.edit = true;
      this.showForm = true;
      this.form.id = data.id;
      this.diaDiemSelect = Object.assign({}, data.khu_bao_ton);
      this.loaiHinhSelect = Object.assign({}, data.loai_hinh);
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        this.form.khu_bao_ton_id = this.diaDiemSelect
          ? this.diaDiemSelect.id
          : null;
        this.form.loai_hinh_id = this.loaiHinhSelect
          ? this.loaiHinhSelect.id
          : null;
        if (validate) {
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/quantrac/diemquantrac", this.form)
            .then(() => {
              this.typeNotification = 2;
              this.textNotification = "Thêm mới thành công";
              this.getData();
            })
            .catch(() => {
              this.typeNotification = 1;
              this.textNotification = "Thêm mới thất bại";
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
    update() {
      this.$validator.validateAll().then((validate) => {
        this.form.khu_bao_ton_id = this.diaDiemSelect
          ? this.diaDiemSelect.id
          : null;
        this.form.loai_hinh_id = this.loaiHinhSelect
          ? this.loaiHinhSelect.id
          : null;
        if (validate) {
          axios
            .put(env.endpointhttp + "admin/quantrac/diemquantrac", this.form)
            .then(() => {
              this.typeNotification = 2;
              this.textNotification = "Cập nhật thành công";
              this.getData();
              this.getChiSo();
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
            });
        }
      });
    },
    filterTable(phan_loai_id) {
      if (this.filter.phan_loai_id == phan_loai_id) {
        this.filter.phan_loai_id = null;
      } else {
        this.filter.phan_loai_id = phan_loai_id;
      }
      this.timKiem();
    },
    getData() {
      axios
        .get(env.endpointhttp + "admin/quantrac/diemquantrac", {
          params: {
            page: this.page,
            search: this.search,
          },
        })
        .then((res) => {
          this.dataTable = res.data.data;
          this.page = res.data.current_page;
          this.pageCount = res.data.last_page;
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
        let File_id = await axios.post("uploadfiles", data, {
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
    clickCallback(pageNum) {
      this.page = pageNum;
      this.getData();
    },
    timKiem() {
      this.page = 1;
      this.getData();
    },
    taiTapTin(data) {
      window.open("/" + data.disk, "_blank");
    },
    showXoaBoLoc() {
      if (this.filter.phan_loai_id == null) {
        return false;
      } else return true;
    },
    deleteSearchFilter() {
      this.filter = {
        phan_loai_id: null,
      };
      this.showXoaBoLoc();
      this.timKiem();
    },
    getChiSo() {
      axios
        .get(env.endpointhttp + "admin/dichvuhesinhthai/chiso")
        .then((res) => {
          this.dataFilter = res.data;
        });
    },
  },
};
</script>

<style></style>
