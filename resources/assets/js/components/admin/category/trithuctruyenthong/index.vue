<template>
  <div class="white-box p-0">
    <div class="page-aside">
      <components-notifications :typeInput="typeNotification" :textInput="textNotification"></components-notifications>
      <div class="loading-roles" v-if="loading">
        <components-loading-page></components-loading-page>
      </div>
      <div class="left-aside">
        <div class="scrollable">
          <div class="d-flex">
            <div style="fonts-size: 16px; font-weight: bold; flex: 1">
              Bộ lọc
            </div>
            <button type="button" class="btn-sm btn-info btn-rounded" v-if="showXoaBoLoc()"
              @click="deleteSearchFilter()">
              <span>Xóa bộ lọc</span>
            </button>
          </div>
          <hr />
          <ul class="list-style-none" style="background: white">
            <div @click="isStatus = -isStatus" data-toggle="collapse" data-target=".filter4"
              style="cursor: pointer; font-weight: normal" :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              ">
              Nhóm tri thức truyền thống
              <span :class="
                isStatus == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
              " style="float: right"></span>
            </div>
            <br />
            <li class="collapse filter4" style="margin-left: 15px" v-for="item in dataFilter.nhom" :key="item.id">
              <a @click="filterData(item.id, 'nhom_id')" style="cursor: pointer" :style="
                filter.phan_loai_id == item.id
                  ? 'color: #408c5b; font-weight: bold'
                  : ''
              ">
                {{ item.name }}
                <span>{{ item.so_luong }}</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="right-aside">
        <div class="d-flex">
          <div style="flex: 1; font-size: 18px; font-weight: bold">
            Danh sách tri thức truyền thống
          </div>
          <div class="d-flex">
            <input class="form-control" placeholder="Tìm kiếm" v-model="search" @keyup.enter="timKiem" />
          </div>
        </div>
        <div>
          <button type="button" class="btn btn-info btn-rounded" data-bs-toggle="modal" data-bs-target="#exampleModal"
            data-bs-placement="top" title="Thêm mới" @click="showFormAdd">
            <i class="fas fa-plus"></i> Thêm mới
          </button>
        </div>
        <hr />
        <div class="scrollable">
          <div class="table-responsive">
            <table class="
                table table-hover
                contact-list
                footable footable-1 footable-paging footable-paging-center
                breakpoint-lg
              ">
              <thead>
                <tr class="footable-header">
                  <th style="width: 60px">STT</th>
                  <th style="display: table-cell; min-width: 70px">
                    Tên tri thức truyền thống
                  </th>
                  <th style="display: table-cell">
                    Nhóm tri thức truyền thống
                  </th>
                  <th style="display: table-cell; min-width: 70px">
                    Số nguồn gen
                  </th>
                  <th style="display: table-cell; min-width: 70px">
                    Nơi lưu giữ
                  </th>
                  <th width="100" style="text-align: center">Hành động</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="dataTable && dataTable.length == 0">
                  <td colspan="6" class="emptyInfomation">
                    <h5>Dữ liệu đang được cập nhật, bạn vui lòng truy cập lại sau</h5>
                  </td>
                </tr>
                <tr v-for="(item, index) in dataTable" :key="index" style="cursor: context-menu">
                  <td>
                    <!-- <span class="text-center-td">{{(page - 1)*10 + index + 1}}</span> -->
                    <div>{{ index + 1 }}</div>
                  </td>
                  <td>
                    <div style="white-space: initial">
                      <div style="font-size: 16px; font-weight: 400; max-width: 320px; overflow:clip">
                        {{ item.ten }}
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>{{ item.nhom ? item.nhom.name : "" }}</div>
                  </td>
                  <td>
                    {{ item.nguon_gen ? item.nguon_gen.length : 0 }}
                  </td>
                  <td>
                    <div>
                      {{ item.noi_luu_giu }}
                    </div>
                  </td>
                  <td>
                    <button type="button" title="Chỉnh sửa" @click="showFormEdit(item)" class="
                        btn btn-info btn-outline btn-circle btn-small
                        m-r-5
                      ">
                      <i class="ti-pencil-alt"></i>
                    </button>
                    <button type="button" title="Xóa" @click="showXoa(item)" class="
                        btn btn-info btn-outline btn-circle btn-small
                        m-r-5
                      ">
                      <i class="ti-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <td colspan="6">
            <paginate v-model="page" :page-count="pageCount" :page-range="3" :margin-pages="2"
              :click-handler="clickCallback" :prev-text="'‹‹'" :next-text="'››'" :container-class="'pagination'"
              :page-class="'page-item'"></paginate>
          </td>
        </div>
      </div>
      <modal v-if="showForm" @close="showForm = false" :width="1200" :title="
        edit
          ? 'Cập nhật tri thức truyền thống'
          : 'Thêm mới tri thức truyền thống'
      ">
        <div slot="body">
          <div class="d-flex">
            <div style="flex: 1; padding-right: 30px">
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ten" class="form-label">Tên tri thức truyền thống
                    <span class="red-dot">*</span></label>
                  <input type="text" class="form-control" id="ten" name="tenvanban" v-model="form.ten"
                    v-validate="'required'" />
                  <span v-show="errors.has('tenvanban')" class="help is-danger" style="color: red">Tên không thể bỏ
                    trống</span>
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label">Cấp giấy chúng nhận <span class="red-dot"></span></label>
                  <input type="text" class="form-control" placeholder="Cấp giấy chúng nhận"
                    v-model="form.cap_giay_chung_nhan" />
                </div>
              </div>
              <br />
              <div style="flex: 1">
                <label for="ten" class="form-label">Nhóm <span class="red-dot">*</span></label>
                <multiselect v-model="nhomSelect" :options="nhomtrithucs" :searchable="true" track-by="id" label="name"
                  placeholder="Nhóm tri thức truyến thống" :show-labels="false" name="nhom" v-validate="'required'">
                </multiselect>
                <span v-show="errors.has('nhom')" class="help is-danger" style="color: red">Tên không thể bỏ
                  trống</span>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ten" class="form-label">Tổ chức cá nhân lưu giữ <span class="red-dot"></span></label>
                  <input type="text" class="form-control" v-model="form.nguoi_luu_giu"
                    placeholder="Nhập tên, tổ chức cá nhân lưu giữ" />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label">Nơi lưu giữ <span class="red-dot"></span></label>
                  <input type="text" class="form-control" placeholder="Nơi lưu giữ" v-model="form.noi_luu_giu" />
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1">
                  <label for="ngayhieuluc" class="form-label">Mô tả công dụng
                  </label>
                  <input class="form-control" v-model="form.mo_ta" />
                </div>
              </div>
              <br />
            </div>
            <div style="flex: 1; padding-left: 15px">
              <div>
                <label for="nhomchithi" class="form-label">Nguồn gen:
                  <span style="color: red">{{
                      nguonGenSelect && nguonGenSelect.length == 1
                        ? 1
                        : nguonGenSelect && nguonGenSelect.length > 1
                          ? "Nhiều"
                          : 0
                  }}</span>
                  <span v-if="nguonGenSelect && nguonGenSelect.length > 1">({{ nguonGenSelect.length }})</span><span
                    class="red-dot"></span></label>
                <multiselect v-model="nguonGenSelect" :options="nguonGens" :multiple="true" :searchable="true"
                  track-by="id" label="ten" placeholder="Chọn nguồn gen liên quan" :show-labels="false"
                  :loading="isLoadingSelect" :internal-search="false" @search-change="getNguonGen">
                  <template slot-scope="props" slot="option">
                    <div>
                      <span>{{ props.option.ten }}</span>
                      <span style="color: gray; font-size: 14px; float: right">{{ props.option.ten_khoa_hoc }}</span>
                    </div>
                  </template>
                </multiselect>
              </div>
              <br />
              <div>
                <label for="nhomchithi" class="form-label">Ghi chú <span class="red-dot"></span></label>
                <textarea class="form-control" rows="6" v-model="form.ghi_chu"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div slot="footer">
          <button type="button" class="btn btn-success" v-if="!edit" @click="add" :disabled="disableButton">
            <i class="fas fa-plus"></i>
            Thêm mới
          </button>
          <button type="button" class="btn btn-success" v-else @click="update">
            <i class="fas fa-check"></i>
            Cập nhật
          </button>
        </div>
      </modal>
      <modal v-if="confirmDelete" @close="confirmDelete = false" title="Xóa dữ liệu">
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
import Multiselect from "../../../vue-multiselect";

