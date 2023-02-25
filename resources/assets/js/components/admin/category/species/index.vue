<template>
  <div class="white-box p-0" style="min-height: calc(100vh)">
    <!-- .left-right-aside-column-->
    <div class="page-aside">
      <components-notifications :typeInput="typeNotification" :textInput="textNotification"></components-notifications>
      <components-confirm :show_pop_up="confirmPopUp" @showPopUp="confirmPopUp = $event" @resetData="resetData = $event"
        title="Thông tin loài" :route_link="deleteSpeciesRoute" type_pop_up="Thông tin loài"
        :data="idDeleteSpecies"></components-confirm>
      <!-- .left-aside-column-->
      <div class="left-aside">
        <div class="scrollable">
          <ul class="list-style-none">
            <li class="box-label">{{ $t("admin.label.filter") }}</li>
            <li class="divider"></li>
            <li v-if="!rank">
              <components-loading></components-loading>
            </li>
            <li v-for="item in rank" v-if="rank">
              <a @click="searchWithRank(item.rank)" style="cursor: context-menu"
                :style="searchRank == item.rank ? 'color: #408c5b' : ''">
                {{ item.rank_vi ? item.rank_vi : item.rank }}
                <span>{{ item.total }}</span>
              </a>
            </li>
            <li v-if="searchRank" style="text-align: center">
              <button type="button" class="btn btn-info btn-rounded" @click="searchWithRank()">
                <span>Xóa lọc theo rank</span>
              </button>
            </li>
          </ul>
        </div>
      </div>
      <!-- /.left-aside-column-->
      <div class="right-aside">
        <div class="right-page-header">
          <div class="pull-right">
            <input type="text" :placeholder="'Tìm kiếm loài'" class="form-control" v-model="search"
              v-on:keyup.enter="(searchRank = null), getSpecies()" />
          </div>
          <h3 class="uppercase" style="font-weight: normal">Danh mục loài</h3>
        </div>
        <a class="btn btn-info btn-rounded" href="/admin/species/add">Thêm mới</a>
        <!-- <div class="m-t-10" style="font-size: 16px;">Nhập từ Excel</div>
        <div >
          <button type="button" class="btn btn-default float-right" @click="downloadTemplate()">
            Tải file mẫu
          </button>
          <button class="btn btn-info btn-file">
            <i class="fa fa-paperclip"></i> Chọn file excel
            <input type="file" id="file" ref="file" v-on:change="uploadFile()"
              accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
          </button>
        </div> -->

        <div class="clearfix"></div>
        <div class="scrollable">
          <div class="table-responsive" style="overflow-x: auto">
            <table
              class="table m-t-30 table-hover contact-list footable footable-1 footable-paging footable-paging-center breakpoint-lg"
              style="min-width: 800px">
              <thead>
                <tr class="footable-header">
                  <th class="text-center">{{ $t("admin.label.no") }}</th>
                  <th style="display: table-cell">Rank</th>
                  <th style="display: table-cell; min-width: 120px">
                    {{ $t("admin.label.name") }}
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
                <tr v-if="species && species.length == 0">
                  <td colspan="6" class="emptyInfomation">
                    <h5>{{ $t("admin.error.no_data") }}</h5>
                  </td>
                </tr>
                <tr v-for="(item, index) in species" v-if="species" style="cursor: context-menu" v-show="!loading">
                  <td class="position-relative">
                    <span class="position-absolute text-center-td">{{
                        (current_page - 1) * per_page + index + 1
                    }}</span>
                  </td>
                  <td class="position-relative">
                    <div class="row position-absolute center-vertical">
                      <div class="col-md-9">{{ item.rank }}</div>
                    </div>
                  </td>
                  <td class="position-relative">
                    <div class="row center-vertical">
                      <div class="col-md-9" style="max-width: 400px; overflow:clip">
                        {{ item.name }}
                        <br />
                        <div class="text-muted" style="max-width: 400px; overflow:clip">{{
                            item.name_vietnamese
                        }}</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <button type="button" @click="editSpecies(item.rank + '/' + item.old_id)"
                      class="btn btn-info btn-outline btn-circle btn-lg m-r-5">
                      <i class="ti-pencil-alt"></i>
                    </button>
                    <button type="button" class="btn btn-info btn-outline btn-circle btn-lg m-r-5"
                      @click="deleteSpecies(item.rank + '/' + item.old_id)">
                      <i class="ti-trash"></i>
                    </button>
                  </td>
                  <div class="modal" id="myModal">
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content">
                        <div class="modal-header">
                          <div class="col-md-10">
                            <label class="modal-title">Xác nhận xóa loài</label>
                          </div>
                          <div class="col-md-2">
                            <button type="button" class="close" data-dismiss="modal">
                              &times;
                            </button>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <div class="col-md-6" style="text-align: left">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                              Hủy
                            </button>
                          </div>
                          <div class="col-md-6">
                            <button type="button" class="btn btn-danger" @click="deleteSpecies(getId)"
                              data-dismiss="modal">
                              Xóa
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </tr>
              </tbody>
            </table>
          </div>
          <footer v-show="!loading">
            <paginate v-if="count > 1" v-model="page" :page-count="this.count" :page-range="3" :margin-pages="2"
              :click-handler="clickCallback" :prev-text="'‹‹'" :next-text="'››'" :container-class="'pagination'"
              :page-class="'page-item'"></paginate>
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
import * as routes from "../../../../routes.js";
import { endpoint, endpointhttp } from "../../../../env.js";

