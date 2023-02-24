<template>
  <div class="white-box p-0">
    <div class="page-aside">
      <div class="right-aside">
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
                  <th style="display: table-cell; min-width: 70px">Địa điểm</th>
                  <th width="100" style="text-align: center">Chi tiết</th>
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
                      {{
                        item.dia_diem
                          ? item.dia_diem.desig + " " + item.dia_diem.orig_name
                          : ""
                      }}
                    </div>
                  </td>
                  <td style="text-align: center">
                    <a @click="chiTiet(item.id)">Chi tiết</a>
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
  </div>
</template>

<script>
import * as env from "../../env.js";

export default {
  props: ["doiTuong"],

  data: () => ({
    page: 1,
    pageCount: 0,
    dataTable: [],
    filter: {
      khu_bao_ton_id: null,
      co_so_bao_ton_id: null,
      hanh_lang_id: null,
      da_dang_sinh_hoc_id: null,
      vung_dat_id: null,
      vung_chim_id: null,
      canh_quan_id: null,
      khu_du_tru_sinh_quyen_id: null,
    },
  }),
  mounted() {
    this.getData();
  },

  methods: {
    getData() {
      this.dataTable = [];
      this.pageCount = 0;
      if (this.doiTuong && this.doiTuong.name && this.doiTuong.id) {
        this.filter[this.doiTuong.name] = this.doiTuong.id;
      } else return;

      axios
        .get(env.endpointhttp + "hesinhthai/get", {
          params: {
            page: this.page,
            khu_bao_ton_id: this.filter.khu_bao_ton_id,
            co_so_bao_ton_id: this.filter.co_so_bao_ton_id,
            hanh_lang_id: this.filter.hanh_lang_id,
            da_dang_sinh_hoc_id: this.filter.da_dang_sinh_hoc_id,
            vung_dat_id: this.filter.vung_dat_id,
            vung_chim_id: this.filter.vung_chim_id,
            canh_quan_id: this.filter.canh_quan_id,
            khu_du_tru_sinh_quyen_id: this.filter.khu_du_tru_sinh_quyen_id,
          },
        })
        .then((res) => {
          console.log(res)
          this.dataTable = res.data.data;
          this.page = res.data.current_page;
          this.pageCount = res.data.last_page;
        });
    },
    chiTiet(id) {
      window.location.href = "/search/ecosystem/" + id;
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