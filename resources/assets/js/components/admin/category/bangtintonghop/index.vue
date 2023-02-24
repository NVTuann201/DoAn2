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
              @click="isStatus = -isStatus"
              data-toggle="collapse"
              data-target=".filter4"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              "
            >
              Phân cấp
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
              v-for="item in dataFilter.phan_cap"
              :key="item.id"
            >
              <a
                @click="searchPhanLoai(item.id)"
                style="cursor: pointer"
                :style="
                  filter.phan_cap_id == item.id
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
            Dánh sách bảng tin tổng hợp
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
                    Thông số
                  </th>
                  <th style="display: table-cell">Phân cấp</th>
                  <th style="display: table-cell; min-width: 70px">
                    Chỉ số
                  </th>
                  <th style="display: table-cell; min-width: 70px">
                    Giá trị
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
                        <div style="font-size: 16px; font-weight: 400">
                          {{ item.thong_so ? item.thong_so.ten : "" }}
                        </div>
                    </div>
                  </td>
                  <td>
                    <div>{{ item.phan_cap ? item.phan_cap.name : "" }}</div>
                  </td>
                  <td>
                    {{ item.chi_so }}
                  </td>
                  <td>
                    <div>
                      {{ item.gia_tri }} {{item.don_vi_tinh}}
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
        :width="1000"
        :title="edit ? 'Cập nhật áp lực lên đa dạng sinh học' : 'Thêm áp lực lên đa dạng sinh học'"
      >
        <div slot="body">
          <div class="d-flex">
            <div style="flex: 1; padding-right: 15px">
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ten" class="form-label"
                    >Thông số <span class="red-dot">*</span></label
                  >

                  <multiselect
                    v-model="thongSoSelect"
                    :options="danhmucs.thong_so"
                    :searchable="true"
                    track-by="id"
                    label="ten"
                    placeholder=" Chọn một thông sô"
                    :show-labels="false"
                    name="tenvanban"
                    v-validate="'required'"
                  >
                  </multiselect>
                  <span
                    v-show="errors.has('tenvanban')"
                    class="help is-danger"
                    style="color: red"
                    >Thông số không thể bỏ trống</span
                  >
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label"
                    >Chỉ số <span class="red-dot"></span
                  ></label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.chi_so"
                  />
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ten" class="form-label"
                    >Giá trị <span class="red-dot"></span
                  ></label>
                  <input
                    type="number"
                    class="form-control"
                    id="ten"
                    v-model="form.gia_tri"
                  />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label"
                    >Đơn vị tính <span class="red-dot"></span
                  ></label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.don_vi_tinh"
                  />
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ten" class="form-label"
                    >Phân cấp <span class="red-dot">*</span></label
                  >

                  <multiselect
                    v-model="phanCapSelect"
                    :options="danhmucs.phan_cap"
                    :searchable="true"
                    track-by="id"
                    label="name"
                    placeholder=" Chọn phân cấp"
                    :show-labels="false"
                    name="tenvanban"
                  >
                  </multiselect>
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label"
                    >Đơn vị báo cáo <span class="red-dot"></span
                  ></label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.don_vi_bao_cao"
                  />
                </div>
              </div>
              <br />
              <div>
                <label for="nhomchithi" class="form-label"
                  >Ghi chú <span class="red-dot"></span
                ></label>
                <input class="form-control" v-model="form.ghi_chu" />
              </div>
            </div>
            <div style="flex: 1; padding-left: 15px">
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ten" class="form-label"
                    >Nguồn dữ liệu<span class="red-dot"></span
                  ></label>

                  <input
                    type="text"
                    class="form-control"
                    v-model="form.nguon_du_lieu"
                  />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label"
                    >Kỳ báo cáo <span class="red-dot"></span
                  ></label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ky_bao_cao"
                  />
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ten" class="form-label"
                    >Thời gian bắt đầu<span class="red-dot"></span
                  ></label>

                  <input
                    type="date"
                    class="form-control"
                    v-model="form.bat_dau"
                  />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label"
                    >Thời gian kết thúc <span class="red-dot"></span
                  ></label>
                  <input
                    type="date"
                    class="form-control"
                    v-model="form.ket_thuc"
                  />
                </div>
              </div>
              <br />

              <div>
                <label for="nhomchithi" class="form-label"
                  >Mô tả <span class="red-dot"></span
                ></label>
                <input class="form-control" v-model="form.mo_ta" />
              </div>
              <br />
              <div>
                <div class="d-flex">
                  <label class="form-label" style="flex: 1"
                    >Tệp đính kèm
                  </label>
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
import Multiselect from "../../../vue-multiselect";

