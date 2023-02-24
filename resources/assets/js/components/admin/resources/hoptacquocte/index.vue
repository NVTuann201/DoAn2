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
              {{ $t("admin.label.filter") }}
            </div>
            <button
              type="button"
              class="btn-sm btn-info btn-rounded"
              v-if="showXoaBoLoc()"
              @click="deleteSearchFilter()"
            >
              <span>{{ $t("admin.label.reset_filter") }}</span>
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
              Năm ban hành
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
            <!-- <div
              @click="isStatus = -isStatus"
              data-toggle="collapse"
              data-target=".filter4"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              "
            >
              Tính chất
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
              v-for="item in tinhChats"
              :key="item.id"
            >
              <a
                @click="searchTinhChat(item.code)"
                style="cursor: pointer"
                :style="
                  filter.tinh_chat == item.code
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
                {{ item.name }}
                <span></span>
              </a>
            </li> -->
          </ul>
        </div>
      </div>
      <div class="right-aside">
        <div class="d-flex">
          <div style="flex: 1; font-size: 18px; font-weight: bold">
            Danh sách hợp tác quốc tế và Khoa học công nghệ
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
                    Văn bản/ Dự án
                  </th>
                  <th style="display: table-cell; min-width: 70px">
                    Thời gian
                  </th>
                  <th style="display: table-cell; min-width: 70px">Đối tác</th>
                  <th style="display: table-cell; min-width: 70px">
                    Cơ quan chủ trì
                  </th>
                     <th style="display: table-cell; width: 130px">Hành động</th>
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
                        <div style="font-size: 16px; font-weight: 400; max-width: 400px; overflow:clip">
                          {{ item.ten }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>
                      <div>Ngày ban hành: {{ item.ngay_ban_hanh }}</div>
                      <div>Ngày hiệu lực: {{ item.ngay_hieu_luc }}</div>
                      <div>Ngày hết hạn: {{ item.ngay_het_han }}</div>
                    </div>
                  </td>
                  <td>{{ item.doi_tac }}</td>
                  <td>
                    <div>
                      {{ item.co_quan_chu_tri }}
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
                      @click="showXoa(item)"
                      type="button"
                      title="Xóa"
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
    page: 1,
    pageCount: 0,
    dataTable: [],
    confirmDelete: false,
    idDelete: null,
    delLink: null,
    contentDelete: "Bạn có chắc chắn muốn xóa dữ liệu này?",
    tinhChats: [
      { name: "Đa phương", code: "da_phuong" },
      { name: "Song phương", code: "song_phuong" },
    ],
    isStatus: 1,
    isBanHanh: 1,
    isHieuLuc: 1,
    isThamGia: 1,
    searchDatasetType: null,
    dataFilter: {},
    filter: {
      tinh_chat: null,
      nam_het_han: null,
      nam_ban_hanh: null,
    },
  }),
  mounted() {
    this.getData();
  },

  methods: {
    showFormAdd() {
      window.location.href = "/admin/hoptacquocte/showadd/";
    },
    showXoa(data) {
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa dự án hợp tác " +
        data.ten +
        " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/hoptacquocte/delete/";
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
    showFormEdit(id) {
      window.location.href = "/admin/hoptacquocte/edit/" + id;
    },
    getData() {
      axios
        .get(env.endpointhttp + "admin/hoptacquocte/list", {
          params: {
            page: this.page,
            search: this.search,
            tinh_chat: this.filter.tinh_chat,
            nam_ban_hanh: this.filter.nam_ban_hanh,
            nam_het_han: this.filter.nam_het_han,
          },
        })
        .then((res) => {
          this.dataTable = res.data.data;
          this.page = res.data.current_page;
          this.pageCount = res.data.last_page;
        });
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.getData();
    },
    timKiem() {
      this.page = 1;
      this.getData();
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
        tinh_chat: null,
        nam_het_han: null,
        nam_ban_hanh: null,
      };
      this.showXoaBoLoc();
      this.timKiem();
    },
    searchTinhChat(tinhChat) {
      if (this.filter.tinh_chat === tinhChat) {
        this.filter.tinh_chat = null;
      } else {
        this.filter.tinh_chat = tinhChat;
      }
      this.timKiem();
    },
  },
};
</script>

<style>
</style>