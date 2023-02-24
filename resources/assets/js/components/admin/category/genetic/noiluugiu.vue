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
      <div style="padding: 15px">
        <div class="d-flex">
          <div style="flex: 1; font-size: 18px; font-weight: bold">
            Danh sách nơi lưu giữ nguồn gen
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
            <i class="fas fa-plus"></i> Thêm mới
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
                    Tên nơi lưu giữ
                  </th>
                  <th style="display: table-cell">Người sở hữu</th>
                  <th style="display: table-cell; min-width: 70px">
                    Quận huyện
                  </th>
                  <th style="display: table-cell; min-width: 70px">Thôn xã</th>
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
                      <div style="font-size: 16px; font-weight: 400">
                        {{ item.ten }}
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>{{ item.nguoi_so_huu }}</div>
                  </td>
                  <td>
                    <div v-if="item.quan_huyens && item.quan_huyens.length">
                      <div v-for="item in item.quan_huyens" :key="item.id">
                        {{ item.name_vietnamese }},
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>
                      {{ item.thon_xa }}
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
        :height="900"
        :title="edit ? 'Cập nhật nơi lưu trữ' : 'Thêm mới nơi lưu trữ'"
      >
        <div slot="body">
          <div class="d-flex">
            <div style="flex: 1; padding-right: 30px">
              <div class="d-flex">
                <div style="flex: 1">
                  <label for="ten" class="form-label"
                    >Tên nơi lưu giữ <span class="red-dot">*</span></label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="ten"
                    name="tenvanban"
                    v-model="form.ten"
                    v-validate="'required'"
                  />
                  <span
                    v-show="errors.has('tenvanban')"
                    class="help is-danger"
                    style="color: red"
                    >Tên không thể bỏ trống</span
                  >
                </div>
              </div>
              <!-- <br />
              <div style="flex: 1">
                <label for="ten" class="form-label"
                  >Tỉnh thành <span class="red-dot"></span
                ></label>
                <multiselect
                  v-model="tinhThanhSelect"
                  :options="tinhThanhs"
                  :searchable="true"
                  :multiple="true"
                  track-by="id"
                  label="name_vietnamese"
                  placeholder="Chọn tỉnh thành"
                  :show-labels="false"
                  @input="changeTinhThanh()"
                >
                </multiselect>
              </div> -->
              <br />
              <div style="flex: 1">
                <label for="ten" class="form-label"
                  >Quận huyện <span class="red-dot"></span
                ></label>
                <multiselect
                  v-model="quanHuyenSelect"
                  :options="quanHuyens"
                  track-by="id"
                  :multiple="true"
                  :searchable="true"
                  label="name_vietnamese"
                  placeholder="Chọn quận huyện"
                  :show-labels="false"
                >
                </multiselect>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1">
                  <label for="ngayhieuluc" class="form-label">Thôn xã </label>
                  <input class="form-control" v-model="form.thon_xa" />
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; margin-right: 10px">
                  <label for="ngayhieuluc" class="form-label"
                    >Đối tượng bảo tồn</label
                  >
                  <multiselect
                    v-model="loaiDoiTuongSelect"
                    :options="doiTuongBaoTons"
                    :searchable="true"
                    track-by="name"
                    label="name"
                    :show-labels="false"
                    placeholder="Chọn một đối tượng bảo tồn"
                    @input="changeDoiTuong()"
                  ></multiselect>
                </div>
                <div
                  style="flex: 1; margin-left: 10px"
                  v-if="loaiDoiTuongSelect"
                >
                  <div
                    v-if="
                      loaiDoiTuongSelect &&
                      loaiDoiTuongSelect.code == 'khu_bao_ton'
                    "
                  >
                    <label class="form-label">
                      Khu bảo tồn
                      <span style="color: red; margin-left: 5px">*</span>
                    </label>
                    <multiselect
                      v-model="doiTuongSelect"
                      :options="dataDoiTuong.khu_bao_ton"
                      :searchable="true"
                      track-by="id"
                      label="name"
                      :show-labels="false"
                      placeholder="Mời bạn nhập khu bảo tồn"
                      v-validate="'required'"
                      name="protectedArea"
                    ></multiselect>
                    <span
                      v-show="errors.has('protectedArea')"
                      class="help is-danger"
                      style="color: red"
                    >
                      Trường khu bảo tồn là trường bắt buộc
                    </span>
                  </div>
                  <div
                    v-if="
                      loaiDoiTuongSelect &&
                      loaiDoiTuongSelect.code == 'co_so_bao_ton'
                    "
                  >
                    <label class="form-label">
                      Cơ sở bảo tồn
                      <span style="color: red; margin-left: 5px">*</span>
                    </label>
                    <multiselect
                      v-model="doiTuongSelect"
                      :options="dataDoiTuong.co_so_bao_ton"
                      :searchable="true"
                      track-by="id"
                      label="ten"
                      :show-labels="false"
                      placeholder="Mời bạn nhập cơ sở bảo tồn"
                      v-validate="'required'"
                      name="protectedArea"
                    ></multiselect>
                    <span
                      v-show="errors.has('protectedArea')"
                      class="help is-danger"
                      style="color: red"
                    >
                      Trường co sở bảo tồn là trường bắt buộc
                    </span>
                  </div>
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ten" class="form-label"
                    >Người sở hữu<span class="red-dot"></span
                  ></label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.nguoi_so_huu"
                    placeholder="Cá nhân, tổ chức sở hữu"
                  />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label"
                    >Thông tin liên hệ <span class="red-dot"></span
                  ></label>
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Thông tin liên hệ"
                    v-model="form.thong_tin_lien_he"
                  />
                </div>
              </div>
              <br />
              <div style="flex: 1">
                <label class="form-label"
                  >Ghi chú <span class="red-dot"></span
                ></label>
                <textarea
                  class="form-control"
                  rows="2"
                  v-model="form.ghi_chu"
                />
              </div>

              <br />
            </div>
            <div style="flex: 1; padding-left: 15px">
              <br />
              <div>
                <div class="d-flex" style="padding-bottom: 20px">
                  <label for="nhomchithi" class="form-label" style="flex: 1"
                    >Không gian<span class="red-dot"></span
                  ></label>
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
            <i class="fas fa-plus"></i>
            Thêm mới
          </button>
          <button type="button" class="btn btn-success" v-else @click="update">
            <i class="fas fa-check"></i>
            Cập nhật
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
            <i class="fas fa-trash-alt"></i>
            Xóa
          </button>
        </div>
      </modal>
    </div>
  </div>
