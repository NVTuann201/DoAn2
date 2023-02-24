<template>
  <div class="white-box">
    <!-- .left-right-aside-column-->
    <div class="card-body">
      <components-loading-page v-if="loading"></components-loading-page>
      <components-notifications
        :typeInput="typeNotification"
        :textInput="textNotification"
      >
      </components-notifications>
      <!-- /.left-aside-column-->
      <button type="button" class="btn btn-default btn-rounded m-t-10 float-right" @click="goback()">
        <i class="fa fa-arrow-left"></i>
        Trở về
      </button>
      <button
        type="button"
        class="btn btn-default btn-rounded m-t-10 float-right"
        @click="downloadTemplate()"
      >
        Tải file mẫu
      </button>
      <button class="btn btn-info btn-file btn-rounded m-t-10">
        <i class="fa fa-paperclip"></i> Chọn file excel
        <input
          type="file"
          id="file"
          ref="file"
          v-on:change="uploadFile()"
          accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
        />
      </button>
      <a
        v-if="this.arrayLoi.length != 0"
        type="button"
        class="btn btn-default btn-rounded m-t-10 float-right"
      >
        <download-excel
          :data="this.arrayLoi"
          name="import_loi.xls"
          :fields="field_excel"
        >
          Tải file báo cáo lỗi
        </download-excel>
      </a>
      <div class="right-page-header">
        <div class="scrollable">
          <div class="table-responsive">
            <table
              class="
                table
                m-t-30
                table-hover
                contact-list
                footable footable-1 footable-paging footable-paging-center
                breakpoint-lg
              "
            >
              <thead>
                <tr class="footable-header">
                  <th class="text-center">{{ $t("admin.label.no") }}</th>
                  <th style="display: table-cell; min-width: 70px">
                    {{ $t("nbds_lang.darwin_core_taxons.scientific_name") }}
                  </th>
                  <!-- <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_taxons.taxon_id") }}
                  </th> -->
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_taxons.kingdom") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_taxons.phylum") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_taxons.class") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_taxons.order") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_taxons.family") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_taxons.genus") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_taxons.species_id") }}
                  </th>
                  <th style="display: table-cell">
                    Phân bố Hà Nội
                    <!-- {{ $t("nbds_lang.nbds_taxon_extensions.phan_bo_ha_noi") }} -->
                  </th>
                  <!-- <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_taxons.taxon_rank") }}
                  </th> -->
                  <th style="display: table-cell">
                    {{
                      $t(
                        "nbds_lang.darwin_core_taxons.scientific_name_authorship"
                      )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_taxons.vernacular_name") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                      $t("nbds_lang.darwin_core_taxons.vernacular_name_english")
                    }}
                  </th>

                  <th style="display: table-cell">
                    {{ $t("nbds_lang.label_adv_srch_iucn") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.nbds_taxon_extensions.red_list") }}
                  </th>
                  <th style="display: table-cell">Nghị Định</th>
                  <th style="display: table-cell">
                    {{
                      $t(
                        "nbds_lang.nbds_taxon_extensions.provenance_in_vietnam"
                      )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                      $t("nbds_lang.nbds_taxon_extensions.provenance_in_local")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.nbds_taxon_extensions.natural") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.nbds_taxon_extensions.invasive_status") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                      $t(
                        "nbds_lang.nbds_taxon_extensions.environmentally_sensitive"
                      )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.nbds_taxon_extensions.rich_and_rare") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.nbds_taxon_extensions.population") }}
                  </th>
                  <th style="display: table-cell">Cites</th>
                  <th style="display: table-cell">
                    {{
                      $t(
                        "nbds_lang.nbds_taxon_extensions.other_vietnamese_law_to_protect_species"
                      )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.nbds_taxon_extensions.use") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                      $t(
                        "nbds_lang.nbds_taxon_extensions.morphological_description"
                      )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.nbds_taxon_extensions.habitat") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                      $t(
                        "nbds_lang.nbds_taxon_extensions.distribution_in_vietnam"
                      )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.nbds_taxon_extensions.local_endemism") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                      $t(
                        "nbds_lang.nbds_taxon_extensions.classification_of_behavioral_features"
                      )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.nbds_taxon_extensions.pressure") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                      $t("nbds_lang.nbds_taxon_extensions.competing_species")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                      $t("nbds_lang.nbds_taxon_extensions.symbiotic_species")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.nbds_taxon_extensions.protected") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.nbds_taxon_extensions.other_porotectec") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                      $t("nbds_lang.nbds_taxon_extensions.protective_measures")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.nbds_taxon_extensions.benefit") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.nbds_taxon_extensions.other") }}
                  </th>

                  <th style="display: table-cell">
                    {{ $t("nbds_lang.nbds_taxon_extensions.references") }}
                  </th>
                  <th style="display: table-cell; min-width: 800px">
                    {{ $t("nbds_lang.nbds_taxon_extensions.note") }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(item, index) in detailtaxon"
                  style="cursor: context-menu"
                  :key="item.id"
                  v-if="detailtaxon"
                >
                  <td>
                    <span class="text-center-td">{{
                      (page - 1) * 50 + index + 1
                    }}</span>
                  </td>
                  <td>
                    <div style="white-space: initial">
                      {{ item.scientific_name }}
                    </div>
                  </td>
                  <!-- <td>{{ item.taxon_id }}</td> -->
                  <td>{{ item.king_dom.name }}</td>
                  <td>{{ item.phylum.name }}</td>
                  <td>{{ item.class.name }}</td>
                  <td>{{ item.order.name }}</td>
                  <td>{{ item.family.name }}</td>
                  <td>{{ item.genus.name }}</td>
                  <td>{{ item.species.name }}</td>
                  <td>{{ item.nbds_taxon_extension_first ? item.nbds_taxon_extension_first.phan_bo_ha_noi : null }}</td>
                  <!-- <td>{{ item.taxon_rank }}</td> -->
                  <td>{{ item.scientific_name_authorship }}</td>
                  <td>{{ item.vernacular_name }}</td>
                  <td>{{ item.vernacular_name_english }}</td>
                  <td>
                    {{
                      item.nbds_taxon_extension_first &&
                      item.nbds_taxon_extension_first.iucn_2012
                    }}
                  </td>
                  <td>
                    {{
                      item.nbds_taxon_extension_first &&
                      item.nbds_taxon_extension_first.red_list
                    }}
                  </td>
                  <td>
                    {{
                      item.nbds_taxon_extension_first &&
                      item.nbds_taxon_extension_first.nghi_dinh
                    }}
                  </td>
                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first.provenance_in_vietnam
                      }}
                    </div>
                  </td>
                  <td>
                    {{
                      item.nbds_taxon_extension_first &&
                      item.nbds_taxon_extension_first.provenance_in_local
                    }}
                  </td>
                  <td>
                    {{
                      item.nbds_taxon_extension_first &&
                      item.nbds_taxon_extension_first.natural
                    }}
                  </td>
                  <td>
                    {{
                      item.nbds_taxon_extension_first &&
                      item.nbds_taxon_extension_first.invasive_status
                    }}
                  </td>
                  <td>
                    {{
                      item.nbds_taxon_extension_first &&
                      item.nbds_taxon_extension_first.environmentally_sensitive
                    }}
                  </td>
                  <td>
                    {{
                      item.nbds_taxon_extension_first &&
                      item.nbds_taxon_extension_first.rich_and_rare
                    }}
                  </td>
                  <td>
                    {{
                      item.nbds_taxon_extension_first &&
                      item.nbds_taxon_extension_first.population
                    }}
                  </td>
                  <td>
                    {{
                      item.nbds_taxon_extension_first &&
                      item.nbds_taxon_extension_first.cites
                    }}
                  </td>
                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first
                          .other_vietnamese_law_to_protect_species
                      }}
                    </div>
                  </td>
                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first.use
                      }}
                    </div>
                  </td>
                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first
                          .morphological_description
                      }}
                    </div>
                  </td>
                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first.habitat
                      }}
                    </div>
                  </td>
                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first.distribution_in_vietnam
                      }}
                    </div>
                  </td>
                  <td>
                    {{
                      item.nbds_taxon_extension_first &&
                      item.nbds_taxon_extension_first.local_endemism
                    }}
                  </td>
                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first
                          .classification_of_behavioral_features
                      }}
                    </div>
                  </td>

                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first.pressure
                      }}
                    </div>
                  </td>
                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first.competing_species
                      }}
                    </div>
                  </td>
                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first.symbiotic_species
                      }}
                    </div>
                  </td>
                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first.protected
                      }}
                    </div>
                  </td>
                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first.other_porotectec
                      }}
                    </div>
                  </td>
                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first.protective_measures
                      }}
                    </div>
                  </td>
                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first.benefit
                      }}
                    </div>
                  </td>
                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first.other
                      }}
                    </div>
                  </td>

                  <td>
                    <div class="white-space-initial">
                      {{ item.references }}
                    </div>
                  </td>
                  <td>
                    <div class="white-space-initial">
                      {{
                        item.nbds_taxon_extension_first &&
                        item.nbds_taxon_extension_first.note
                      }}
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <tr v-if="count > 1" class="footable-paging">
        <td colspan="6">
          <paginate
            v-model="page"
            :page-count="this.count"
            :page-range="3"
            :margin-pages="2"
            :click-handler="clickCallback"
            :prev-text="'‹‹'"
            :next-text="'››'"
            :container-class="'pagination'"
            :page-class="'page-item'"
          ></paginate>
        </td>
      </tr>
      <!-- .left-aside-column-->

      <!-- /.left-right-aside-column-->
    </div>
  </div>
