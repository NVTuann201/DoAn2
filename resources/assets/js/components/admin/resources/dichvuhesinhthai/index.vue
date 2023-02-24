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
          <ul>
            <div @click="isStatus = -isStatus" data-toggle="collapse" data-target=".filter4"
              style="cursor: pointer; font-weight: normal">
              Phân loại dịch vụ
              <span :class="
                isStatus == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
              " style="float: right"></span>
            </div>
            <br />
            <li class="collapse filter4" style="margin-bottom: 10px" v-for="item in dataFilter.phan_loai"
              :key="item.id">
              <a @click="filterTable(item.id)" style="cursor: pointer" :style="
                filter.phan_loai_id == item.id
                  ? 'color: #408c5b; font-weight: bold'
                  : 'color: black'
              ">
                {{ item.name }}
                <span style="float: right">{{ item.so_luong }}</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="right-aside">
        <div class="d-flex">
          <div style="flex: 1; font-size: 18px; font-weight: bold">
            Danh sách dịch vụ hệ sinh thái
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
                    Tên dịch vụ
                  </th>
                  <th style="display: table-cell">Phân loại</th>
                  <th style="display: table-cell; min-width: 70px">
                    Hệ sinh thái
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
                      <div>
                        <div style="font-size: 16px; font-weight: 400">
                          {{ item.ten }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>{{ item.phan_loai ? item.phan_loai.name : "" }}</div>
                  </td>
                  <td>
                    <div v-if="item.he_sinh_thai && item.he_sinh_thai.length">
                      <div v-for="it in item.he_sinh_thai" :key="it.id">
                        {{ it.ten }}
                      </div>
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
      <modal v-if="showForm" @close="showForm = false" :width="800" :title="
        edit ? 'Cập nhật dịch vụ hệ sinh thái' : 'Thêm dịch vụ hệ sinh thái'
      ">
        <div slot="body">
          <div class="d-flex">
            <div style="flex: 1; padding-right: 15px">
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="soquocgiathamgia" class="form-label">Tên dịch vụ <span class="red-dot">*</span>
                  </label>
                  <input type="text" class="form-control" name="tendichvu" v-model="form.ten" v-validate="'required'" />
                  <span v-show="errors.has('tendichvu')" class="help is-danger" style="color: red">Tên dịch vụ không thể
                    bỏ trống</span>
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label">Phân loại <span class="red-dot">*</span></label>
                  <multiselect v-model="phanLoaiSelect" :options="danhmucs.phan_loai" :searchable="true" track-by="id"
                    label="name" name="phanloai" placeholder="Phân loại" :show-labels="false" v-validate="'required'">
                  </multiselect>
                  <span v-show="errors.has('phanloai')" class="help is-danger" style="color: red">Phân loại không thể bỏ
                    trống</span>
                </div>
              </div>
              <br />
              <div style="flex: 1; padding-right: 10px">
                <label for="ten" class="form-label">Hệ sinh thái <span class="red-dot"></span></label>
                <multiselect v-model="heSinhThaiSelect" :options="danhmucs.he_sinh_thai" :searchable="true"
                  :multiple="true" track-by="id" label="ten" name="nguonkinhphi" placeholder="Chọn hệ sinh thái"
                  :show-labels="false">
                  <template slot-scope="props" slot="option">
                    <div>
                      <span style="">{{ props.option.ten }}</span>
                      <span style="font-size: 14px; float: right; color: gray">{{
                      props.option.he_sinh_thai
                      ? props.option.he_sinh_thai.name
                      : ""
                      }}</span>
                    </div>
                  </template>
                </multiselect>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="soquocgiathamgia" class="form-label">Giá trị lượng giá
                  </label>
                  <input type="number" class="form-control" v-model="form.gia_tri_luong_gia" />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label">Nguồn dữ liệu</label>
                  <input type="text" class="form-control" v-model="form.nguon_du_lieu" />
                </div>
              </div>
              <br />
              <div style="flex: 1">
                <label for="ten" class="form-label">Mô tả<span class="red-dot"></span></label>
                <input class="form-control" id="ten" v-model="form.mo_ta" />
              </div>
              <br />
              <div style="flex: 1">
                <label for="ten" class="form-label">Ghi chú<span class="red-dot"></span></label>
                <input class="form-control" id="ten" v-model="form.ghi_chu" />
              </div>
            </div>
          </div>
        </div>
        <div slot="footer">
          <button type="button" class="btn btn-success" v-if="!edit" @click="add" :disabled="disableButton">
            <i class="fas fa-plus"></i>
            Thêm mới
          </button>
          <button type="button" class="btn btn-success" v-else @click="update" :disabled="disableButton">
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
  components: { ValidationProvider, Multiselect },
  props: ["danhmucs"],
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
    phanLoaiSelect: null,
    heSinhThaiSelect: null,
    confirmDelete: false,
    idDelete: null,
    delLink: null,
    contentDelete: "Bạn có chắc chắn muốn xóa dữ liệu này?",
    dataFilter: {},
    form: {
      ten: null,
      mo_ta: null,
      phan_loai_id: null,
      gia_tri_luong_gia: null,
      he_sinh_thai_id: [],
      nguon_du_lieu: null,
      ghi_chu: null,
    },
    danhMucs: {},
    isStatus: 1,
    isBanHanh: 1,
    isHieuLuc: 1,
    isThamGia: 1,
    searchDatasetType: null,
    filter: {
      nguon_kinh_phi_id: null,
    },
  }),
  mounted() {
    this.getData();
    this.getChiSo();
  },

  methods: {
    resetForm() {
      this.phanLoaiSelect = null;
      this.heSinhThaiSelect = null;
      this.form = {
        ten: null,
        mo_ta: null,
        phan_loai_id: null,
        gia_tri_luong_gia: null,
        he_sinh_thai_id: [],
        nguon_du_lieu: null,
        ghi_chu: null,
      };
    },
    showXoa(data) {
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa dịch vụ hệ sinh thái " +
        data.ten +
        " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/dichvuhesinhthai/delete/";
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
      this.phanLoaiSelect = Object.assign({}, data.phan_loai);
      this.heSinhThaiSelect = null;
      if (data.he_sinh_thai && data.he_sinh_thai.length) {
        this.heSinhThaiSelect = [...data.he_sinh_thai];
      }
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        this.form.phan_loai_id = this.phanLoaiSelect
          ? this.phanLoaiSelect.id
          : null;
        this.form.he_sinh_thai_id = [];
        if (this.heSinhThaiSelect && this.heSinhThaiSelect.length) {
          this.heSinhThaiSelect.forEach((el) => {
            this.form.he_sinh_thai_id.push(el.id);
          });
        }
        if (validate) {
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/dichvuhesinhthai/add", this.form)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Thêm mới thành công";
                this.getData();
              }
              else{
                this.typeNotification = 1;
                this.textNotification = response.data.error[Object.keys(response.data.error)[0]];
              }
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
        this.form.phan_loai_id = this.phanLoaiSelect
          ? this.phanLoaiSelect.id
          : null;
        this.form.he_sinh_thai_id = [];
        if (this.heSinhThaiSelect && this.heSinhThaiSelect.length) {
          this.heSinhThaiSelect.forEach((el) => {
            this.form.he_sinh_thai_id.push(el.id);
          });
        }
        if (validate) {
          axios
            .post(env.endpointhttp + "admin/dichvuhesinhthai/edit", this.form)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Thêm mới thành công";
                this.getData();
                this.getData();
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
        .get(env.endpointhttp + "admin/dichvuhesinhthai/get", {
          params: {
            page: this.page,
            search: this.search,
            phan_loai_id: this.filter.phan_loai_id,
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

<style>

</style>