</template>

<script>
import { ValidationProvider } from "vee-validate";
import * as env from "../../../../env.js";
import * as routes from "../../../../routes.js";

import Multiselect from "../../../vue-multiselect";
import FilterSearch from "@/modules/filter-group/filter-search.vue";

import { doiTuongBaoTons } from "../../../../doituongbaoton.js";
import UploadableMap from "../../../UploadableMap.vue";

export default {
  components: {
    ValidationProvider,
    Multiselect,
    FilterSearch,
    UploadableMap,
  },
  data: () => ({
    search: null,
    disableButton: false,
    typeNotification: null,
    textNotification: null,
    loading: false,
    showForm: false,
    page: 1,
    pageCount: 0,
    edit: false,
    tienTrinhUpload: null,
    fileList: [],
    tinhThanhSelect: null,
    quanHuyenSelect: null,
    dataFilter: {},
    dataTable: [],
    confirmDelete: false,
    idDelete: null,
    delLink: null,
    contentDelete: "Bạn có chắc chắn muốn xóa dữ liệu này?",
    isLoadingSelect: false,
    nguonGens: [],
    form: {
      ten: null,
      tinh_thanh: [],
      quan_huyen: [],
      thon_xa: null,
      nguoi_so_huu: null,
      thong_tin_lien_he: null,
      ghi_chu: null,
      loai_doi_tuong: null,
      doi_tuong_id: null,
      geom: null,
    },
    tinhThanhs: [],
    quanHuyens: [],
    danhMucs: {},
    isStatus: 1,
    isBanHanh: 1,
    isHieuLuc: 1,
    isThamGia: 1,
    searchDatasetType: null,
    quanHuyenFilter: [],
    filter: {
      nhom_id: null,
    },
    paramsProvince: {},
    filter_subloc: [],
    loaiDoiTuongSelect: null,
    doiTuongSelect: null,
    doiTuongBaoTons,
    dataDoiTuong: {
      co_so_bao_ton: [],
      khu_bao_ton: [],
    },
  }),
  computed: {
    gettopsubloc: function () {
      return env.endpointhttp + routes.gettopsubloc;
    },
    getsublogs: function () {
      return env.endpointhttp + routes.getsubloc;
    },
  },
  mounted() {
    this.getData();
    this.getChiSo();
    this.getNguonGen();
    this.getDiaGioi();
    this.getDoiTuongBaoTon();
  },

  methods: {
    showXoa(data) {
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa nơi lưu trữ nguồn gen " +
        data.ten +
        " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/noiluugiu/delete/";
    },
    changeDoiTuong() {
      this.doiTuongSelect = null;
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
    resetForm() {
      this.tinhThanhSelect = null;
      this.quanHuyenSelect = null;
      this.doiTuongSelect = null;
      this.loaiDoiTuongSelect = null;
      this.form = {
        ten: null,
        tinh_thanh: [],
        quan_huyen: [],
        thon_xa: null,
        nguoi_so_huu: null,
        thong_tin_lien_he: null,
        ghi_chu: null,
        loai_doi_tuong: null,
        doi_tuong_id: null,
        geom: null,
      };
      if (this.$refs["uploadable-map"])
        this.$refs["uploadable-map"].removeFeature();
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
      this.quanHuyenSelect = [...data.quan_huyens];
      this.loaiDoiTuongSelect = data.loai_doi_tuong
        ? this.doiTuongBaoTons.find((el) => el.code == data.loai_doi_tuong)
        : null;
      this.doiTuongSelect =
        data.loai_doi_tuong && data.doi_tuong_id
          ? this.dataDoiTuong[data.loai_doi_tuong].find(
              (el) => el.id == data.doi_tuong_id
            )
          : null;
    },
    setFormTinhThanhQuanHuyen() {
      this.form.quan_huyen = [];
      if (this.quanHuyenSelect && this.quanHuyenSelect.length) {
        this.quanHuyenSelect.forEach((el) => {
          this.form.quan_huyen.push(el.id);
        });
      }
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          this.disableButton = true;
          this.setFormTinhThanhQuanHuyen();
          this.form.loai_doi_tuong = this.loaiDoiTuongSelect
            ? this.loaiDoiTuongSelect.code
            : null;
          this.form.doi_tuong_id = this.doiTuongSelect
            ? this.doiTuongSelect.id
            : null;
          axios
            .post(env.endpointhttp + "admin/noiluugiu/add", this.form)
            .then(() => {
              this.typeNotification = 2;
              this.textNotification = "Thêm mới thành công";
              this.getData();
              this.getChiSo();
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
        if (validate) {
          this.setFormTinhThanhQuanHuyen();
          this.disableButton = true;
          this.form.loai_doi_tuong = this.loaiDoiTuongSelect
            ? this.loaiDoiTuongSelect.code
            : null;
          this.form.doi_tuong_id = this.doiTuongSelect
            ? this.doiTuongSelect.id
            : null;
          axios
            .post(env.endpointhttp + "admin/noiluugiu/edit", this.form)
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
              this.disableButton = false;
            });
        }
      });
    },
    getData() {
      axios
        .get(env.endpointhttp + "admin/noiluugiu/get", {
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
    getChiSo() {
      axios
        .get(env.endpointhttp + "admin/trithuctruyenthong/chiso")
        .then((res) => {
          this.dataFilter = res.data;
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
    getNguonGen(query = "") {
      if (typeof window.LIT !== "undefined") {
        clearTimeout(window.LIT);
      }
      window.LIT = setTimeout(() => {
        this.isLoadingSelect = true;
        axios
          .get(
            env.endpointhttp +
              "admin/trithuctruyenthong/searchgen?search=" +
              query
          )
          .then((response) => {
            this.nguonGens = response.data;
            this.isLoadingSelect = false;
          })
          .catch(() => {
            this.isLoadingSelect = false;
          });
      }, 500);
    },
    showXoaBoLoc() {
      if (this.filter.nhom_id == null) {
        return false;
      } else return true;
    },
    deleteSearchFilter() {
      this.filter = {
        nhom_id: null,
      };
      this.showXoaBoLoc();
      this.timKiem();
    },
    filterData(id, type) {
      if (this.filter[type] === id) {
        this.filter[type] = null;
      } else {
        this.filter[type] = id;
      }
      this.timKiem();
    },
    getDoiTuongBaoTon() {
      axios.get(env.endpointhttp + "admin/doituongbaoton").then((response) => {
        this.dataDoiTuong = response.data;
      });
    },
    getDiaGioi() {
      axios.get(env.endpointhttp + "admin/noiluugiu/diagioi").then((res) => {
        this.quanHuyens = res.data.quan_huyen;
      });
    },
  },
};
</script>

<style>
/* .multiselect {
  position: sticky !important;
} */
</style>