</template>

<script>
import * as env from "../../../../env.js";
import * as routes from "../../../../routes.js";
import { endpoint, endpointhttp } from "../../../../env.js";

export default {
  props: ["value"],
  data: function () {
    return {
      arrayLoi: [],
      confirmPopUp: false,
      typeNotification: null,
      textNotification: null,
      resetData: false,
      detailtaxon: null,
      page: 1,
      count: 0,
      per_page: 0,
      current_page: 0,
      loading: false,
      file: "",
      field_excel: {
        Dòng: "dong",
        Lỗi: "loi",
        "Tên khoa học": "ten_khoa_hoc",
        "ID loài": "id_loai",
        Giới: "gioi",
        Ngành: "nganh",
        Lớp: "lop",
        Bộ: "bo",
        Họ: "ho",
        "Chi/Giống": "chigiong",
        Loài: "Loài",
        "Phân loài": "phan_loai",
        "Cấp bậc phân loài": "cap_bac_phan_loai",
        "Tác giả": "tac_gia",
        "Từ đồng nghĩa": "tu_dong_nghia",
        "Tên tiếng Việt": "ten_tieng_viet",
        "Tên tiếng Anh": "ten_tieng_anh",
        "IUCN 2012": "iucn_2012",
        "Sách đỏ Việt Nam 2007": "sach_do_viet_nam_2007",
        "Phân hạng theo sách đỏ": "phan_hang_theo_sach_do",
        "Nghị định": "nghi_dinh",
        "Phụ lục nghị định": "phu_luc_nghi_dinh",
        "Nguồn gốc ở Việt Nam": "nguon_goc_o_viet_nam",
        "Nguồn gốc ở Địa Phương": "nguon_goc_o_dia_phuong",
        "Tự nhiên": "tu_nhien",
        "Xâm lấn": "xam_lan",
        "Độ nhạy với sinh thái/môi trường": "do_nhay_voi_sinh_thaimoi_truong",
        "Sự phong phú/quý hiếm": "do_nhay_voi_sinh_thaimoi_truong",
        "Số lượng cá thể": "so_luong_ca_the",
        CITES: "cites",
        "Luật Việt Nam về bảo tồn loài": "luat_viet_nam_ve_bao_ton_loai",
        "Sử dụng": "su_dung",
        "Mô tả đặc tính sinh học": "mo_ta_dac_tinh_sinh_hoc",
        "Môi trường sống": "moi_truong_song",
        "Phân bố ở Việt Nam": "phan_bo_o_viet_nam",
        "Tính đặc hữu": "tinh_dac_huu",
        "Phân loại các đặc tính về hành vi":
          "phan_loai_cac_dac_tinh_ve_hanh_vi",
        "Áp lực": "ap_luc",
        "Loài cạnh tranh": "loai_canh_tranh",
        "Loài cộng sinh": "loai_cong_sinh",
        "Loài được bảo vệ": "loai_duoc_bao_ve",
        "Loài đang bảo vệ": "loai_dang_bao_ve",
        "Các biện pháp bảo tồn": "cac_bien_phap_bao_ton",
        "Lợi ích kinh tế": "loi_ich_kinh_te",
        "Vấn đề khác": "van_de_khac",
        "Tham khảo": "tham_khao",
        "Ghi chú": "ghi_chu",
      },
    };
  },
  mounted() {
    this.getDatasetDetail();
  },
  methods: {
    goback(){
      window.location="/admin/dataset"
    },
    getDatasetDetail() {
      this.loading = true;
      this.page = 1;
      this.resetData = false;
      axios
        .get(env.endpointhttp + "admin/dataset/detail/taxon/info/" + this.value)
        .then((response) => {
          this.detailtaxon = response.data.result.data;
          // console.log(this.detailtaxon.find(item=>item.nbds_taxon_extension_first!=null))
          this.count = response.data.result.last_page;
          this.per_page = response.data.result.per_page;
          this.current_page = response.data.result.current_page;
          this.loading = false;
        });
    },
    downloadTemplate() {
      axios
        .get(env.endpointhttp + "download/template/taxon", {
          responseType: "blob",
        })
        .then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "file_mau_taxon.xlsx");
          document.body.appendChild(link);
          link.click();
        });
    },
    uploadFile() {
      this.loading = true;
      this.file = this.$refs.file.files[0];
      let formData = new FormData();
      formData.append("file", this.file);
      axios
        .post(
          env.endpointhttp + "admin/dataset/taxon/upload/" + this.value,
          formData
        )
        .then((response) => {
          if (response.data.message == "message.success") {
            this.arrayLoi = response.data.result;
            this.typeNotification = 2;
            this.textNotification = "Cập nhật thành công.";
            this.getDatasetDetail();
          } else {
            this.typeNotification = 1;
            this.textNotification = "Cập nhật không thành công.";
          }
        })
        .catch((error) => {
          this.typeNotification = 1;
          this.textNotification = "Cập nhật không thành công.";
        })
        .finally(() => {
          this.loading = false;
          this.typeNotification = null;
          this.textNotification = null;
          this.$refs.file.value = "";
        });
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      axios
        .get(
          env.endpointhttp + "admin/dataset/detail/taxon/info/" + this.value,
          {
            params: {
              page: this.page,
            },
          }
        )
        .then((response) => {
          this.detailtaxon = response.data.result.data;
          this.count = response.data.result.last_page;
          this.per_page = response.data.result.per_page;
          this.current_page = response.data.result.current_page;
        });
    },
  },
};
</script>
<style scoped>
.white-space-initial {
  white-space: initial;
}
</style>
