<template>
  <div class="white-box p-0">
    <components-notifications
      :typeInput="typeNotification"
      :textInput="textNotification"
    ></components-notifications>
    <div class="loading-roles" v-if="loading">
      <components-loading-page></components-loading-page>
    </div>
    <div style="padding: 30px">
      <div class="d-flex">
        <div style="flex: 1; font-size: 18px; font-weight: bold">
          Danh sách quận huyện trực thuộc thành phố Hà Nội
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
                  Tên quận huyện
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="quanHuyens && quanHuyens.length == 0">
                <td colspan="6" class="emptyInfomation">
                  <h5>Dữ liệu đang được cập nhật, bạn vui lòng truy cập lại sau</h5>
                </td>
              </tr>
              <tr
                v-for="(item, index) in quanHuyens"
                :key="index"
                style="cursor: context-menu"
              >
                <td>
                  <!-- <span class="text-center-td">{{(page - 1)*10 + index + 1}}</span> -->
                  <div>{{ index + 1 }}</div>
                </td>
                <td>
                  <div style="white-space: initial">
                    <div style="font-weight: 400; font-size: 15px">
                      {{ item.name_vietnamese }}
                    </div>
                  </div>
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
  </div>
</template>

<script>
import * as env from "../../../../env.js";

export default {
  data: () => ({
    showFormNhomGen: false,
    contentDelete: "",
    confirmDelete: false,
    editNhomGen: false,
    resetData: false,
    deleteTitle: "Xóa dữ liệu",
    idDelete: null,
    search: null,
    page: 1,
    pageCount: 0,
    nhomGens: [],
    typeNotification: null,
    textNotification: null,
    loading: false,
    delLink: "",
    quanHuyens: [],
  }),
  mounted() {
    this.getData();
  },
  methods: {
    timKiem() {
      this.page = 1;
      this.getData();
    },
    getData() {
      axios
        .get(env.endpointhttp + "admin/quanhuyenhanoi", {
          params: {
            page: this.page,
            search: this.search,
          },
        })
        .then((res) => {
          this.quanHuyens = res.data.data;
          this.page = res.data.current_page;
          this.pageCount = res.data.last_page;
        });
    },

    clickCallback(pageNum) {
      this.page = pageNum;
      this.getData();
    },
  },
};
</script>

<style>
</style>