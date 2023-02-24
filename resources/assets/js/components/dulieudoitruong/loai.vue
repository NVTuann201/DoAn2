<template>
  <div>
    <div class="scrollable">
      <div class="table-responsive">
        <table class="table search-table smaller">
          <thead>
            <tr>
              <th style="width: 60px">STT</th>
              <th>
                {{ $t("nbds_lang.darwin_core_taxons.scientific_name") }}
              </th>
              <th>{{ $t("nbds_lang.darwin_core_taxons.kingdom_id") }}</th>
              <th>{{ $t("nbds_lang.darwin_core_taxons.phylum_id") }}</th>
              <th>{{ $t("nbds_lang.darwin_core_taxons.class_id") }}</th>
              <th>{{ $t("nbds_lang.darwin_core_taxons.order_id") }}</th>
              <th>{{ $t("nbds_lang.darwin_core_taxons.family_id") }}</th>
              <th>{{ $t("nbds_lang.darwin_core_taxons.genus_id") }}</th>
              <th>{{ $t("nbds_lang.darwin_core_taxons.species_id") }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="dataTable && dataTable.length == 0">
              <td colspan="6" class="emptyInfomation">
                <h5>Dữ liệu đang được cập nhật, bạn vui lòng truy cập lại sau</h5>
              </td>
            </tr>
            <tr
              class="lastCellFlexible rtl-supported"
              v-for="(item, index) in dataTable"
              style="cursor: pointer"
              :key="item.id"
              @click="goDetailTaxon(item.id)"
            >
              <td>
                <span class="text-center-td">{{ index + 1 }}</span>
              </td>
              <td class="table-cell--wide">
                <a
                  ><i>{{ item.scientific_name }}</i></a
                >
              </td>
              <td class="">
                <a v-if="item.king_dom">{{ item.king_dom.name }}</a>
              </td>
              <td class="">
                <a v-if="item.phylum">{{ item.phylum.name }}</a>
              </td>
              <td class="">
                <a v-if="item.class">{{ item.class.name }}</a>
              </td>
              <td class="">
                <a v-if="item.order">{{ item.order.name }}</a>
              </td>
              <td class="">
                <a v-if="item.family">{{ item.family.name }}</a>
              </td>
              <td class="">
                <a v-if="item.genus">{{ item.genus.name }}</a>
              </td>
              <td class="">
                <a v-if="item.species"
                  >{{ item.species.name }}
                  <template v-if="item.species.name_vietnamese"
                    >({{ item.species.name_vietnamese }})</template
                  >
                </a>
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
    goDetailTaxon(value) {
      window.location.href = "/detail/taxon/" + value;
    },
    getData() {
      this.dataTable = [];
      this.pageCount = 0;
      if (this.doiTuong && this.doiTuong.name && this.doiTuong.id) {
        this.filter[this.doiTuong.name] = this.doiTuong.id;
      } else return;
      axios
        .get(env.endpointhttp + "/taxon", {
          params: {
            page: this.page,
            per_page: 15,
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
          this.dataTable = res.data.result.data;
          this.page = res.data.result.current_page;
          this.pageCount = res.data.result.last_page;
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