export default {
  data: function () {
    return {
      confirmPopUp: false,
      loading: false,
      typeNotification: null,
      textNotification: null,
      rank: null,
      species: null,
      page: 1,
      count: 0,
      per_page: 0,
      current_page: 0,
      searchRank: null,
      search: null,
      getId: null,
      idDeleteSpecies: null,
      resetData: false,
      file: null
    };
  },
  mounted() {
    this.getSpecies();
  },
  methods: {
    deleteSpecies(value) {
      this.idDeleteSpecies = value;
      this.confirmPopUp = true;
    },
    editSpecies(value) {
      window.location.href = "/admin/species/update/" + value;
    },
    deleteUser(value) { },
    searchWithRank(value) {
      this.searchRank = value;
      this.getSpecies();
    },
    downloadTemplate() {
      axios
        .get(env.endpointhttp + "admin/species/dowload-template-rank", {
          responseType: "blob",
        })
        .then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "file_mau_rank.xlsx");
          document.body.appendChild(link);
          link.click();
        });
    },
    uploadFile() {
      this.file = this.$refs.file.files[0];
      let formData = new FormData();
      formData.append("file", this.file);
      axios
        .post(
          env.endpointhttp + "admin/species/upload",
          formData
        )
        .then((response) => {
          this.typeNotification = 2;
          this.textNotification = "Cập nhật thành công.";
          this.getSpecies();
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
    getRoles() { },
    getSpecies() {
      this.resetData = false;
      this.loading = true;
      this.page = 1;
      axios
        .get(env.endpointhttp + "admin/species", {
          params: {
            search: this.search,
            search_rank: this.searchRank,
            page: this.page,
          },
        })
        .then((response) => {
          this.rank = response.data.rank;
          this.species = response.data.result.data;
          this.count = response.data.result.last_page;
          this.per_page = response.data.result.per_page;
          this.current_page = response.data.result.current_page;
          this.loading = false;
        });
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.loading = false;
      axios
        .get(env.endpointhttp + "admin/species", {
          params: {
            search: this.search,
            search_rank: this.searchRank,
            page: this.page,
          },
        })
        .then((response) => {
          this.rank = response.data.rank;
          this.species = response.data.result.data;
          this.count = response.data.result.last_page;
          this.per_page = response.data.result.per_page;
          this.current_page = response.data.result.current_page;
          this.loading = false;
        })
        .catch((error) => {
          console.log(error);
        })
        .finally();
    },
    goAddUser() { },
  },
  computed: {
    deleteSpeciesRoute: function () {
      return env.endpointhttp + routes.deletespecies;
    },
  },
  watch: {
    resetData: function (value) {
      if (value) {
        this.typeNotification = 2;
        this.textNotification = "Xóa thành công.";
        this.getSpecies();
      }
    },
  },
};
</script>
