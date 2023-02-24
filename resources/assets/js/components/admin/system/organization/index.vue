<template>
  <div class="white-box p-0">
    <!-- .left-right-aside-column-->
    <div class="page-aside">
      <components-loading-page v-if="loading"></components-loading-page>
      <components-notifications :typeInput="typeNotification" :textInput="textNotification"></components-notifications>
      <components-confirm
        :show_pop_up="confirmPopUp"
        @showPopUp="confirmPopUp = $event"
        @resetData="resetData = $event"
        :title="$t('nbds_lang.publisher')"
        :route_link="deleteorganizaition"
        :type_pop_up="$t('nbds_lang.publisher')"
        :data="idDeleteOrganizaition"
      ></components-confirm>
      <!-- /.left-aside-column-->
      <div class="right-aside" style="margin-left: 0px">
        <div class="right-page-header">
          <button
            type="button"
            class="btn btn-info btn-rounded"
            @click="goAddOrganization()"
          >Khai báo thông tin tổ chức mới</button>
          <div class="pull-right">
            <input
              type="text"
              :placeholder="$t('admin.placeholder.search_organization')"
              class="form-control"
              v-model="search"
              v-on:keyup.enter="getOrganization()"
            />
          </div>
          <div class="clearfix"></div>
          <div class="scrollable">
            <div class="table-responsive">
              <table
                class="table m-t-30 table-hover contact-list footable footable-1 footable-paging footable-paging-center breakpoint-lg"
              >
                <thead>
                  <tr class="footable-header">
                    <th class="text-center">{{ $t("admin.label.no") }}</th>
                    <th style="display: table-cell;min-width: 300px;">{{ $t("admin.label.name")}}</th>
                    <th style="display: table-cell;">{{ $t("admin.label.organization_type") }}</th>
                    <th style="display: table-cell; width: 150px">{{ $t("admin.label.acronym") }}</th>
                    <th style="display: table-cell;width: 150px">{{ $t("admin.label.email") }}</th>
                    <th width="150" style="text-align: center">{{ $t("admin.label.manage") }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="!organization">
                    <td colspan="8">
                      <components-loading></components-loading>
                    </td>
                  </tr>
                  <tr v-if="organization && organization.length == 0">
                      <td colspan="6" class="emptyInfomation">
                          <h5>{{ $t("admin.error.no_data") }}</h5>
                      </td>
                  </tr>
                  <tr
                    v-for="(item, index) in organization"
                    v-if="organization"
                    style="cursor: context-menu;"
                  >
                    <td class="position-relative">
                      <span class="position-absolute text-center-td">{{(page - 1)*10 + index + 1}}</span>
                    </td>
                    <td class="position-relative">
                      <div class="row center-vertical" style="transform: translateY(0%)">
                        <div style="white-space: initial">
                          <div class="col-md-2">
                              <img v-if="item.mediable.media_id" :src="'/storage/'+item.mediable.media_id+'/'+item.mediable.media.file_name" alt="user" class="img-circle">
                          </div>
                          <div class="col-md-10">
                            {{item.name_vietnamese}}
                            <br />
                            <span class="text-muted">{{item.name}}</span>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="position-relative">
                      <span class="position-absolute center-vertical">{{item.organization_type}}</span>
                    </td>
                    <td class="position-relative">
                      <span>{{item.acronym}}</span>
                    </td>
                    <td class="position-relative">
                      <span class="text-muted">{{item.email}}</span>
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
                        @click="editOrganization(item.id)"
                        class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
                      >
                        <i class="ti-pencil-alt"></i>
                      </button>
                      <button
                        type="button"
                        class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
                        @click="deleteOrganization(item.id)"
                      >
                        <i class="ti-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
                <tfoot v-if="count>1">
                  <tr class="footable-paging">
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
                </tfoot>
              </table>
            </div>
          </div>
        </div>
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
  data: function() {
    return {
      confirmPopUp: false,
      search: null,
      typeNotification: null,
      textNotification: null,
      resetData: false,
      roles: null,
      organization: null,
      page: 1,
      count: 0,
      search_role: null,
      loading: false,
      idDeleteOrganizaition: null
    };
  },
  mounted() {
    this.getOrganization();
  },
  methods: {
    editOrganization(value) {
      window.location.href = "/admin/organizations/update/" + value;
    },
    deleteOrganization(value) {
      this.idDeleteOrganizaition = value;
      this.confirmPopUp = true;
    },

    getOrganization() {
      this.resetData = false;
      axios
        .get(env.endpointhttp + "admin/organizations/info", {
          params: {
            search: this.search
          }
        })
        .then(response => {
          this.organization = response.data.result.data;
          this.count = response.data.result.last_page;
        });
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      axios
        .get(env.endpointhttp + "admin/organizations/info", {
          params: {
            search: this.search,
            page: this.page
          }
        })
        .then(response => {
          this.organization = response.data.result.data;
          this.count = response.data.result.last_page;
        })
        .catch(error => {
          console.log(error);
        })
        .finally();
    },
    goAddOrganization() {
      window.location.href = "/admin/organizations/add";
    }
  },
  computed: {
    deleteorganizaition: function() {
      return env.endpointhttp + routes.deleteorganizaition;
    }
  },
  watch: {
    resetData: function(value) {
      if (value) {
        this.page = 1;
        this.typeNotification = 2;
        this.textNotification = "Xóa thành công.";
        this.getOrganization();
      }
    }
  }
};
</script>