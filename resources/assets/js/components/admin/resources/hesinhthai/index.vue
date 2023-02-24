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
      <div class="left-aside">
        <div class="scrollable">
          <div class="d-flex">
            <div style="fonts-size: 16px; font-weight: bold; flex: 1">
              Bộ lọc
            </div>
            <button
              type="button"
              class="btn-sm btn-info btn-rounded"
              v-if="showXoaBoLoc()"
              @click="deleteSearchFilter()"
            >
              <span>Xóa bộ lọc</span>
            </button>
          </div>
          <hr />
          <ul class="list-style-none" style="background: white">
            <div
              @click="isHieuLuc = -isHieuLuc"
              data-toggle="collapse"
              data-target=".filter2"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              "
            >
              Hệ sinh thái
              <span
                :class="
                  isHieuLuc == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <li
              class="collapse filter2"
              style="margin-left: 5px"
              v-for="item in dataFilter.he_sinh_thai"
              :key="item.id"
            >
              <a
                @click="selectFilter(item.id)"
                style="cursor: pointer"
                :style="
                  filter.he_sinh_thai_id == item.id
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
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
            Danh sách hệ sinh thái
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
              class="
                table table-hover
                contact-list
                footable footable-1 footable-paging footable-paging-center
                breakpoint-lg
              "
            >
              <thead>
                <tr class="footable-header">
                  <th style="width: 60px">STT</th>
                  <th style="display: table-cell; min-width: 70px">
                    Hệ sinh thái
                  </th>
                  <th style="display: table-cell">Diện tích</th>
                  <th style="display: table-cell; min-width: 70px">
                    Diện tích chuyển đổi
                  </th>
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
                          {{ item.he_sinh_thai ? item.he_sinh_thai.name : "" }}
                        </div>
                        <div></div>
                        <div>
                          {{ item.ten }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>{{ item.dien_tich }} (ha)</td>
                  <td>
                    <div>
                      <div>{{ item.dien_tich_chuyen_doi }} (ha)</div>
                    </div>
                  </td>
                  <td>
                    <div>
                      {{
                        item.dia_diem
                          ? item.dia_diem.desig + " " + item.dia_diem.orig_name
                          : ""
                      }}
                    </div>
                  </td>
                  <td>
                    <button
                      type="button"
                      title="Chỉnh sửa"
                      @click="showFormEdit(item)"
                      class="
                        btn btn-info btn-outline btn-circle btn-small
                        m-r-5
                      "
                    >
                      <i class="ti-pencil-alt"></i>
                    </button>
                    <button
                      type="button"
                      title="Xóa"
                      @click="showXoa(item)"
                      class="
                        btn btn-info btn-outline btn-circle btn-small
                        m-r-5
                      "
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
import Multiselect from "vue-multiselect";

export default {
  components: { ValidationProvider, Multiselect },
  props: ["tengiayphep", "loaigiayphep"],
  data: () => ({
    search: null,
    typeNotification: null,
    textNotification: null,
    loading: false,
    showForm: false,
    confirmDelete: false,
    idDelete: null,
    delLink: null,
    contentDelete: "Bạn có chắc chắn muốn xóa dữ liệu này?",
    page: 1,
    pageCount: 0,
    edit: false,
    tienTrinhUpload: null,
    fileList: [],
    tenGiaySelect: null,
    loaiGiayPhepSelect: null,
    dataTable: [],
    disableButton: false,
    form: {
      ten_giay_phep_id: null,
      co_quan_cap: null,
      ngay_cap: null,
      loai_giay_phep_id: null,
      don_vi_duoc_cap: null,
      ngay_het_han: null,
      ghi_chu: null,
      files: [],
    },
    danhMucs: {},
    isStatus: 1,
    isBanHanh: 1,
    isHieuLuc: 1,
    isThamGia: 1,
    searchDatasetType: null,
    dataFilter: {},
    filter: {
      loai_giay_phep: null,
      nam_het_han: null,
      nam_cap_giay: null,
    },
  }),
  mounted() {
    this.getData();
    this.getChiSo()
  },

  methods: {
    resetForm() {
      this.tenGiaySelect = null;
      this.loaiGiayPhepSelect = null;
      this.form = {
        ten_giay_phep_id: null,
        co_quan_cap: null,
        ngay_cap: null,
        loai_giay_phep_id: null,
        don_vi_duoc_cap: null,
        ngay_het_han: null,
        ghi_chu: null,
        files: [],
      };
      this.fileList = [];
    },
    showXoa(data) {
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa hệ sinh thái " +
        data.he_sinh_thai.name +
        " " +
        data.ten +
        " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/hesinhthai/delete/";
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
      window.location.href = "/admin/hesinhthai/add";
    },
    showFormEdit(data) {
      window.location.href = "/admin/hesinhthai/edit/" + data.id;
    },
    getData() {
      axios
        .get(env.endpointhttp + "admin/hesinhthai/get", {
          params: {
            page: this.page,
            search: this.search,
            he_sinh_thai_id: this.filter.he_sinh_thai_id,
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
      if (
        this.filter.nam_cap_giay == null &&
        this.filter.nam_het_han == null &&
        this.filter.loai_giay_phep == null
      ) {
        return false;
      } else return true;
    },
    deleteSearchFilter() {
      this.filter = {
        he_sinh_thai_id: null,
      };
      this.showXoaBoLoc();
      this.timKiem();
    },
    getChiSo() {
      axios
        .get(env.endpointhttp + "admin/hesinhthai/chiso")
        .then((res) => {
          this.dataFilter = res.data;
        });
    },
    selectFilter(he_sinh_thai_id) {
      if (this.filter.he_sinh_thai_id === he_sinh_thai_id) {
        this.filter.he_sinh_thai_id = null;
      } else {
        this.filter.he_sinh_thai_id = he_sinh_thai_id;
      }
      this.timKiem();
    },
  },
};
</script>

<style>
</style>