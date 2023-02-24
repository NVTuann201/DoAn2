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
      <!-- <div class="left-aside">
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
              IUCN
              <span
                :class="
                  isHieuLuc == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <li
              class="collapse filter2"
              style="margin-left: 15px"
              v-for="item in iucsList"
              :key="item.id"
            >
              <a
                @click="searchType(item.id, 'phan_loai_id')"
                style="cursor: pointer"
                :style="
                  filter.phan_loai_id == item.id
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
                {{ item }}
              </a>
            </li>

            <div
              @click="isHieuLuc = -isHieuLuc"
              data-toggle="collapse"
              data-target=".filter3"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              "
            >
              Sách đỏ Việt Nam
              <span
                :class="
                  isHieuLuc == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <li
              class="collapse filter3"
              style="margin-left: 15px"
              v-for="item in sachDoList"
              :key="item.id"
            >
              <a
                @click="searchType(item.id, 'nguy_co_id')"
                style="cursor: pointer"
                :style="
                  filter.nguy_co_id == item.id
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
                {{ item }}
              </a>
            </li>

            <div
              @click="isHieuLuc = -isHieuLuc"
              data-toggle="collapse"
              data-target=".filter4"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              "
            >
              Cites
              <span
                :class="
                  isHieuLuc == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <li
              class="collapse filter4"
              style="margin-left: 15px"
              v-for="item in dataFilter.nguy_co"
              :key="item.id"
            >
              <a
                @click="searchType(item.id, 'nguy_co_id')"
                style="cursor: pointer"
                :style="
                  filter.nguy_co_id == item.id
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
                {{ item.name }}
                <span>{{ item.so_luong }}</span>
              </a>
            </li>

            <div
              @click="isHieuLuc = -isHieuLuc"
              data-toggle="collapse"
              data-target=".filter5"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              "
            >
              Nghị Định
              <span
                :class="
                  isHieuLuc == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <li
              class="collapse filter5"
              style="margin-left: 15px"
              v-for="item in dataFilter.nguy_co"
              :key="item.id"
            >
              <a
                @click="searchType(item.id, 'nguy_co_id')"
                style="cursor: pointer"
                :style="
                  filter.nguy_co_id == item.id
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
                {{ item.name }}
                <span>{{ item.so_luong }}</span>
              </a>
            </li>

            <li>
              <a style="cursor: context-menu">
                Khác
                <span>200</span>
              </a>
            </li>
          </ul>
        </div>
      </div> -->
      <div style="padding: 30px">
        <div class="d-flex">
          <div style="flex: 1; font-size: 18px; font-weight: bold">
            Danh sách loài
          </div>
          <div class="d-flex">
            <input
              type="text"
              class="form-control"
              placeholder="Tìm kiếm"
              v-model="search"
              v-on:keyup.enter="timKiem()"
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
                    Tên khoa học
                  </th>
                  <th style="display: table-cell">Tên tiếng Anh</th>
                  <th style="display: table-cell">Phân loại</th>
                  <th style="display: table-cell; min-width: 70px">
                    Đặc điểm hình thái
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
                      <div>{{ item.scientific_name }}</div>
                    </div>
                  </td>
                  <td>{{ item.vernacular_name_english }}</td>
                  <td>
                    <div>
                      Giới: {{ item.king_dom ? item.king_dom.name : "" }}
                    </div>
                    <div>Ngành: {{ item.phylum ? item.phylum.name : "" }}</div>
                    <div>Lớp: {{ item.class ? item.class.name : "" }}</div>
                    <div>Bộ: {{ item.order ? item.order.name : "" }}</div>
                    <div>Họ: {{ item.family ? item.family.name : "" }}</div>
                  </td>
                  <td>{{ item.morphological_description }}</td>
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
    iucsList: [
      'Tuyệt chủng (Extinct - Ex)',
      'Tuyệt chủng ngoài thiên nhiên (Extinct in the wild - Ex)',
      'Cực kỳ nguy cấp (Critically Endangered - CR)',
      'Nguy cấp (Endangered - EN)',
      'Sắp nguy cấp (Vulnerable - VU)',
      'Sắp bị đe dọa (NT)',
      'Ít quan tâm (Least concem - LC)',
      'Thiếu dữ liệu (Data deficient - DD)',
      'Không được đánh giá (Not evaluated - NE)',
    ],
    sachDoList: [
      'Tuyệt chủng (Extinct - Ex)',
      'Tuyệt chủng ngoài thiên nhiên (Extinct in the wild - Ex)',
      'Cực kỳ nguy cấp (Critically Endangered - CR)',
      'Nguy cấp (Endangered - EN)',
      'Sắp nguy cấp (Vulnerable - VU)',
      'Sắp bị đe dọa (NT)',
      'Ít quan tâm (Least concem - LC)',
      'Thiếu dữ liệu (Data deficient - DD)',
      'Không được đánh giá (Not evaluated - NE)',
    ],
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
      phan_loai_id: null,
      nguy_co_id: null,
    },
  }),
  mounted() {
    this.getData();
    //this.getChiSo();
  },

  methods: {
    showXoa(data) {
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa loài " + data.scientific_name + " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/taxon/";
    },
    xoaDuLieu() {
      axios
        .delete(env.endpointhttp + this.delLink + this.idDelete)
        .then(() => {
          this.typeNotification = 2;
          this.textNotification = "Xóa thành công";
          this.getData();
          this.getChiSo();
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
      window.location.href = "/admin/taxon/add";
    },
    showFormEdit(data) {
      window.location.href = "/admin/taxon/" + data.id;
      // window.location.href = "/admin/taxon/add";
    },
    getData() {
      axios
        .get(env.endpointhttp + "admin/taxon/get", {
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
      if (this.filter.phan_loai_id == null && this.filter.nguy_co_id == null) {
        return false;
      } else return true;
    },
    deleteSearchFilter() {
      this.filter = {
        phan_loai_id: null,
        nguy_co_id: null,
      };
      this.showXoaBoLoc();
      this.timKiem();
    },
    getChiSo() {
      axios.get(env.endpointhttp + "admin/taxon/chiso").then((res) => {
        this.dataFilter = res.data;
      });
    },
    searchType(id, type) {
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

<style></style>
