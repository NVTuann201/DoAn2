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
            <div style="font-size: 16px; font-weight: bold; flex: 1">
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
              @click="isLoaiHinh = -isLoaiHinh"
              data-toggle="collapse"
              data-target=".filter1"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              "
            >
              Loại hình thanh, kiểm tra
              <span
                :class="
                  isLoaiHinh == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <li
              class="collapse filter1"
              style="margin-left: 15px"
              v-for="item in dataFilter.loai_hinh"
              :key="item.id"
            >
              <a
                @click="selectFilter(item.id, 'loai_hinh_id')"
                style="cursor: pointer"
                :style="
                  filter.loai_hinh_id == item.id
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
                {{ item.name }}
                <span>{{ item.so_luong }}</span>
              </a>
            </li>

            <div
              @click="isCheDo = -isCheDo"
              data-toggle="collapse"
              data-target=".filter2"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              "
            >
              Loại
              <span
                :class="isCheDo == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'"
                style="float: right"
              ></span>
            </div>
            <li
              class="collapse filter2"
              style="margin-left: 15px"
              v-for="item in dataFilter.che_do"
              :key="item.id"
            >
              <a
                @click="selectFilter(item.id, 'che_do_id')"
                style="cursor: pointer"
                :style="
                  filter.che_do_id == item.id
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
            Danh sách đoàn thanh tra
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
            <i class="fas fa-plus"></i> Thêm đoàn thanh tra
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
                    Số quyết định
                  </th>
                  <th style="display: table-cell">Loại hình</th>
                  <th style="display: table-cell; min-width: 70px">
                    Thời gian ký
                  </th>
                  <th style="display: table-cell; min-width: 70px">
                    Cơ quan ký
                  </th>
                  <th style="display: table-cell; min-width: 70px">
                    Chế độ TT
                  </th>
                  <th style="display: table-cell; min-width: 70px">
                    Cơ quan thực hiện
                  </th>
                  <th style="display: table-cell; min-width: 70px">Địa bàn</th>
                  <th style="display: table-cell; min-width: 70px">
                    Thời gian TT
                  </th>
                  <th width="100" style="text-align: center">Hành động</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="dataTable && dataTable.length == 0">
                  <td colspan="10" class="emptyInfomation">
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
                    <div>{{ item.so_quyet_dinh }}</div>
                  </td>
                  <td>
                    <div>{{ item.loai_hinh.name }}</div>
                  </td>
                  <td>
                    <div>
                      {{ item.thoi_gian_ky }}
                    </div>
                  </td>
                  <td>
                    <div>
                      {{
                        item.co_quan_ky ? item.co_quan_ky.name_vietnamese : ""
                      }}
                    </div>
                  </td>
                  <td>
                    <div>
                      {{ item.che_do ? item.che_do.name : "" }}
                    </div>
                  </td>
                  <td>
                    <div>
                      {{
                        item.co_quan_thuc_hien
                          ? item.co_quan_thuc_hien.name_vietnamese
                          : ""
                      }}
                    </div>
                  </td>
                  <td>
                    <div v-for="i in item.tinh_thanh" :key="i.id">
                      {{ i.name_vietnamese }}
                    </div>
                  </td>
                  <td>
                    <div>{{ item.thoi_gian_tt }}</div>
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
        :title="edit ? 'Cập nhật đoàn thanh tra' : 'Thêm đoàn thanh tra'"
      >
        <div slot="body">
          <div class="d-flex">
            <div style="flex: 1; padding-right: 15px">
              <div class="d-flex" style="margin-top: 20px">
                <div style="flex: 1; padding-right: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Số quyết định
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    id="thoigiantt"
                    v-model="form.so_quyet_dinh"
                  />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Địa bàn
                  </label>
                  <multiselect
                    v-model="tinhThanhSelect"
                    :options="danhmucs.tinh_thanh"
                    :searchable="true"
                    track-by="id"
                    label="name_vietnamese"
                    placeholder="Chọn địa bàn"
                    :show-labels="false"
                    multiple
                  >
                  </multiselect>
                </div>
              </div>
              <div class="d-flex" style="margin-top: 20px">
                <div style="flex: 1; padding-right: 10px">
                  <label for="coquanky" class="form-label">Loại hình (*)</label>
                  <multiselect
                    v-model="loaiHinhSelect"
                    :options="danhmucs.loai_hinh"
                    :searchable="true"
                    track-by="id"
                    label="name"
                    placeholder="Chọn loại hình"
                    :show-labels="false"
                  >
                  </multiselect>
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Chế độ
                  </label>
                  <multiselect
                    v-model="cheDoSelect"
                    :options="danhmucs.che_do"
                    :searchable="true"
                    track-by="id"
                    label="name"
                    placeholder="Chọn chế độ"
                    :show-labels="false"
                  >
                  </multiselect>
                </div>
              </div>
              <div class="d-flex" style="margin-top: 20px">
                <div style="flex: 1; padding-right: 10px">
                  <label for="thoigianky" class="form-label"
                    >Thời gian ký
                  </label>
                  <input
                    type="date"
                    class="form-control"
                    id="thoigianky"
                    v-model="form.thoi_gian_ky"
                  />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="thoigiantt" class="form-label"
                    >Thời gian TT
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    id="thoigiantt"
                    v-model="form.thoi_gian_tt"
                  />
                </div>
              </div>
              <div class="d-flex" style="margin-top: 20px">
                <div style="flex: 1; padding-right: 10px">
                  <label for="coquanky" class="form-label">Cơ quan ký </label>
                  <multiselect
                    v-model="coQuanKySelect"
                    :options="danhmucs.co_quan"
                    :searchable="true"
                    track-by="id"
                    label="name_vietnamese"
                    placeholder="Chọn cơ quan ký"
                    :show-labels="false"
                  >
                  </multiselect>
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Cơ quan thực hiện
                  </label>
                  <multiselect
                    v-model="coQuanThucHienSelect"
                    :options="danhmucs.co_quan"
                    :searchable="true"
                    track-by="id"
                    label="name_vietnamese"
                    placeholder="Chọn cơ quan thực hiện"
                    :show-labels="false"
                  >
                  </multiselect>
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
import * as env from "../../../../../env.js";
import Multiselect from "../../../../vue-multiselect";

