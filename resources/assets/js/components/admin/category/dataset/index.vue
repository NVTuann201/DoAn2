<template>
  <div class="white-box p-0">
    <!-- .left-right-aside-column-->
    <div class="page-aside">
      <components-loading-page v-if="loading"></components-loading-page>
      <components-notifications
        :typeInput="typeNotification"
        :textInput="textNotification"
      ></components-notifications>
      <components-confirm
        :show_pop_up="confirmPopUp"
        @showPopUp="confirmPopUp = $event"
        @resetData="resetData = $event"
        :title="$t('nbds_lang.submenu_dataset')"
        :route_link="delDataset"
        :type_pop_up="$t('nbds_lang.submenu_dataset')"
        :data="idDeleteDataset"
      ></components-confirm>
      <!-- /.left-aside-column-->
      <div class="left-aside">
        <div class="scrollable">
          <ul class="list-style-none">
            <li class="box-label">{{ $t("admin.label.filter") }}</li>
            <li class="divider"></li>
            <li v-if="!dataset">
              <components-loading></components-loading>
            </li>
            <div
              @click="isOrganization = -isOrganization"
              data-toggle="collapse"
              data-target=".filter1"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchOrganization ? 'color: #000000; font-weight: bold' : ''
              "
            >
              {{ $t("nbds_lang.dataset_resources.organization") }}
              <span
                :class="
                  isOrganization == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <br />
            <li
            v-if="organization"
              v-for="item in organization"
              class="collapse filter1"
              style="margin-left: 15px"
              :key="item.id"
            >
              <a
               v-if="item.organization && item.organization.name_vietnamese"
                @click="searchWithOrganization(item.organization_id)"
                style="cursor: pointer"
                :style="
                  searchOrganization == item.organization_id
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
                {{
                  item.organization.name_vietnamese.length >= 20
                    ? item.organization.name_vietnamese.substring(0, 20) + "..."
                    : item.organization.name_vietnamese 
                }}
                <span>{{ item.count }}</span>
              </a>
            </li>

            <div
              @click="isDatasetType = -isDatasetType"
              data-toggle="collapse"
              data-target=".filter2"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              "
            >
              {{ $t("nbds_lang.dataset_resources.dataset_type") }}
              <span
                :class="
                  isDatasetType == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <br />
            <li
              v-for="item in dataset_type"
              v-if="dataset_type"
              class="collapse filter2"
              style="margin-left: 15px"
            >
              <a
                @click="searchWithDatasetType(item.dataset_type)"
                style="cursor: pointer"
                :style="
                  searchDatasetType == item.dataset_type
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
                {{
                  item.dataset_type.length >= 20
                    ? item.dataset_type.substring(0, 20) + "..."
                    : item.dataset_type
                }}
                <span>{{ item.count }}</span>
              </a>
            </li>
            <div
              @click="isStatus = -isStatus"
              data-toggle="collapse"
              data-target=".filter3"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              "
            >
              {{ $t("nbds_lang.dataset_resources.status") }}
              <span
                :class="
                  isStatus == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <br />
            <li
              v-for="item in Object.keys(status)"
              v-if="dataset_type"
              class="collapse filter3"
              style="margin-left: 15px"
            >
              <a
                @click="goSearchStatus(item)"
                style="cursor: pointer"
                :style="
                  searchStatus == item
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
                {{ item }}
                <span>{{ status[item] }}</span>
              </a>
            </li>
            <li
              style="text-align: center; margin-top: 12px"
              v-if="searchOrganization || searchDatasetType || searchStatus"
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
      <div class="right-aside">
        <div class="right-page-header">
          <button
            type="button"
            class="btn btn-info btn-rounded"
            @click="addDataset()"
          >
            Thêm mới bộ dữ liệu
          </button>
          <div class="pull-right">
            <input
              type="text"
              :placeholder="$t('admin.placeholder.search_dataset')"
              class="form-control"
              v-model="search"
              v-on:keyup.enter="searchDataset()"
            />
          </div>
          <div class="clearfix"></div>
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
                      {{ $t("nbds_lang.dataset_resources.title") }}
                    </th>
                    <th style="display: table-cell">
                      {{ $t("nbds_lang.dataset_resources.publication_date") }}
                    </th>
                    <th style="display: table-cell; min-width: 70px">
                      {{ $t("nbds_lang.dataset_resources.organization") }}
                    </th>
                    <th style="display: table-cell">
                      {{ $t("nbds_lang.dataset_resources.dataset_type") }}
                    </th>
                    <th width="150" style="text-align: center">
                      {{ $t("admin.label.manage") }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="!dataset">
                    <td colspan="8">
                      <components-loading></components-loading>
                    </td>
                  </tr>
                  <tr v-if="dataset && dataset.length == 0">
                    <td colspan="6" class="emptyInfomation">
                      <h5>{{ $t("admin.error.no_data") }}</h5>
                    </td>
                  </tr>
                  <tr
                    v-for="(item, index) in dataset"
                    v-if="dataset"
                    style="cursor: context-menu"
                  >
                    <td>
                      <span class="text-center-td">{{
                        (page - 1) * 10 + index + 1
                      }}</span>
                    </td>
                    <td>
                      <div style="white-space: initial">{{ item.title }}</div>
                    </td>
                    <td>{{ item.publication_date }}</td>
                    <td>
                      <div style="white-space: initial">
                        {{ item.organization.name }}
                      </div>
                    </td>
                    <td>{{ item.dataset_type }}</td>
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
                        title="Chỉnh sửa"
                        @click="editDataset(item.id)"
                        class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
                      >
                        <i class="ti-pencil-alt"></i>
                      </button>
                      <button
                        type="button"
                        title="Chi tiết Bộ dữ liệu"
                        @click="detailDataset(item)"
                        class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
                      >
                        <i class="fas fa-list-ul"></i>
                      </button>
                      <button
                        type="button"
                        title="Xóa"
                        class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
                        @click="deleteDataset(item.id)"
                      >
                        <i class="ti-trash"></i>
                      </button>
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
      </div>
      <!-- /.left-right-aside-column-->
    </div>
  </div>
</template>

<script>
import * as env from "../../../../env.js";
import * as routes from "../../../../routes.js";
import { endpoint, endpointhttp } from "../../../../env.js";

export default {
  data: function () {
    return {
      confirmPopUp: false,
      search: null,
      typeNotification: null,
      textNotification: null,
      resetData: false,
      dataset: null,
      page: 1,
      count: 0,
      per_page: 0,
      current_page: 0,
      search_role: null,
      loading: false,
      idDeleteDataset: null,
      organization: [],
      dataset_type: [],
      status: {},
      searchOrganization: null,
      searchDatasetType: null,
      searchStatus: null,
      isOrganization: 1,
      isDatasetType: 1,
      isStatus: 1,
    };
  },
  mounted() {
    this.getDataset();
    this.getTopOrg();
    this.getDatasetType();
    this.getStatus();
  },
  methods: {
    editDataset(value) {
      window.location.href = "/admin/dataset/update/" + value;
    },
    deleteDataset(value) {
      this.idDeleteDataset = value;
      this.confirmPopUp = true;
    },
    addDataset() {
      window.location.href = "/admin/dataset/add";
    },
    detailDataset(item) {
      if (item.dataset_type == "Taxon") {
        window.location.href = "/admin/dataset/detail/taxon/" + item.id;
      }
      if (item.dataset_type == "Occurrence") {
        window.location.href = "/admin/dataset/detail/occurrences/" + item.id;
      }
    },
    getDataset() {
      this.loading = true;
      this.page = 1;
      this.resetData = false;
      axios
        .get(env.endpointhttp + "admin/dataset/info", {
          params: {
            search: this.search,
            search_organization: this.searchOrganization,
            search_dataset_type: this.searchDatasetType,
            search_status: this.searchStatus,
            page: this.page,
          },
        })
        .then((response) => {
          this.dataset = response.data.result.data;
          this.count = response.data.result.last_page;
          this.per_page = response.data.result.per_page;
          this.current_page = response.data.result.current_page;
          this.loading = false;
        });
    },
    getTopOrg() {
      this.loading = true;
      this.organization = [];
      axios
        .get(env.endpointhttp + "admin/dataset/toporganization", {
          params: {
            search: this.search,
          },
        })
        .then((response) => {
          this.organization = response.data.result;
          this.loading = false;
        });
    },
    getStatus() {
      this.loading = true;
      this.status = {};
      axios
        .get(env.endpointhttp + "admin/dataset/status", {
          params: {
            search: this.search,
          },
        })
        .then((response) => {
          this.status = response.data.result;
          this.loading = false;
        });
    },
    getDatasetType() {
      this.dataset_type = [];
      this.loading = true;
      axios
        .get(env.endpointhttp + "admin/dataset/datasettype", {
          params: {
            search: this.search,
          },
        })
        .then((response) => {
          this.dataset_type = response.data.result;
          this.loading = false;
        });
    },
    searchDataset() {
      this.loading = true;
      (this.isOrganization = false),
        (this.isDatasetType = false),
        (this.searchOrganization = null);
      this.searchDatasetType = null;
      this.getDataset();
      this.getTopOrg();
      this.getDatasetType();
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      axios
        .get(env.endpointhttp + "admin/dataset/info", {
          params: {
            search: this.search,
            search_organization: this.searchOrganization,
            search_dataset_type: this.searchDatasetType,
            search_status: this.searchStatus,
            page: this.page,
          },
        })
        .then((response) => {
          this.dataset = response.data.result.data;
          this.count = response.data.result.last_page;
          this.per_page = response.data.result.per_page;
          this.current_page = response.data.result.current_page;
        });
    },
    searchWithOrganization(value) {
      this.searchOrganization = value;
      this.getDataset();
    },
    searchWithDatasetType(value) {
      this.searchDatasetType = value;
      this.getDataset();
    },
    goSearchStatus(item) {
      this.searchStatus = item;
      this.getDataset();
    },
    deleteSearchFilter() {
      this.searchOrganization = null;
      this.searchDatasetType = null;
      this.searchStatus = null;
      this.getDataset();
    },
  },
  computed: {
    delDataset: function () {
      return env.endpointhttp + routes.deletedataset;
    },
  },
  watch: {
    resetData: function (value) {
      if (value) {
        this.typeNotification = 2;
        this.textNotification = "Xóa thành công.";
        this.getDataset();
        this.getTopOrg();
        this.getDatasetType();
      }
    },
  },
};
</script>