export default {
  props: ["hinhthucs", "tinhthanhs", "phancaps", "nhomtrithucs"],
  components: { ValidationProvider, Multiselect },
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
    nhomSelect: null,
    nguonGenSelect: null,
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
      nhom_id: null,
      nguoi_luu_giu: null,
      noi_luu_giu: null,
      mo_ta: null,
      cap_giay_chung_nhan: null,
      ghi_chu: null,
    },
    danhMucs: {},
    isStatus: 1,
    isBanHanh: 1,
    isHieuLuc: 1,
    isThamGia: 1,
    searchDatasetType: null,
    filter: {
      nhom_id: null,
    },
  }),
  mounted() {
    this.getData();
    this.getChiSo();
    this.getNguonGen();
  },

  methods: {
    showXoa(data) {
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa tri thức truyền thống " +
        data.ten +
        " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/trithuctruyenthong/delete/";
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
      this.nhomSelect = null;
      this.nguonGenSelect = null;
      this.form = {
        ten: null,
        nhom_id: null,
        nguoi_luu_giu: null,
        noi_luu_giu: null,
        mo_ta: null,
        cap_giay_chung_nhan: null,
        ghi_chu: null,
      };
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
      this.nhomSelect = Object.assign({}, data.nhom);
      this.nguonGenSelect = data.nguon_gen ? [...data.nguon_gen] : null;
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        this.form.nguon_gen = this.nguonGenSelect ? this.nguonGenSelect : [];
        this.form.nhom_id = this.nhomSelect ? this.nhomSelect.id : null;
        if (validate) {
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/trithuctruyenthong/add", this.form)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Thêm mới thành công";
                this.getData();
                this.getChiSo();
              }
              else {
                this.action = true;
                this.typeNotification = 1;
                this.textNotification = response.data.error[Object.keys(response.data.error)[0]];
              }
            })
            .catch(() => {
              console.log(1)
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
        this.form.nguon_gen = this.nguonGenSelect ? this.nguonGenSelect : [];
        this.form.nhom_id = this.nhomSelect ? this.nhomSelect.id : null;
        if (validate) {
          this.disableButton = true;
          axios
            .post(
              env.endpointhttp + "admin/trithuctruyenthong/update",
              this.form
            )
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Cập nhật thành công";
                this.getData();
                this.getChiSo();
              }
              else {
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
    getData() {
      axios
        .get(env.endpointhttp + "admin/trithuctruyenthong/get", {
          params: {
            page: this.page,
            search: this.search,
            nhom_id: this.filter.nhom_id,
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
  },
};
</script>

<style>
/* .multiselect {
  position: sticky !important;
} */
</style>