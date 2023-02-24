/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css";
import "./bootstrap";
import "babel-polyfill";

import { BootstrapVue, IconsPlugin, VBTooltip } from "bootstrap-vue";
import { Tab, Tabs } from "vue-tabs-component";

import ClickOutside from "vue-click-outside";
import JsonExcel from "vue-json-excel";
import VeeValidate from "vee-validate";
import Vue from "vue";
import VueHighcharts from "vue-highcharts";
import VueI18n from "vue-i18n";
import VueMoment from "vue-moment";
import Vuetify from "vuetify";
import datePicker from "vue-bootstrap-datetimepicker";
import enMessage from "./lang/en.json";
import jpMessage from "./lang/jp.json";
import mapboxgl from "mapbox-gl";
import store from "./store";
import vClickOutside from "v-click-outside";
import vnMessage from "./lang/vn.json";
import VueApexCharts from 'vue-apexcharts'

Vue.directive("b-tooltip", VBTooltip);

Vue.component("tabs", Tabs);
Vue.component("tab", Tab);
window.mapboxgl = mapboxgl;
window.Vue = require("vue");

// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue);
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);
Vue.use(vClickOutside);
Vue.use(datePicker);
Vue.use(VeeValidate, { fieldsBagName: "formFields" });
Vue.use(VueMoment);
Vue.use(Vuetify);
Vue.use(ClickOutside);
Vue.use(VueHighcharts);
Vue.component("downloadExcel", JsonExcel);
Vue.use(VueApexCharts)

Vue.component('apexchart', VueApexCharts)

const messages = {
  vn: vnMessage,
  jp: jpMessage,
  en: enMessage,
};

