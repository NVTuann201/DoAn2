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
            Danh sách kết quả quan trắc
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
                    Điểm quan trắc
                  </th>
                  <th style="display: table-cell">Thông số</th>
                  <th style="display: table-cell; min-width: 70px">
                    Đơn vị đo
                  </th>
                  <th style="display: table-cell; min-width: 70px">Kết quả</th>

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
                            item.diem_quan_trac ? item.diem_quan_trac.ten : ""
                          }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>{{ item.thong_so ? item.thong_so.ten : "" }}</div>
                  </td>
                  <td>
                    <div>{{ item.don_vi_do }}</div>
                  </td>
                  <td>
                    <div>
                      {{ item.ket_qua }}
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
        :width="800"
        :title="edit ? 'Cập nhật kết quả quan trắc' : 'Thêm kết quả quan trắc'"
      >
        <div slot="body">
          <div class="d-flex">
            <div style="flex: 1">
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Điểm quan trắc<span class="red-dot">*</span>
                  </label>
                  <multiselect
                    v-model="diemQuanTracSelect"
                    :options="danhmucs.diem_quan_trac"
                    :searchable="true"
                    track-by="id"
                    label="ten"
                    placeholder="Chọn địa điểm quan trắc"
                    :show-labels="false"
                    name="diemquantrac"
                    v-validate="'required'"
                  >
                  </multiselect>
                  <span
                    v-show="errors.has('diemquantrac')"
                    class="help is-danger"
                    style="color: red"
                    >Điểm quan trắc không thể bỏ trống</span
                  >
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Thông số quan trắc<span class="red-dot"></span>
                  </label>
                  <multiselect
                    v-model="thongSoSelect"
                    :options="danhmucs.thong_so"
                    :searchable="true"
                    track-by="id"
                    label="ten"
                    placeholder="Chọn thông sô"
                    :show-labels="false"
                  >
                  </multiselect>
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Kết quả<span class="red-dot"></span>
                  </label>
                  <input
                    type="number"
                    class="form-control"
                    name="tendichvu"
                    v-model="form.ket_qua"
                  />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Đơn vị đo<span class="red-dot"></span>
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    name="tendichvu"
                    v-model="form.don_vi_do"
                  />
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1">
                  <label for="soquocgiathamgia" class="form-label"
                    >Quy chuẩn Việt Nam
                  </label>
                  <input
                    class="form-control"
                    v-model="form.quy_chuan_viet_nam"
                  />
                </div>
              </div>

              <br />
              <div class="d-flex">
                <div style="flex: 1">
                  <label for="soquocgiathamgia" class="form-label"
                    >Ghi chú
                  </label>
                  <input class="form-control" v-model="form.ghi_chu" />
                </div>
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
export default {
  components: { ValidationProvider, Multiselect },
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
    thongSoSelect: null,
    diemQuanTracSelect: null,
    form: {
      diem_quan_trac_id: null,
      thong_so_id: null,
      ket_qua: null,
      don_vi_do: null,
      quy_chuan_viet_nam: null,
      ghi_chu: null,
      files: [],
    },
    isStatus: 1,
    isBanHanh: 1,
    isHieuLuc: 1,
    isThamGia: 1,
    searchDatasetType: null,
    danhmucs: {},
    filter: {
      nguon_kinh_phi_id: null,
    },
  }),
  mounted() {
    this.getData();
    this.getDanhMuc();
  },

  methods: {
    resetForm() {
      this.thongSoSelect = null;
      this.diemQuanTracSelect = null;
      this.form = {
        diem_quan_trac_id: null,
        thong_so_id: null,
        ket_qua: null,
        don_vi_do: null,
        quy_chuan_viet_nam: null,
        ghi_chu: null,
        files: [],
      };
      this.fileList = [];
    },
    showXoa(data) {
      this.contentDelete = "Bạn có chắc chắn muốn xóa kết quả quan trắc này ?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/ketquaquantrac/delete/";
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
      if (data.files) {
        this.form.files = [...JSON.parse(data.files)];
      }
      this.fileList = [...data.fileList];
      this.thongSoSelect = Object.assign({}, data.thong_so);
      this.diemQuanTracSelect = Object.assign({}, data.diem_quan_trac);
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        this.form.thong_so_id = this.thongSoSelect
          ? this.thongSoSelect.id
          : null;
        this.form.diem_quan_trac_id = this.diemQuanTracSelect
          ? this.diemQuanTracSelect.id
          : null;
        if (validate) {
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/ketquaquantrac/add", this.form)
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
        this.form.diem_quan_trac_id = this.diemQuanTracSelect
          ? this.diemQuanTracSelect.id
          : null;
        if (validate) {
          axios
            .post(env.endpointhttp + "admin/ketquaquantrac/edit", this.form)
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
        .get(env.endpointhttp + "admin/ketquaquantrac/get", {
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
    getDanhMuc() {
      axios
        .get(env.endpointhttp + "admin/ketquaquantrac/danhmuc")
        .then((res) => {
          this.danhmucs = res.data;
        });
    },
  },
};
</script>

<style></style>