export default {
  props: ["danhmucs"],
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
    thongSoSelect: null,
    phanCapSelect: null,
    dataFilter: {},
    dataTable: [],
    confirmDelete: false,
    idDelete: null,
    delLink: null,
    contentDelete: "Bạn có chắc chắn muốn xóa dữ liệu này?",
    form: {
      thong_so_id: null,
      chi_so: null,
      gia_tri: null,
      don_vi_tinh: null,
      phan_cap_id: null,
      don_vi_bao_cao: null,
      ghi_chu: null,
      nguon_du_lieu: null,
      ky_bao_cao: null,
      bat_dau: null,
      ket_thuc: null,
      mo_ta: null,
      files: [],
    },
    danhMucs: {},
    isStatus: 1,
    isBanHanh: 1,
    isHieuLuc: 1,
    isThamGia: 1,
    searchDatasetType: null,
    filter: {
      nam_thuc_hien: null,
      hinh_thuc_id: null,
      phan_loai_id: null,
    },
  }),
  mounted() {
    this.getData();
    this.getChiSo();
  },

  methods: {
    showXoa(data) {
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa mô hình sáng kiến " + data.ten + " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/mohinhsangkien/delete/";
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
      this.thongSoSelect = null;
      this.phanCapSelect = null;
      this.form = {
        thong_so_id: null,
        chi_so: null,
        gia_tri: null,
        don_vi_tinh: null,
        phan_cap_id: null,
        don_vi_bao_cao: null,
        ghi_chu: null,
        nguon_du_lieu: null,
        ky_bao_cao: null,
        bat_dau: null,
        ket_thuc: null,
        mo_ta: null,
        files: [],
      };
      this.fileList = [];
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
      if (data.files) {
        this.form.files = [...JSON.parse(data.files)];
      }
      this.fileList = [...data.fileList];
      this.phanCapSelect = Object.assign({}, data.phan_cap);
      this.thongSoSelect = Object.assign({}, data.thong_so);
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        this.form.thong_so_id = this.thongSoSelect
          ? this.thongSoSelect.id
          : null;
        this.form.phan_cap_id = this.phanCapSelect
          ? this.phanCapSelect.id
          : null;

        if (validate) {
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/bangtin/add", this.form)
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
        this.form.thong_so_id = this.thongSoSelect
          ? this.thongSoSelect.id
          : null;
        this.form.phan_cap_id = this.phanCapSelect
          ? this.phanCapSelect.id
          : null;

        if (validate) {
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/bangtin/edit", this.form)
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
              this.disableButton = false;
            });
        }
      });
    },
    getData() {
      axios
        .get(env.endpointhttp + "admin/bangtin/get", {
          params: {
            page: this.page,
            search: this.search,
            phan_cap_id: this.filter.phan_cap_id,
          },
        })
        .then((res) => {
          this.dataTable = res.data.data;
          this.page = res.data.current_page;
          this.pageCount = res.data.last_page;
        });
    },
    getChiSo() {
      axios.get(env.endpointhttp + "admin/bangtin/chiso").then((res) => {
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
    showXoaBoLoc() {
      if (
        this.filter.phan_cap_id == null
      ) {
        return false;
      } else return true;
    },
    deleteSearchFilter() {
      this.filter = {
        phan_cap_id: null
      };
      this.showXoaBoLoc();
      this.timKiem();
    },
    searchPhanLoai(phanCap) {
      if (this.filter.phan_cap_id === phanCap) {
        this.filter.phan_cap_id = null;
      } else {
        this.filter.phan_cap_id = phanCap;
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