const i18n = new VueI18n({
  locale: "vn", // set locale
  messages,
  fallbackLocale: "vn",
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component("paginate", require("vuejs-paginate"));
Vue.component("PageFilter", require("./components/layouts/page-filter.vue"));

Vue.component("login-component", require("./components/login.vue"));
Vue.component(
  "components-loading",
  require("./components/admin/components/loading.vue")
);
Vue.component(
  "components-loading-mini",
  require("./components/admin/components/loadingMini.vue")
);
Vue.component(
  "components-loading-page",
  require("./components/admin/components/loadingPage.vue")
);
Vue.component(
  "components-notifications",
  require("./components/admin/components/notifications.vue")
);
Vue.component("home-component", require("./components/homeComponent.vue"));
// Vue.component(
//   "footer-component",
//   require("./components/layouts/footerComponent.vue")
// );
Vue.component(
  "header-component",
  require("./components/layouts/headerComponent.vue")
);

Vue.component(
  "auth-profile-index",
  require("./components/admin/auth/profile/index.vue")
);
Vue.component(
  "system-user-index",
  require("./components/admin/system/user/index.vue")
);
Vue.component(
  "system-user-add",
  require("./components/admin/system/user/add.vue")
);
Vue.component(
  "user-update-component",
  require("./components/admin/system/user/update.vue")
);

Vue.component(
  "system-role-index",
  require("./components/admin/system/role/index.vue")
);
Vue.component(
  "system-role-update",
  require("./components/admin/system/role/update.vue")
);
Vue.component(
  "system-role-add",
  require("./components/admin/system/role/add.vue")
);

Vue.component(
  "system-tools-index",
  require("./components/admin/system/tools/index.vue")
);

Vue.component(
  "system-menu-index",
  require("./components/admin/system/menu/index.vue")
);
Vue.component(
  "system-menu-add",
  require("./components/admin/system/menu/add.vue")
);
Vue.component(
  "system-menu-update",
  require("./components/admin/system/menu/update.vue")
);

Vue.component(
  "system-function-add",
  require("./components/admin/system/function/add.vue")
);
Vue.component(
  "system-function-index",
  require("./components/admin/system/function/index.vue")
);
Vue.component(
  "system-function-update",
  require("./components/admin/system/function/update.vue")
);

Vue.component(
  "system-organization-index",
  require("./components/admin/system/organization/index.vue")
);
Vue.component(
  "system-organization-add",
  require("./components/admin/system/organization/add.vue")
);
Vue.component(
  "system-organization-update",
  require("./components/admin/system/organization/update.vue")
);

Vue.component(
  "system-userlog-index",
  require("./components/admin/system/userlog/index.vue")
);

Vue.component(
  "category-species-index",
  require("./components/admin/category/species/index.vue")
);
Vue.component(
  "category-species-add",
  require("./components/admin/category/species/add.vue")
);
Vue.component(
  "category-species-update",
  require("./components/admin/category/species/update.vue")
);

Vue.component(
  "category-dataset-index",
  require("./components/admin/category/dataset/index.vue")
);
Vue.component(
  "category-dataset-add",
  require("./components/admin/category/dataset/add.vue")
);
Vue.component(
  "category-dataset-update",
  require("./components/admin/category/dataset/update.vue")
);
Vue.component(
  "category-dataset-detail-taxon",
  require("./components/admin/category/dataset/detailtaxon.vue")
);
Vue.component(
  "category-dataset-detail-occurrences",
  require("./components/admin/category/dataset/detailoccurrences.vue")
);

Vue.component(
  "category-protectedarea-index",
  require("./components/admin/category/protectedarea/index.vue")
);
Vue.component(
  "category-protectedarea-add",
  require("./components/admin/category/protectedarea/add.vue")
);
Vue.component(
  "category-protectedarea-update",
  require("./components/admin/category/protectedarea/update.vue")
);

Vue.component(
  "search-everything-component",
  require("./components/searchEverythingComponent.vue")
);
Vue.component(
  "search-dataset-component",
  require("./components/dataset/index.vue")
);
Vue.component(
  "detail-dataset-component",
  require("./components/dataset/detail/detail.vue")
);
Vue.component(
  "search-publisher-component",
  require("./components/publisher/index.vue")
);
Vue.component(
  "detail-publisher-component",
  require("./components/publisher/detail/index.vue")
);
Vue.component(
  "search-protectedarea-component",
  require("./components/protectedarea/index.vue")
);
Vue.component(
  "detail-protectedarea-component",
  require("./components/protectedarea/detail/detail.vue")
);
Vue.component(
  "detail-occurrence-component",
  require("./components/occurrence/detail/index.vue")
);
Vue.component(
  "search-occurrence-component",
  require("./components/occurrence/index.vue")
);

Vue.component(
  "search-species-component",
  require("./components/species/searchSpeciesComponent.vue")
);
Vue.component(
  "detail-species-component",
  require("./components/species/detail/detailSpeciesComponent.vue")
);

Vue.component(
  "search-taxon-component",
  require("./components/taxon/index.vue")
);
Vue.component(
  "detail-taxon-component",
  require("./components/taxon/detail/index.vue")
);

Vue.component(
  "components-confirm",
  require("./components/admin/components/popUpConfirm.vue")
);

Vue.component(
  "download-dataset",
  require("./components/download/downloadDataset.vue")
);
Vue.component(
  "download-publisher",
  require("./components/download/downloadPublisher.vue")
);
Vue.component(
  "download-protectedarea",
  require("./components/download/downloadProtectedArea.vue")
);
Vue.component(
  "download-occurrence",
  require("./components/download/downloadOccurrence.vue")
);
Vue.component(
  "download-taxon",
  require("./components/download/downloadTaxon.vue")
);

Vue.component("tool-species", require("./components/species/toolSpecies.vue"));
Vue.component(
  "tool-occurrence",
  require("./components/occurrence/toolOccurrence.vue")
);
Vue.component("tool-summary", require("./components/toolSummary.vue"));
Vue.component("tool-category", require("./components/toolCategory.vue"));

Vue.component(
  "profile-component",
  require("./components/profileComponent.vue")
);
Vue.component(
  "translate-component",
  require("./components/translateComponent.vue")
);

Vue.component("modal", require("./components/admin/components/modal.vue"));

Vue.component(
  "search-genetic-resources",
  require("./components/genetic/resources/index.vue")
);
Vue.component(
  "detail-gennetic-component",
  require("./components/genetic/resources/detail.vue")
);

Vue.component(
  "search-genetic-knowledge",
  require("./components/genetic/knowledge/index.vue")
);

Vue.component(
  "search-ecosystem",
  require("./components/ecosystem/ecosystem/index.vue")
);
Vue.component(
  "search-ecosystem-data",
  require("./components/conservation-area/ecosystem/index/index_old.vue")
);
Vue.component(
  "search-service",
  require("./components/ecosystem/service/index.vue")
);

Vue.component(
  "search-standardzone",
  require("./components/protectedarea/standardzone/index.vue")
);
Vue.component(
  "search-conservation-infrastructure",
  require("./components/conservation-area/conservation-infrastructure/index/index.vue")
);
Vue.component(
  "search-ecosystem",
  require("./components/conservation-area/ecosystem/index/index.vue")
);
Vue.component(
  "search-map",
  require("./components/conservation-area/map/index.vue")
);
Vue.component(
  "search-map-v2",
  require("./components/conservation-area/map/index_v2.vue")
);
Vue.component(
  "search-map-v3",
  require("./components/conservation-area/map/index_v3.vue")
);

Vue.component(
  "dashboard-home",
  require("./components/dashboard/index.vue")
);

Vue.component(
  "search-annual-budget",
  require("./components/conservation-efforts/annual-budget/index/index.vue")
);
Vue.component(
  "search-ecosystem-service",
  require("./components/conservation-efforts/ecosystem-service/index/index.vue")
);
// Vue.component(
//   "search-genetic-knowledge",
//   require("./components/conservation-efforts/genetic-knowledge/index/index.vue")
// );
Vue.component(
  "search-sinhvat-ngoailai",
  require("./components/sinhvatngoailai/index.vue")
);

Vue.component(
  "dia-gioi",
  require("./components/admin/resources/diagioi/index.vue")
);

Vue.component(
  "search-biodiversity-conservation-initiative",
  require("./components/conservation-efforts/biodiversity-conservation-initiative/index/index.vue")
);

Vue.component(
  "search-control-of-alien-organisms",
  require("./components/conservation-efforts/control-of-alien-organisms/index/index.vue")
);
Vue.component(
  "search-international-cooperation",
  require("./components/conservation-efforts/international-cooperation/index/index.vue")
);
Vue.component(
  "detail-international-cooperation",
  require("./components/conservation-efforts/international-cooperation/detail/detail.vue")
);
Vue.component(
  "search-legislation-documents",
  require("./components/conservation-efforts/legislation-documents/index/index.vue")
);
Vue.component(
  "detail-legislation-documents",
  require("./components/conservation-efforts/legislation-documents/detail/detail.vue")
);
Vue.component(
  "search-standard-zone",
  require("./components/conservation-area/standard-zone/index/index.vue")
);
Vue.component(
  "detail-conservation-infrastructure",
  require("./components/conservation-area/conservation-infrastructure/detail/detail.vue")
);
Vue.component(
  "detail-ecosystem",
  require("./components/conservation-area/ecosystem/detail/detail.vue")
);

Vue.component(
  "search-cosobaoton",
  require("./components/protectedarea/cosobaoton/index.vue")
);
Vue.component(
  "search-hanhlang",
  require("./components/protectedarea/hanhlang/index.vue")
);
Vue.component(
  "search-highbiodiversity",
  require("./components/protectedarea/highbiodiversity/index.vue")
);
Vue.component(
  "search-importantwetland",
  require("./components/protectedarea/wetland/index.vue")
);
Vue.component(
  "search-bridarea",
  require("./components/protectedarea/birdarea/index.vue")
);
Vue.component(
  "search-landscape",
  require("./components/protectedarea/landscape/index.vue")
);
Vue.component(
  "search-biosphere",
  require("./components/protectedarea/biosphere/index.vue")
);
Vue.component(
  "search-conventions",
  require("./components/efforts/conventions/index.vue")
);
Vue.component(
  "detail-conguoc",
  require("./components/efforts/conventions/detail.vue")
);
Vue.component(
  "search-legislation",
  require("./components/efforts/legislation/index.vue")
);
Vue.component(
  "detail-legislation",
  require("./components/efforts/legislation/detail.vue")
);
Vue.component(
  "search-permit",
  require("./components/efforts/permit/index.vue")
);
Vue.component(
  "detail-permit",
  require("./components/efforts/permit/detail.vue")
);
Vue.component(
  "search-inspect",
  require("./components/efforts/inspect/index.vue")
);
Vue.component("search-alien", require("./components/efforts/alien/index.vue"));
Vue.component(
  "search-cooperation",
  require("./components/efforts/cooperation/index.vue")
);
Vue.component(
  "search-budget",
  require("./components/efforts/budget/index.vue")
);
Vue.component(
  "search-initiative",
  require("./components/efforts/initiative/index.vue")
);
Vue.component(
  "search-pressure",
  require("./components/efforts/pressure/index.vue")
);

Vue.component(
  "chi-thi",
  require("./components/admin/category/chithi/index.vue")
);
Vue.component(
  "thong-so",
  require("./components/admin/category/chithi/thongso.vue")
);
Vue.component(
  "quan-trac",
  require("./components/admin/category/quantrac/index.vue")
);

Vue.component(
  "dieu-uoc",
  require("./components/admin/resources/dieuuoc/index.vue")
);
Vue.component(
  "search-dieuuocquocte",
  require("./components/nolucbaoton/dieuuocquocte/index.vue")
);
Vue.component(
  "detail-dieuuocquocte",
  require("./components/nolucbaoton/dieuuocquocte/detail.vue")
);
Vue.component(
  "vanban-phapluat",
  require("./components/admin/resources/vanbanphapluat/index.vue")
);
Vue.component(
  "giay-phep",
  require("./components/admin/resources/giayphep/index.vue")
);
Vue.component(
  "add-giayphep",
  require("./components/admin/resources/giayphep/add.vue")
);
Vue.component(
  "edit-giayphep",
  require("./components/admin/resources/giayphep/edit.vue")
);
Vue.component(
  "hoptac-quocte",
  require("./components/admin/resources/hoptacquocte/index.vue")
);
Vue.component(
  "them-hoptacquocte",
  require("./components/admin/resources/hoptacquocte/add.vue")
);
Vue.component(
  "capnhat-hoptacquocte",
  require("./components/admin/resources/hoptacquocte/edit.vue")
);
Vue.component(
  "mohinh-sangkien",
  require("./components/admin/resources/mohinhsangkien/index.vue")
);
Vue.component(
  "kiemsoat-svnlxh",
  require("./components/admin/resources/sinhvatngoailai/index.vue")
);
Vue.component(
  "thanhtra-kiemtra",
  require("./components/admin/resources/thanhtrakiemtra/index.vue")
);
Vue.component(
  "kinh-phi",
  require("./components/admin/resources/kinhphi/index.vue")
);
Vue.component(
  "doanthanhtra",
  require("./components/admin/resources/thanhtrakiemtra/doanthanhtra/index.vue")
);
Vue.component(
  "add-ketquathanhtracoso",
  require("./components/admin/resources/thanhtrakiemtra/ketquathanhtracoso/add.vue")
);
Vue.component(
  "edit-ketquathanhtracoso",
  require("./components/admin/resources/thanhtrakiemtra/ketquathanhtracoso/edit.vue")
);
Vue.component(
  "ketquathanhtracoso",
  require("./components/admin/resources/thanhtrakiemtra/ketquathanhtracoso/index.vue")
);

Vue.component(
  "add-ketquathanhtratinh",
  require("./components/admin/resources/thanhtrakiemtra/ketquathanhtratinh/add.vue")
);
Vue.component(
  "edit-ketquathanhtratinh",
  require("./components/admin/resources/thanhtrakiemtra/ketquathanhtratinh/edit.vue")
);
Vue.component(
  "ketquathanhtratinh",
  require("./components/admin/resources/thanhtrakiemtra/ketquathanhtratinh/index.vue")
);
Vue.component(
  "he-sinhthai",
  require("./components/admin/resources/hesinhthai/index.vue")
);
Vue.component(
  "add-hesinhthai",
  require("./components/admin/resources/hesinhthai/add.vue")
);
Vue.component(
  "edit-hesinhthai",
  require("./components/admin/resources/hesinhthai/edit.vue")
);
Vue.component(
  "dichvu-hesinhthai",
  require("./components/admin/resources/dichvuhesinhthai/index.vue")
);
Vue.component(
  "o-tieuchuan",
  require("./components/admin/resources/otieuchuan/index.vue")
);
Vue.component(
  "coso-baoton",
  require("./components/admin/resources/cosobaoton/index.vue")
);
Vue.component(
  "edit-cosobaoton",
  require("./components/admin/resources/cosobaoton/edit.vue")
);
Vue.component(
  "add-cosobaoton",
  require("./components/admin/resources/cosobaoton/add.vue")
);
Vue.component(
  "hanhlang-sinhhoc",
  require("./components/admin/resources/hanhlang/index.vue")
);
Vue.component(
  "add-hanhlang",
  require("./components/admin/resources/hanhlang/add.vue")
);
Vue.component(
  "edit-hanhlang",
  require("./components/admin/resources/hanhlang/edit.vue")
);
Vue.component(
  "edit-dadang-sinhhoccao",
  require("./components/admin/resources/dadangsinhhoccao/edit.vue")
);
Vue.component(
  "add-dadang-sinhhoccao",
  require("./components/admin/resources/dadangsinhhoccao/add.vue")
);
Vue.component(
  "dadang-sinhhoccao",
  require("./components/admin/resources/dadangsinhhoccao/index.vue")
);
Vue.component(
  "dat-ngapnuoc",
  require("./components/admin/resources/datngapnuoc/index.vue")
);
Vue.component(
  "edit-dat-ngapnuoc",
  require("./components/admin/resources/datngapnuoc/edit.vue")
);
Vue.component(
  "add-dat-ngapnuoc",
  require("./components/admin/resources/datngapnuoc/add.vue")
);
Vue.component(
  "vungchim-quantrong",
  require("./components/admin/resources/vungchimquantrong/index.vue")
);
Vue.component(
  "add-vungchim-quantrong",
  require("./components/admin/resources/vungchimquantrong/add.vue")
);
Vue.component(
  "edit-vungchim-quantrong",
  require("./components/admin/resources/vungchimquantrong/edit.vue")
);
Vue.component(
  "dutru-sinhquyen",
  require("./components/admin/resources/dutrusinhquyen/index.vue")
);
Vue.component(
  "add-dutru-sinhquyen",
  require("./components/admin/resources/dutrusinhquyen/add.vue")
);
Vue.component(
  "edit-dutru-sinhquyen",
  require("./components/admin/resources/dutrusinhquyen/edit.vue")
);
Vue.component(
  "canhquan-sinhthai",
  require("./components/admin/resources/canhquansinhthai/index.vue")
);
Vue.component(
  "add-canhquan-sinhthai",
  require("./components/admin/resources/canhquansinhthai/add.vue")
);
Vue.component(
  "edit-canhquan-sinhthai",
  require("./components/admin/resources/canhquansinhthai/edit.vue")
);
Vue.component(
  "sinhvat-ngoailai",
  require("./components/admin/category/sinhvatngoailai/index.vue")
);
Vue.component(
  "add-sinhvat-ngoailai",
  require("./components/admin/category/sinhvatngoailai/add.vue")
);
Vue.component(
  "edit-sinhvat-ngoailai",
  require("./components/admin/category/sinhvatngoailai/edit.vue")
);
Vue.component(
  "bangtin-tonghop",
  require("./components/admin/category/bangtintonghop/index.vue")
);

Vue.component(
  "nguon-gen",
  require("./components/admin/category/genetic/index.vue")
);
Vue.component(
  "add-nguongen",
  require("./components/admin/category/genetic/addnguongen.vue")
);
Vue.component(
  "edit-nguongen",
  require("./components/admin/category/genetic/editnguongen.vue")
);
Vue.component(
  "trithuc-truyenthong",
  require("./components/admin/category/trithuctruyenthong/index.vue")
);

Vue.component(
  "loai-taxon",
  require("./components/admin/category/taxon/index.vue")
);
Vue.component(
  "search-apluc",
  require("./components/conservation-efforts/apluc/index.vue")
);
Vue.component(
  "detail-apluc",
  require("./components/conservation-efforts/apluc/detail.vue")
);
Vue.component(
  "bieudo-loai",
  require("./components/bieudoloai/index.vue")
);
Vue.component(
  "detail-loai-taxon",
  require("./components/admin/category/taxon/detail.vue")
);
Vue.component(
  "thongke-apluc",
  require("./components/admin/resources/baocaoapluc/index.vue")
);
Vue.filter("rankName", (value) => {
  if (
    [
      "kingdom",
      "phylum",
      "class",
      "order",
      "family",
      "genus",
      "species",
    ].includes(value)
  ) {
    return i18n.t(`nbds_lang.taxon_${value}`);
  }
  return "Error";
});
Vue.filter("datasetTypeName", (value) => {
  if (value == "Occurrence") return i18n.t(`nbds_lang.occurrences`);
  if (value == "Taxon") return i18n.t(`nbds_lang.table_darwin_core_taxons`);
  return value;
});
const app = new Vue({
  el: "#app",
  i18n,
  store,
});
