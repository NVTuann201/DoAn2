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
              @click="isBanHanh = -isBanHanh"
              data-toggle="collapse"
              data-target=".filter1"
              style="cursor: pointer; font-weight: normal"
            >
              Năm cấp giấy phép
              <span
                :class="
                  isBanHanh == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <li
              class="collapse filter1"
              style="margin-left: 15px; padding-top: 10px"
            >
              <input
                placeholder="Nhập năm"
                v-model="filter.nam_cap_giay"
                type="number"
                @keyup.enter="timKiem"
              />
            </li>
            <div
              @click="isHieuLuc = -isHieuLuc"
              data-toggle="collapse"
              data-target=".filter2"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              "
            >
              Năm hết hạn
              <span
                :class="
                  isHieuLuc == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <li
              class="collapse filter2"
              style="margin-left: 15px; padding-top: 10px"
            >
              <input
                placeholder="Nhập năm hết hạn"
                v-model="filter.nam_het_han"
                type="number"
                @keyup.enter="timKiem"
              />
            </li>
            <div
              @click="isStatus = -isStatus"
              data-toggle="collapse"
              data-target=".filter4"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              "
            >
              Loại giấy phép
              <span
                :class="
                  isStatus == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <br />
            <li
              class="collapse filter4"
              style="margin-left: 15px"
              v-for="item in loaigiayphep"
              :key="item.id"
            >
              <a
                @click="searchLoaiGiayPhep(item.id)"
                style="cursor: pointer"
                :style="
                  filter.loai_giay_phep == item.id
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
                {{ item.name }}
                <span></span>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="right-aside">
        <div class="d-flex">
          <div style="flex: 1; font-size: 18px; font-weight: bold">
            Danh sách giấy phép đa dạng sinh học
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
                    Giấy phép
                  </th>
                  <th style="display: table-cell">Cơ quan cấp</th>
                  <th style="display: table-cell; min-width: 70px">
                    Thời gian
                  </th>
                  <th style="display: table-cell; min-width: 70px">
                    Đơn vị được cấp
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
                          {{
                            item.loai_giay_phep ? item.loai_giay_phep.name : ""
                          }}
                        </div>
                        <div>
                          Loại:
                          {{
                            item.loai_cap ? item.loai_cap.name : ""
                          }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>{{ item.co_quan_cap }}</td>
                  <td>
                    <div>
                      <div>Ngày cấp: {{ item.ngay_cap }}</div>
                      <div>Ngày hết hạn: {{ item.ngay_het_han }}</div>
                    </div>
                  </td>
                  <td>
                    <div>
                      {{ item.don_vi_duoc_cap }}
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
        v-if="showForm"
        @close="showForm = false"
        :width="800"
        :title="
          edit
            ? 'Cập nhật giấy phép đa dạng sinh học'
            : 'Thêm giấy phép đa dạng sinh học'
        "
      >
        <div slot="body">
          <div class="c-flex">
            <div class="c-flex">
              <label for="ten" class="form-label"
                >Tên giấy phép <span class="red-dot">*</span></label
              >
              <multiselect
                v-model="tenGiaySelect"
                :options="tengiayphep"
                :searchable="true"
                track-by="id"
                name="tenGiayPhep"
                label="name"
                placeholder="Chọn một giấy phép"
                v-validate="'required'"
                :show-labels="false"
              >
              </multiselect>
              <span
                v-show="errors.has('tenGiayPhep')"
                class="help is-danger"
                style="color: red"
                >Loại giấy phép không thể bỏ trống</span
              >
            </div>
            <br />
            <div class="d-flex">
              <div style="flex: 1; padding-right: 10px">
                <label for="ten" class="form-label"
                  >Cơ quan cấp<span class="red-dot"></span
                ></label>
                <input
                  type="text"
                  class="form-control"
                  id="ten"
                  name="sohieu"
                  v-model="form.co_quan_cap"
                  v-validate="'required'"
                />
              </div>
              <div style="flex: 1; padding-left: 10px">
                <label for="ten" class="form-label"
                  >Loại giấy phép <span class="red-dot">*</span></label
                >
                <multiselect
                  v-model="loaiGiayPhepSelect"
                  :options="loaigiayphep"
                  :searchable="true"
                  track-by="id"
                  name="loaiGiay"
                  label="name"
                  placeholder="Chọn loại giấy phép"
                  v-validate="'required'"
                  :show-labels="false"
                >
                </multiselect>
                <span
                  v-show="errors.has('loaiGiay')"
                  class="help is-danger"
                  style="color: red"
                  >Loại giấy phép không thể bỏ trống</span
                >
              </div>
            </div>
            <br />
            <div class="d-flex">
              <div style="flex: 1; padding-right: 10px">
                <label for="ngaybanhanh" class="form-label">Ngày cấp </label>
                <input
                  type="date"
                  class="form-control"
                  id="ngaybanhanh"
                  name="tenNhomChiThi"
                  v-model="form.ngay_cap"
                />
              </div>
              <div style="flex: 1; padding-left: 10px">
                <label for="ngayhieuluc" class="form-label"
                  >Ngày hết hạn
                </label>
                <input
                  type="date"
                  class="form-control"
                  id="ngayhieuluc"
                  v-model="form.ngay_het_han"
                />
              </div>
            </div>
            <br />
            <div class="d-flex">
              <div style="flex: 1; padding-right: 10px">
                <label for="ngayvietnamthamgia" class="form-label"
                  >Đơn vị được cấp
                </label>
                <input
                  v-model="form.don_vi_duoc_cap"
                  placeholder="Tên đơn vị được cấp"
                  class="form-control"
                />
              </div>
              <div style="flex: 1; padding-left: 10px" class="c-flex">
                <label for="soquocgiathamgia" class="form-label"
                  >Ghi chú
                </label>
                <input
                  class="form-control"
                  id="flexCheckDefault"
                  v-model="form.ghi_chu"
                />
              </div>
            </div>
            <br />
            <div>
              <div class="d-flex">
                <label class="form-label" style="flex: 1">Tệp đính kèm </label>
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
    filter: {
      loai_giay_phep: null,
      nam_het_han: null,
      nam_cap_giay: null,
    },
  }),
  mounted() {
    this.getData();
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
        "Bạn có chắc chắn muốn xóa " +
        data.ten_giay_phep.name + ' của đơn vị: '+ data.don_vi_duoc_cap +  
        " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/giayphep/delete/";
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
       window.location.href = "/admin/giayphep/add";
      // this.resetForm();
      // this.edit = false;
      // this.showForm = true;
    },
    showFormEdit(data) {
       window.location.href = "/admin/giayphep/edit/" + data.id;
      // Object.assign(this.form, data);
      // this.edit = true;
      // this.showForm = true;
      // if (data.files) {
      //   this.form.files = [...JSON.parse(data.files)];
      // }
      // this.fileList = [...data.fileList];
      // this.tenGiaySelect = Object.assign({}, data.ten_giay_phep);
      // this.loaiGiayPhepSelect = Object.assign({}, data.loai_giay_phep);
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        this.form.ten_giay_phep_id = this.tenGiaySelect
          ? this.tenGiaySelect.id
          : null;
        this.form.loai_giay_phep_id = this.loaiGiayPhepSelect.id
          ? this.loaiGiayPhepSelect.id
          : null;
        this.disableButton = true;
        if (validate) {
          axios
            .post(env.endpointhttp + "admin/giayphep/add", this.form)
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
        this.form.ten_giay_phep_id = this.tenGiaySelect
          ? this.tenGiaySelect.id
          : null;
        this.form.loai_giay_phep_id = this.loaiGiayPhepSelect
          ? this.loaiGiayPhepSelect.id
          : null;
        if (validate) {
          axios
            .put(env.endpointhttp + "admin/giayphep/edit", this.form)
            .then(() => {
              this.typeNotification = 2;
              this.textNotification = "Cập nhật thành công";
              this.getData();
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
    getData() {
      axios
        .get(env.endpointhttp + "admin/giayphep/list", {
          params: {
            page: this.page,
            search: this.search,
            loai_giay: this.filter.loai_giay_phep,
            nam_cap_giay: this.filter.nam_cap_giay,
            nam_het_han: this.filter.nam_het_han,
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
        loai_giay_phep: null,
        nam_het_han: null,
        nam_cap_giay: null,
      };
      this.showXoaBoLoc();
      this.timKiem();
    },
    searchLoaiGiayPhep(loaiGiay) {
      if (this.filter.loai_giay_phep === loaiGiay) {
        this.filter.loai_giay_phep = null;
      } else {
        this.filter.loai_giay_phep = loaiGiay;
      }
      this.timKiem();
    },
  },
};
</script>

<style>
</style>