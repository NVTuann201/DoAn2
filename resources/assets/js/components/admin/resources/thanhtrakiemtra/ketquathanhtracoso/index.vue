<template>
  <div class="white-box p-0">
    <div class="page-aside" style="min-height: 500px">
      <components-notifications
        :typeInput="typeNotification"
        :textInput="textNotification"
      ></components-notifications>
      <div class="loading-roles" v-if="loading">
        <components-loading-page></components-loading-page>
      </div>
      <div class="left-aside">
        <div class="d-flex">
          <div style="font-size: 16px; font-weight: bold; flex: 1">Bộ lọc</div>
        </div>
        <hr />
        <label class="form-label">Đoàn thanh tra</label>
        <multiselect
          :options="danhmucs['doan_thanh_tra']"
          :searchable="true"
          track-by="id"
          label="so_quyet_dinh"
          :show-labels="false"
          :placeholder="'Chọn đoàn thanh tra'"
          v-model="doanThanhTraSelect"
        />
        <label class="form-label" style="margin-top: 20px">Cơ quan ký</label>
        <multiselect
          :options="danhmucs['co_quan']"
          :searchable="true"
          track-by="id"
          label="name"
          :show-labels="false"
          :placeholder="'Chọn cơ quan ký'"
          v-model="coQuanKySelect"
        />
        <label class="form-label" style="margin-top: 20px"
          >Thời gian ký từ</label
        >
        <input
          type="date"
          style="width: 100%"
          class="form-control"
          v-model="filter.thoi_gian_ky_tu"
        />
        <label class="form-label" style="margin-top: 20px"
          >Thời gian ký đến</label
        >
        <input
          type="date"
          style="width: 100%"
          class="form-control"
          v-model="filter.thoi_gian_ky_den"
        />

        <button
          @click="timKiem"
          style="margin-top: 20px"
          class="btn btn-success btn-sm"
        >
          Lọc
        </button>
        <button
          style="margin-top: 20px; float: right"
          class="btn btn-success btn-sm"
          @click="xoaBoLoc"
        >
          Xóa bộ lọc
        </button>
      </div>

      <div class="right-aside">
        <div class="d-flex">
          <div style="flex: 1; font-size: 18px; font-weight: bold">
            Danh sách kết quả thanh tra cơ sở
          </div>
          <div class="d-flex">
            <input
              class="form-control"
              placeholder="Tìm kiếm"
              v-model="filter.search"
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
            <i class="fas fa-plus"></i> Thêm kết quả thanh tra
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
                    Đoàn thanh tra
                  </th>
                  <th style="display: table-cell">Số văn bản</th>
                  <th style="display: table-cell; min-width: 70px">
                    Thời gian ký
                  </th>
                  <th style="display: table-cell; min-width: 70px">
                    Cơ quan ký
                  </th>
                  <th style="display: table-cell; min-width: 70px">Tên CSTT</th>

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
                    <div>{{ item.doan_thanh_tra.so_quyet_dinh }}</div>
                  </td>
                  <td>
                    <div>{{ item.so_van_ban }}</div>
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
                      {{
                        item.co_so && item.co_so_type === "App\\ProtectedArea"
                          ? item.co_so.orig_name
                          : ""
                      }}
                    </div>
                    <div>
                      {{
                        item.co_so && item.co_so_type === "App\\CoSoBaoTon"
                          ? item.co_so.orig_name
                          : ""
                      }}
                    </div>
                  </td>

                  <td>
                    <button
                      type="button"
                      title="Chỉnh sửa"
                      @click="showFormEdit(item.id)"
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

    searchDatasetType: null,
    dataFilter: {},
    filter: {
      doan_thanh_tra_id: null,
      co_quan_ky_id: null,
      thoi_gian_ky_tu: null,
      thoi_gian_ky_den: null,
    },

    coQuanKySelect: null,
    doanThanhTraSelect: null,
  }),
  mounted() {
    this.getData();
  },

  methods: {
    showXoa(data) {
      this.contentDelete = "Bạn có chắc chắn muốn xóa không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/ketquathanhtracoso/";
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
      window.open("/admin/ketquathanhtracoso/create", "_blank");
    },
    showFormEdit(id) {
      window.open(`/admin/ketquathanhtracoso/${id}/edit`, "_blank");
    },

    getData() {
      axios
        .get(env.endpointhttp + "admin/ketquathanhtracoso/get", {
          params: {
            page: this.page,
            doan_thanh_tra_id: this.doanThanhTraSelect
              ? this.doanThanhTraSelect.id
              : null,
            co_quan_ky_id: this.coQuanKySelect ? this.coQuanKySelect.id : null,
            thoi_gian_ky_tu: this.filter.thoi_gian_ky_tu,
            thoi_gian_ky_den: this.filter.thoi_gian_ky_den,
            search: this.filter.search,
          },
        })
        .then((res) => {
          this.dataTable = res.data.data;
          this.page = res.data.current_page;
          this.pageCount = res.data.last_page;
        });
      // this.getChiSo();
    },

    clickCallback(pageNum) {
      this.page = pageNum;
      this.getData();
    },
    timKiem() {
      this.page = 1;
      this.getData();
    },

    xoaBoLoc() {
      this.filter.thoi_gian_ky_tu = null;
      this.filter.thoi_gian_ky_den = null;
      this.doanThanhTraSelect = null;
      this.coQuanKySelect = null;
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