<template>
  <div class="white-box p-0" style="min-height: calc(150vh - 220px)">
    <!-- .left-right-aside-column-->
    <div class="page-aside">
      <components-notifications
        :typeInput="typeNotification"
        :textInput="textNotification"
      >
      </components-notifications>
      <components-confirm
        :show_pop_up="confirmPopUp"
        @showPopUp="confirmPopUp = $event"
        @resetData="resetData = $event"
        title="Khu bảo tồn"
        :route_link="delprotectedarea"
        type_pop_up="Khu bảo tồn"
        :data="idDeleteProtectedArea"
      ></components-confirm>
      <!-- .left-aside-column-->
      <div class="left-aside">
        <div class="scrollable">
          <ul class="list-style-none">
            <li class="box-label">{{ $t("admin.label.filter") }}</li>
            <li class="divider"></li>
            <li v-if="!status">
              <components-loading></components-loading>
            </li>
            <div
              @click="isStatus = -isStatus"
              data-toggle="collapse"
              data-target=".filter1"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchStatus.length != 0
                  ? 'color: #000000; font-weight: bold'
                  : ''
              "
            >
              {{ $t("nbds_lang.protected_areas.status")
              }}<span
                :class="
                  isStatus == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <br />
            <li
              v-for="item in status"
              v-if="status"
              class="collapse filter1"
              style="margin-left: 15px"
            >
              <a
                @click="searchWithStatus(item.status)"
                :title="item.status"
                style="cursor: pointer"
                :style="
                  searchStatus == item.status
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
                {{ $t("nbds_lang.protected_areas." + item.status) }}
                <span>{{ item.count }}</span>
              </a>
            </li>
            <div
              @click="isGovType = -isGovType"
              data-toggle="collapse"
              data-target=".filter2"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchGovType.length != 0
                  ? 'color: #000000; font-weight: bold'
                  : ''
              "
            >
              {{ $t("nbds_lang.protected_areas.gov_type")
              }}<span
                :class="
                  isGovType == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <br />
            <li
              v-for="item in gov_type"
              v-if="gov_type"
              class="collapse filter2"
              style="margin-left: 15px"
            >
              <a
                @click="searchWithGovType(item.gov_type)"
                :title="item.gov_type"
                style="cursor: pointer"
                :style="
                  searchGovType == item.gov_type
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
                {{
                  item.gov_type.length >= 20
                    ? item.gov_type.substring(0, 20) + "..."
                    : item.gov_type
                }}
                <span>{{ item.count }}</span>
              </a>
            </li>
            <div
              @click="isDesigType = -isDesigType"
              data-toggle="collapse"
              data-target=".filter3"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchDesigType ? 'color: #000000; font-weight: bold' : ''
              "
            >
              {{ $t("nbds_lang.protected_areas.desig_type")
              }}<span
                :class="
                  isDesigType == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <li
              v-for="item in desig_type"
              v-if="desig_type"
              class="collapse filter3"
              style="margin-left: 15px"
            >
              <a
                @click="searchWithDesigType(item.desig_type)"
                :title="item.desig_type"
                style="cursor: pointer"
                :style="
                  searchDesigType == item.desig_type
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
                {{
                  item.desig_type.length >= 20
                    ? item.desig_type.substring(0, 20) + "..."
                    : item.desig_type
                }}
                <span>{{ item.count }}</span>
              </a>
            </li>

            <li
              style="text-align: center; margin-top: 12px"
              v-if="
                searchStatus.length != 0 ||
                searchGovType.length != 0 ||
                searchDesigType
              "
            >
              <button
                type="button"
                class="btn btn-info btn-rounded"
                @click="deleteSearchFilter()"
              >
                <span>Xóa bộ lọc</span>
              </button>
            </li>
          </ul>
        </div>
      </div>
      <!-- /.left-aside-column-->
      <div class="right-aside">
        <div class="right-page-header">
          <div class="pull-right">
            <input
              type="text"
              :placeholder="'Tìm kiếm khu bảo tồn'"
              class="form-control"
              v-model="search"
              v-on:keyup.enter="
                (searchGovType = []),
                  (searchStatus = []),
                  (searchDesigType = null),
                  getProtectedArea()
              "
            />
          </div>
          <h3 class="uppercase" style="font-weight: normal">
            Danh sách khu bảo tồn
          </h3>
        </div>
        <button
          type="button"
          class="btn btn-info btn-rounded"
          @click="goAddProtectedArea()"
        >
          Thêm mới
        </button>
        <div class="clearfix"></div>
        <div class="scrollable">
          <div class="table-responsive" style="overflow-x: auto">
            <table
              class="table m-t-30 table-hover contact-list footable footable-1 footable-paging footable-paging-center breakpoint-lg"
              style="min-width: 800px"
            >
              <thead>
                <tr class="footable-header">
                  <th class="text-center">{{ $t("admin.label.no") }}</th>
                  <th style="display: table-cell; min-width: 190px">
                    {{ $t("nbds_lang.protected_areas.name") }}
                  </th>
                  <th style="display: table-cell; min-width: 170px">
                   Diện tích
                  </th>
                  <th style="display: table-cell; min-width: 170px">
                    {{ $t("nbds_lang.protected_areas.gov_type") }}
                  </th>
                  <th style="display: table-cell; min-width: 180px">
                   Địa chỉ
                  </th>
                  <th width="150" style="text-align: center">
                    {{ $t("admin.label.manage") }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="loading">
                  <td colspan="5">
                    <components-loading></components-loading>
                  </td>
                </tr>
                <tr v-if="protected_area && protected_area.length == 0">
                  <td colspan="6" class="emptyInfomation">
                    <h5>{{ $t("admin.error.no_data") }}</h5>
                  </td>
                </tr>
                <tr
                  v-for="(item, index) in protected_area"
                  v-if="protected_area"
                  style="cursor: context-menu"
                  v-show="!loading"
                >
                  <td class="position-relative">
                    <span class="position-absolute text-center-td">
                      {{ (current_page - 1) * per_page + index + 1 }}
                    </span>
                  </td>
                  <td class="position-relative">
                    <div class="row position-absolute center-vertical">
                      <div class="col-md-9" :title="item.name">
                        {{
                          item.name.length >= 20
                            ? item.name.substring(0, 20) + "..."
                            : item.name
                        }}
                        <br />
                        <span class="text-muted" :title="item.orig_name" v-if="item.orig_name">{{
                          item.orig_name.length >= 20
                            ? item.orig_name.substring(0, 20) + "..."
                            : item.orig_name
                        }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="position-relative">
                    <div class="row position-absolute center-vertical">
                        {{item.rep_area}} (ha)
                    </div>
                  </td>
                  <td class="position-relative">
                    <div class="row position-absolute center-vertical">
                      <div
                        class="col-md-9"
                        :title="item.gov_type"
                        v-if="item.gov_type"
                      >
                        <p>
                          {{
                            item.gov_type.length >= 20
                              ? item.gov_type.substring(0, 20) + "..."
                              : item.gov_type
                          }}
                        </p>
                      </div>
                    </div>
                  </td>
                  <td class="position-relative">
                    <div class="row position-absolute center-vertical">
                      <div
                        class="col-md-9"
                        :title="item.dia_chi"
                        v-if="item.dia_chi"
                      >
                        <p>
                          {{
                            item.dia_chi.length >= 20
                              ? item.dia_chi.substring(0, 20) + "..."
                              : item.dia_chi
                          }}
                        </p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <button
                      type="button"
                      style="display: none"
                      class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
                    >
                      <i class="ti-key"></i>
                    </button>
                    <button
                      type="button"
                      @click="editProtectedArea(item.id)"
                      class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
                    >
                      <i class="ti-pencil-alt"></i>
                    </button>
                    <button
                      type="button"
                      class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
                      @click="deleteProtectedArea(item.id)"
                    >
                      <i class="ti-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <footer v-if="count > 1" v-show="!loading">
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
          </footer>
        </div>
      </div>
      <!-- .left-aside-column-->
    </div>
    <!-- /.left-right-aside-column-->
  </div>
</template>

<script>
import * as env from "../../../../env.js";
import { endpoint, endpointhttp } from "../../../../env.js";
import * as routes from "../../../../routes.js";

export default {
  data: function () {
    return {
      loading: false,
      typeNotification: null,
      textNotification: null,
      status: null,
      protected_area: null,
      page: 1,
      count: 0,
      per_page: 0,
      current_page: 0,
      searchStatus: [],
      search: null,
      getId: null,
      gov_type: null,
      searchGovType: [],
      open: false,
      confirmPopUp: false,
      idDeleteProtectedArea: null,
      desig_type: null,
      searchDesigType: null,
      isStatus: 1,
      isGovType: 1,
      isDesigType: 1,
      resetData: false,
    };
  },
  mounted() {
    this.getProtectedArea();
    this.getStatus();
    this.getGovType();
    this.getDesigType();
  },

  computed: {
    delprotectedarea: function () {
      return env.endpointhttp + routes.deleteprotectedarea;
    },
  },

  watch: {
    resetData: function (value) {
      if (value) {
        this.page = 1;
        this.typeNotification = 2;
        this.textNotification = "Xóa thành công.";
        this.getProtectedArea();
      }
    },
  },

  methods: {
    editProtectedArea(value) {
      window.location.href = "/admin/protectedareas/update/" + value;
    },
    searchWithStatus(value) {
      this.searchStatus[0] = value;
      this.getProtectedArea();
    },
    searchWithGovType(value) {
      this.searchGovType[0] = value;
      this.getProtectedArea();
    },
    searchWithDesigType(value) {
      this.searchDesigType = value;
      this.getProtectedArea();
    },
    getProtectedArea() {
      this.loading = true;
      this.page = 1;
      axios
        .get(env.endpointhttp + "admin/protectedareas/info", {
          params: {
            search: this.search,
            status: this.searchStatus,
            gov_type: this.searchGovType,
            desig_type: this.searchDesigType,
          },
        })
        .then((response) => {
          this.protected_area = response.data.result.data;
          this.count = response.data.result.last_page;
          this.per_page = response.data.result.per_page;
          this.current_page = response.data.result.current_page;
          this.loading = false;
        });
    },
    getStatus() {
      this.loading = true;
      axios
        .get(env.endpointhttp + "protectedareas/topstatuses")
        .then((response) => {
          this.status = response.data.result;
          this.loading = false;
          console.log(this.status);
        });
    },
    getGovType() {
      this.loading = true;
      axios
        .get(env.endpointhttp + "protectedareas/topgovtypes")
        .then((response) => {
          this.gov_type = response.data.result;
          this.loading = false;
        });
    },
    getDesigType() {
      this.loading = true;
      axios.get(env.endpointhttp + "desigtypes").then((response) => {
        this.desig_type = response.data.result;
        this.loading = false;
      });
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.loading = false;
      axios
        .get(env.endpointhttp + "admin/protectedareas/info", {
          params: {
            search: this.search,
            status: this.searchStatus,
            page: this.page,
            gov_type: this.searchGovType,
            desig_type: this.searchDesigType,
          },
        })
        .then((response) => {
          this.protected_area = response.data.result.data;
          this.count = response.data.result.last_page;
          this.per_page = response.data.result.per_page;
          this.current_page = response.data.result.current_page;
          this.loading = false;
        })
        .catch((error) => {
          console.log(error);
          this.loading = false;
        })
        .finally();
    },
    goAddProtectedArea() {
      window.location.href = "/admin/protectedareas/add";
    },
    deleteSearchFilter() {
      this.searchStatus = [];
      this.searchGovType = [];
      this.searchDesigType = null;
      this.getProtectedArea();
    },
    deleteProtectedArea(value) {
      this.idDeleteProtectedArea = value;
      this.confirmPopUp = true;
    },
  },
};
</script>
