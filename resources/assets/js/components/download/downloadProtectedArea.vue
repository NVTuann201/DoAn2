<template>
  <download-excel
    :data="this.value"
    :before-generate="startDownload"
    :before-finish="finishDownload"
    name="protectedarea.xls"
    :fields="protectedarea_fields"
    title="Danh sách khu bảo tồn"
  >
    <span class="gb-icon-file-download"></span>
    <span>{{ $t("nbds_lang.download") }}</span>
    <svg v-if="load" class="circular mini load_download" viewBox="25 25 50 50">
      <circle
        class="path"
        cx="50"
        cy="50"
        r="20"
        style="stroke: rgba(0, 0, 0, 0.8)"
        fill="none"
        stroke-width="2"
        stroke-miterlimit="10"
      ></circle>
    </svg>
  </download-excel>
</template>

<script>
import * as env from "../../env.js";
export default {
  props: ["value"],
  data: function () {
    return {
      load: false,
      protectedarea_fields: {
        "Mã số WDPA": "wdpaid",
        "Mã số chính WDPA": "wdpa_pid",
        "Tên tiếng việt": "name",
        Tỉnh: "sub_loc",
        "Phân loại": "desig",
        "Phân định tiếng anh": "desig_eng",
        "Cấp độ đa dạng sinh học": "desig_type",
        "Danh lục IUCN": "iucncat",
        Biển: "marine",
        "Diện tích biển được báo cáo": "rep_m_area",
        "Tổng diện tích báo cáo": "rep_area",
        "Công nhận": "status",
        "Năm công nhận": "status_year",
        "Phân cấp quản lý": "gov_type",
        "Phân quyền quản lý": "mang_auth",
        "Tiêu chuẩn quốc tế": "int_crit",
        "Kế hoạch quản lý": "mang_plan",
        "ID siêu dữ liệu": "metadataid",
        "Kinh độ": "longitude",
        "Vĩ độ": "latitude",
        "Pháp lý": "legislation",
        "Mô tả": "description",
        "Ngày công bố": "created_at",
      },
    };
  },
  methods: {
    startDownload() {
      this.load = true;
    },
    finishDownload() {
      this.load = false;
    },
  },
};
</script>
