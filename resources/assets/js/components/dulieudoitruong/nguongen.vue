<template>
  <div>
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
                Tên nguồn gen
              </th>
              <th style="display: table-cell">Nhóm/Loại</th>
              <th style="display: table-cell; min-width: 70px">Đặc điểm</th>
              <th style="display: table-cell; min-width: 70px">Xuất xứ</th>
              <th width="100" style="text-align: center; min-width: 70px">
                Chi tiết
              </th>
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
                      {{ item.ten }}
                    </div>
                    <div style="font-weight: 400; font-size: 14px">
                      Tên khoa học: {{ item.ten_khoa_hoc }}
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <div>
                  <div>Nhóm: {{ item.nhom_gen ? item.nhom_gen.ten : "" }}</div>
                  <div>Loại: {{ item.loai_gen ? item.loai_gen.ten : "" }}</div>
                </div>
              </td>
              <td>
                <div v-if="item.dac_diem && item.dac_diem.length > 80">
                  {{ item.dac_diem.substring(0, 80) + "..." }}
                </div>
                <div v-else>{{ item.dac_diem }}</div>
              </td>
              <td>
                <div>
                  {{ item.xuat_xu }}
                </div>
              </td>
              <td style="text-align: center">
                <a @click="chiTietGen(item.id)">Chi tiết</a>
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
</template>

<script>
import * as env from "../../env.js";

export default {
  props: ["doiTuong"],
  data: () => ({
    dataTable: [],
    page: 1,
    pageCount: 0,
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
    clickCallback(pageNum) {
      this.page = pageNum;
      this.getData();
    },
    getData() {
      this.dataTable = [];
      this.page = 1;
      this.pageCount = 0;
      if (this.doiTuong && this.doiTuong.name && this.doiTuong.id) {
        this.filter[this.doiTuong.name] = this.doiTuong.id;
      } else return;
      axios
        .get(env.endpointhttp + "genetic/getdata", {
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
          this.dataTable = res.data.data;
          this.page = res.data.current_page;
          this.pageCount = res.data.last_page;
        });
    },
    chiTietGen(id) {
      window.location.href = "/detail/genetic/" + id;
    },
  },
};
</script>

<style>
</style>