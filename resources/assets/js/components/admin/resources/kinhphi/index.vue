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
          <ul>
            <div
              @click="isStatus = -isStatus"
              data-toggle="collapse"
              data-target=".filter4"
              style="cursor: pointer; font-weight: normal"
            >
              Nguồn kinh phí
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
              style="margin-bottom: 10px"
              v-for="item in dataFilter.nguon_kinh_phi"
              :key="item.id"
            >
              <a
                @click="filterTable(item.id)"
                style="cursor: pointer"
                :style="
                  filter.nguon_kinh_phi_id == item.id
                    ? 'color: #408c5b; font-weight: bold'
                    : 'color: black'
                "
              >
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
            Kinh phí hằng năm
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
                    Nguồn kinh phí
                  </th>
                  <th style="display: table-cell">Tổng kinh phí</th>
                  <th style="display: table-cell; min-width: 70px">
                    Tỉ lệ ngân sách
                  </th>
                  <th style="display: table-cell; min-width: 70px">
                    Quận huyện
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
                          {{ item.nguon_kinh_phi.name }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>{{ item.tong_kinh_phi }} đ</div>
                  </td>
                  <td>
                    <div>{{ item.ty_le_ngan_sach }} %</div>
                  </td>
                  <td>
                    <div>
                      {{
                        item.quan_huyen ? item.quan_huyen.name_vietnamese : ""
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
        v-if="showForm"
        @close="showForm = false"
        :width="800"
        :title="edit ? 'Cập nhật nguồn kinh phí' : 'Thêm nguồn kinh phí'"
      >
        <div slot="body">
          <div class="d-flex">
            <div style="flex: 1; padding-right: 15px">
              <div style="flex: 1; padding-right: 10px">
                <label for="ten" class="form-label"
                  >Nguồn kinh phí <span class="red-dot">*</span></label
                >
                <multiselect
                  v-model="nguonKinhPhiSelect"
                  :options="danhmucs.nguon_kinh_phi"
                  :searchable="true"
                  track-by="id"
                  label="name"
                  name="nguonkinhphi"
                  placeholder="Chọn một nguồn kinh phí"
                  :show-labels="false"
                  v-validate="'required'"
                >
                </multiselect>
                <span
                  v-show="errors.has('nguonkinhphi')"
                  class="help is-danger"
                  style="color: red"
                  >Nguồn kinh phí không thể bỏ trống</span
                >
              </div>
              <br />

              <div style="flex: 1">
                <label for="ten" class="form-label"
                  >Tổng kinh phí (đồng)<span class="red-dot"></span
                ></label>
                <input
                  type="number"
                  class="form-control"
                  id="ten"
                  v-model="form.tong_kinh_phi"
                />
              </div>
              <!-- <br />
              <div class="c-flex">
                <label for="ten" class="form-label"
                  >Mục đích sử dụng<span class="red-dot"></span
                ></label>
                <input
                  type="text"
                  class="form-control"
                  id="ten"
                  name="tendonvi"
                  v-model="form.muc_dich_su_dung"
                />
              </div> -->
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ngaybanhanh" class="form-label"
                    >Tỷ lệ ngân sách (%)
                  </label>
                  <input
                    type="number"
                    class="form-control"
                    id="ngaybanhanh"
                    name="tenNhomChiThi"
                    v-model="form.ty_le_ngan_sach"
                  />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ngayhieuluc" class="form-label"
                    >Thời gian thu thập(năm)</label
                  >
                  <input
                    type="number"
                    class="form-control"
                    id="ngayhieuluc"
                    v-model="form.thoi_gian"
                  />
                </div>
              </div>

              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Quận huyện
                  </label>
                  <multiselect
                    v-model="quanHuyenSelect"
                    :options="danhmucs.quan_huyen"
                    :searchable="true"
                    track-by="id"
                    label="name_vietnamese"
                    placeholder="Chọn một quận huyện"
                    :show-labels="false"
                  >
                  </multiselect>
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label"
                    >Ghi chú <span class="red-dot"></span
                  ></label>
                  <input
                    type="text"
                    class="form-control"
                    id="ten"
                    name="sohieu"
                    v-model="form.ghi_chu"
                  />
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
          <button
            type="button"
            class="btn btn-success"
            v-else
            @click="update"
            :disabled="disableButton"
          >
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
    quanHuyenSelect: null,
    nguonKinhPhiSelect: null,
    confirmDelete: false,
    idDelete: null,
    delLink: null,
    contentDelete: "Bạn có chắc chắn muốn xóa dữ liệu này?",
    dataFilter: {},
    form: {
      nguon_kinh_phi_id: null,
      tong_kinh_phi: null,
      muc_dich_su_dung: null,
      ty_le_ngan_sach: null,
      thoi_gian: null,
      quan_huyen_id: null,
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
      this.quanHuyenSelect = null;
      this.nguonKinhPhiSelect = null;
      this.form = {
        nguon_kinh_phi_id: null,
        tong_kinh_phi: null,
        muc_dich_su_dung: null,
        ty_le_ngan_sach: null,
        thoi_gian: null,
        quan_huyen_id: null,
        ghi_chu: null,
      };
      this.fileList = [];
    },
    showXoa(data) {
      let nguonkp = data.nguon_kinh_phi ? data.nguon_kinh_phi.name : "";
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa dữ liệu kinh phí " +
        nguonkp +
        " tổng giá trị  " +
        data.tong_kinh_phi +
        " đ " +
        " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/kinhphi/delete/";
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
      this.quanHuyenSelect = Object.assign({}, data.quan_huyen);
      this.nguonKinhPhiSelect = Object.assign({}, data.nguon_kinh_phi);
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        this.form.quan_huyen_id = this.quanHuyenSelect
          ? this.quanHuyenSelect.id
          : null;
        this.form.nguon_kinh_phi_id = this.nguonKinhPhiSelect
          ? this.nguonKinhPhiSelect.id
          : null;
        if (validate) {
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/kinhphi/add", this.form)
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
        this.form.quan_huyen_id = this.quanHuyenSelect
          ? this.quanHuyenSelect.id
          : null;
        this.form.nguon_kinh_phi_id = this.nguonKinhPhiSelect
          ? this.nguonKinhPhiSelect.id
          : null;
        if (validate) {
          axios
            .post(env.endpointhttp + "admin/kinhphi/edit", this.form)
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
    filterTable(nguon_kinh_phi_id) {
      if (this.filter.nguon_kinh_phi_id == nguon_kinh_phi_id) {
        this.filter.nguon_kinh_phi_id = null;
      } else {
        this.filter.nguon_kinh_phi_id = nguon_kinh_phi_id;
      }
      this.timKiem();
    },
    getData() {
      axios
        .get(env.endpointhttp + "admin/kinhphi/get", {
          params: {
            page: this.page,
            search: this.search,
            nguon_kinh_phi_id: this.filter.nguon_kinh_phi_id,
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
      if (this.filter.nguon_kinh_phi_id == null) {
        return false;
      } else return true;
    },
    deleteSearchFilter() {
      this.filter = {
        nguon_kinh_phi_id: null,
      };
      this.showXoaBoLoc();
      this.timKiem();
    },
    getChiSo() {
      axios.get(env.endpointhttp + "admin/kinhphi/chiso").then((res) => {
        this.dataFilter = res.data;
      });
    },
  },
};
</script>

<style>
</style>