export default {
  props: ["danhmucs"],
  components: { ValidationProvider, Multiselect },
  data: () => ({
    search: null,
    typeNotification: null,
    textNotification: null,
    confirmDelete: false,
    idDelete: null,
    delLink: null,
    contentDelete: "Bạn có chắc chắn muốn xóa dữ liệu này?",
    loading: false,
    showForm: false,
    page: 1,
    pageCount: 0,
    edit: false,
    tienTrinhUpload: null,
    fileList: [],
    loaiVanBanSelect: null,
    coQuanSelect: null,
    linhVucSelect: null,
    disableButton: false,
    dataTable: [],
    form: {
      loai_hinh_id: null,
      so_quyet_dinh: null,
      thoi_gian_ky: null,
      co_quan_ky_id: null,
      che_do_tt: null,
      co_quan_thuc_hien_id: null,
      dia_ban: [],
      thoi_gian_tt: null,
    },
    danhMucs: {},
    isLoaiHinh: 1,
    isCheDo: 1,

    searchDatasetType: null,
    dataFilter: {},
    filter: {
      loai_hinh_id: null,
      che_do_id: null,
    },

    tinhThanhSelect: null,
    cheDoSelect: null,
    loaiHinhSelect: null,

    coQuanThucHienSelect: null,
    coQuanKySelect: null,
  }),
  mounted() {
    this.getData();
  },

  methods: {
    showXoa(data) {
      this.contentDelete = "Bạn có chắc chắn muốn xóa không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/doanthanhtra/delete/";
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
      this.cheDoSelect = null;
      this.loaiHinhSelect = null;
      this.coQuanThucHienSelect = null;
      this.coQuanKySelect = null;

      this.form = {
        loai_hinh_id: null,
        so_quyet_dinh: null,
        thoi_gian_ky: null,
        co_quan_ky_id: null,
        che_do_id: null,
        co_quan_thuc_hien_id: null,
        dia_ban: [],
        thoi_gian_tt: null,
      };
      this.fileList = [];
    },
    showFormAdd() {
      this.resetForm();
      this.edit = false;
      this.showForm = true;
    },
    showFormEdit(data) {
      console.log(data, this.form);
      for (const field in this.form) {
        this.form[field] = data[field];
      }
      this.form.id = data.id;
      this.edit = true;
      this.showForm = true;

      this.cheDoSelect = Object.assign({}, data.che_do);
      this.loaiHinhSelect = Object.assign({}, data.loai_hinh);
      this.coQuanKySelect = Object.assign({}, data.co_quan_ky);
      this.coQuanThucHienSelect = Object.assign({}, data.co_quan_thuc_hien);
      this.tinhThanhSelect = data.tinh_thanh;

      console.log(this.tinhThanhSelect);
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        this.form.loai_hinh_id = this.loaiHinhSelect
          ? this.loaiHinhSelect.id
          : null;
        this.form.co_quan_thuc_hien_id = this.coQuanThucHienSelect
          ? this.coQuanThucHienSelect.id
          : null;
        this.form.co_quan_ky_id = this.coQuanKySelect
          ? this.coQuanKySelect.id
          : null;
        this.form.che_do_id = this.cheDoSelect ? this.cheDoSelect.id : null;
        this.form.dia_ban = this.tinhThanhSelect
          ? this.tinhThanhSelect.map((i) => i.id)
          : [];

        if (validate) {
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/doanthanhtra/add", this.form)
            .then(() => {
              this.typeNotification = 2;
              this.textNotification = "Thêm mới thành công";
              this.getData();
              this.showForm = false;
            })
            .catch(() => {
              this.typeNotification = 1;
              this.textNotification = "Thêm mới thất bại";
            })
            .finally(() => {
              this.loading = false;

              this.typeNotification = null;
              this.textNotification = null;
              this.disableButton = false;
            });
        }
      });
    },
    update() {
      this.$validator.validateAll().then((validate) => {
        this.form.loai_hinh_id = this.loaiHinhSelect
          ? this.loaiHinhSelect.id
          : null;
        this.form.co_quan_thuc_hien_id = this.coQuanThucHienSelect
          ? this.coQuanThucHienSelect.id
          : null;
        this.form.co_quan_ky_id = this.coQuanKySelect
          ? this.coQuanKySelect.id
          : null;
        this.form.che_do_id = this.cheDoSelect ? this.cheDoSelect.id : null;
        this.form.dia_ban = this.tinhThanhSelect.map((i) => i.id);
        if (validate) {
          this.disableButton = true;
          axios
            .put(env.endpointhttp + "admin/doanthanhtra/edit", this.form)
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
        .get(env.endpointhttp + "admin/doanthanhtra/get", {
          params: {
            page: this.page,
            loai_hinh_id: this.filter.loai_hinh_id,
            che_do_id: this.filter.che_do_id,
          },
        })
        .then((res) => {
          this.dataTable = res.data.data;
          this.page = res.data.current_page;
          this.pageCount = res.data.last_page;
        });
      this.getChiSo();
    },

    clickUpload() {
      this.$refs["upload-files"].click();
    },

    clickCallback(pageNum) {
      this.page = pageNum;
      this.getData();
    },
    timKiem() {
      this.page = 1;
      this.getData();
    },

    getChiSo() {
      axios.get(env.endpointhttp + "admin/doanthanhtra/chiso").then((res) => {
        this.dataFilter = res.data;
      });
    },
    showXoaBoLoc() {
      if (this.filter.che_do_id == null && this.filter.loai_hinh_id == null) {
        return false;
      } else return true;
    },
    deleteSearchFilter() {
      this.filter = {
        loai_hinh_id: null,
        che_do_id: null,
      };
      this.showXoaBoLoc();
      this.timKiem();
    },
    selectFilter(value, name) {
      if (this.filter[name] == value) {
        this.filter[name] = null;
      } else {
        this.filter[name] = value;
      }
      this.timKiem();
    },
  },
};
</script>

<style>
</style>