<template>
  <div class="white-box p-0">
    <div class="page-aside">
      <components-notifications :typeInput="typeNotification" :textInput="textNotification"></components-notifications>
      <div class="loading-roles" v-if="loading">
        <components-loading-page></components-loading-page>
      </div>
      <!-- <div class="left-aside">
        <div class="scrollable">
          <div class="d-flex">
            <div style="fonts-size: 16px; font-weight: bold; flex: 1">
              Bộ lọc
            </div>
            <button
              type="button"
              class="btn-sm btn-info btn-rounded"
              v-if="showXoaBoLoc()"
              @click="deleteSearchFilter()"
            >
              <span>Xóa bộ lọc</span>
            </button>
          </div>
          <hr />
          <ul>
            <div
              @click="isStatus = -isStatus"
              data-toggle="collapse"
              data-target=".filter4"
              style="cursor: pointer; font-weight: normal"
            >
              Danh sách ô tiêu chuẩn
              <span
                :class="
                  isStatus == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <br />
            <li
              class="collapse filter4"
              style="margin-bottom: 10px"
              v-for="item in dataFilter.phan_loai"
              :key="item.id"
            >
              <a
                @click="filterTable(item.id)"
                style="cursor: pointer"
                :style="
                  filter.phan_loai_id == item.id
                    ? 'color: #408c5b; font-weight: bold'
                    : 'color: black'
                "
              >
                {{ item.name }}
                <span style="float: right">{{ item.so_luong }}</span>
              </a>
            </li>
          </ul>
        </div>
      </div> -->
      <div style="padding: 45px">
        <div class="d-flex">
          <div style="flex: 1; font-size: 18px; font-weight: bold">
            Danh sách ô tiêu chuẩn
          </div>
          <div class="d-flex">
            <input class="form-control" placeholder="Tìm kiếm" v-model="search" @keyup.enter="timKiem" />
          </div>
        </div>
        <div>
          <button type="button" class="btn btn-info btn-rounded" data-bs-toggle="modal" data-bs-target="#exampleModal"
            data-bs-placement="top" title="Thêm mới" @click="showFormAdd">
            <i class="fas fa-plus"></i> Thêm mới
          </button>
        </div>
        <hr />
        <div class="scrollable">
          <div class="table-responsive">
            <table class="
                table table-hover
                contact-list
                footable footable-1 footable-paging footable-paging-center
                breakpoint-lg
              ">
              <thead>
                <tr class="footable-header">
                  <th style="width: 60px">STT</th>
                  <th style="display: table-cell; min-width: 70px">
                    Tên ô tiêu chuẩn
                  </th>
                  <th style="display: table-cell">Kích thước</th>
                  <th style="display: table-cell; min-width: 70px">
                    Hình dạng
                  </th>
                  <th style="display: table-cell; min-width: 70px">Địa điểm</th>

                  <th width="100" style="text-align: center">Hành động</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="dataTable && dataTable.length == 0">
                  <td colspan="6" class="emptyInfomation">
                    <h5>Dữ liệu đang được cập nhật, bạn vui lòng truy cập lại sau</h5>
                  </td>
                </tr>
                <tr v-for="(item, index) in dataTable" :key="index" style="cursor: context-menu">
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
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>{{ item.kich_thuoc }}</div>
                  </td>
                  <td>
                    <div>{{ item.hinh_dang }}</div>
                  </td>
                  <td>
                    <div>
                      {{ item.dia_diem ? item.dia_diem.orig_name : "" }}
                    </div>
                  </td>
                  <td>
                    <button type="button" title="Chỉnh sửa" @click="showFormEdit(item)" class="
                        btn btn-info btn-outline btn-circle btn-small
                        m-r-5
                      ">
                      <i class="ti-pencil-alt"></i>
                    </button>
                    <button type="button" title="Xóa" @click="showXoa(item)" class="
                        btn btn-info btn-outline btn-circle btn-small
                        m-r-5
                      ">
                      <i class="ti-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <td colspan="6">
            <paginate v-model="page" :page-count="pageCount" :page-range="3" :margin-pages="2"
              :click-handler="clickCallback" :prev-text="'‹‹'" :next-text="'››'" :container-class="'pagination'"
              :page-class="'page-item'"></paginate>
          </td>
        </div>
      </div>
      <modal v-if="showForm" @close="showForm = false" :width="1200"
        :title="edit ? 'Cập nhật ô tiêu chuẩn' : 'Thêm ô tiêu chuẩn'">
        <div slot="body">
          <div class="d-flex">
            <div style="padding-right: 15px; flex: 1">
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="soquocgiathamgia" class="form-label">Tên ô tiêu chuẩn<span class="red-dot">*</span>
                  </label>
                  <input type="text" class="form-control" name="tendichvu" v-model="form.ten"  v-validate="'required|max:255'" />
                  <span v-show="errors.has('tendichvu')" class="help is-danger" style="color: red; font-size: 13px;">Tên ô tiêu chuẩn
                    không thể bỏ trống | Tối đa 255 ký tự</span>
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="soquocgiathamgia" class="form-label">Khu sinh thái<span class="red-dot"></span>
                  </label>
                  <input type="text" class="form-control"  v-validate="'max:255'"  name="khusinhthai" v-model="form.khu_sinh_thai" />
                  <span v-show="errors.has('khusinhthai')" class="help is-danger" style="color: red; font-size: 13px;">
                     Tối đa 255 ký tự</span>
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="soquocgiathamgia" class="form-label">Kích thước<span class="red-dot"></span>
                  </label>
                  <input type="text" class="form-control" name="kichthuoc" v-validate="'max:255'" v-model="form.kich_thuoc" />
                  <span v-show="errors.has('kichthuoc')" class="help is-danger" style="color: red; font-size: 13px;">
                     Tối đa 255 ký tự</span>
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="soquocgiathamgia" class="form-label">Hình dạng<span class="red-dot"></span>
                  </label>
                  <input type="text" class="form-control" name="hinhdang" v-validate="'max:255'"  v-model="form.hinh_dang" />
                  <span v-show="errors.has('hinhdang')" class="help is-danger" style="color: red; font-size: 13px;">
                     Tối đa 255 ký tự</span>
                </div>
              </div>
              <br />
              <div style="flex: 1; padding-right: 10px">
                <label for="ten" class="form-label">Địa điểm <span class="red-dot"></span></label>
                <multiselect v-model="diaDiemSelect" :options="diadiems" :searchable="true" track-by="id"
                  :label="diaDiemSelect && diaDiemSelect.name ? 'name' : 'orig_name'" placeholder="Chọn Địa điểm" :show-labels="false">
                  <template slot-scope="props" slot="option">
                    <div>
                      {{ props.option.name || props.option.orig_name }}
                    </div>
                  </template>
                </multiselect>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="soquocgiathamgia" class="form-label">Độ cao / Độ sâu (Độ sâu {{"<"}} 0) </label>
                      <input type="number" class="form-control" placeholder="Đơn vị (m)" v-model="form.do_cao" />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label">Vị trí</label>
                  <input type="text" name="vitri" v-validate="'max:255'"  class="form-control" v-model="form.vi_tri" />
                  <span v-show="errors.has('vitri')" class="help is-danger" style="color: red; font-size: 13px;">
                     Tối đa 255 ký tự</span>
                </div>
              </div>
            </div>
            <div style="padding-left: 15px; flex: 1">
              <div style="flex: 1">
                <div class="d-flex">
                  <div style="flex: 1">
                    <label for="ten" class="form-label">Không gian</label>
                  </div>
                </div>
                <div class="chieucao">
                  <UploadableMap ref="uploadable-map" @change="form.geom = $event" :geometry="form.geom" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div slot="footer">
          <button type="button" class="btn btn-success" v-if="!edit" @click="add" :disabled="disableButton">
            <i class="fas fa-plus"></i>
            Thêm mới
          </button>
          <button type="button" class="btn btn-success" v-else @click="update" :disabled="disableButton">
            <i class="fas fa-check"></i>
            Cập nhật
          </button>
        </div>
      </modal>

      <modal v-if="confirmDelete" @close="confirmDelete = false" title="Xóa dữ liệu">
        <div slot="body">
          {{ contentDelete }}
        </div>
        <div slot="footer">
          <button type="button" class="btn btn-danger" @click="xoaDuLieu()">
            <i class="fas fa-trash-alt"></i>
            Xóa
          </button>
        </div>
      </modal>
    </div>
  </div>
</template>

<script>
import { ValidationProvider } from "vee-validate";
import * as env from "../../../../env.js";
import Multiselect from "../../../vue-multiselect";
import UploadableMap from "../../../UploadableMap.vue";

export default {

  components: { ValidationProvider, Multiselect, Map, UploadableMap },
  props: ["diadiems"],
  data: () => ({
    search: null,
    typeNotification: null,
    textNotification: null,
    loading: false,
    showForm: false,
    disableButton: false,
    page: 1,
    pageCount: 0,
    edit: false,
    tienTrinhUpload: null,
    fileList: [],
    dataTable: [],
    confirmDelete: false,
    idDelete: null,
    delLink: null,
    contentDelete: "Bạn có chắc chắn muốn xóa dữ liệu này?",
    dataFilter: {},
    diaDiemSelect: null,
    form: {
      ten: null,
      khu_sinh_thai: null,
      kich_thuoc: null,
      do_cao: null,
      hinh_dang: null,
      dia_diem_id: null,
      vi_tri: null,
      geom: null,
    },
    danhMucs: {},
    isStatus: 1,
    isBanHanh: 1,
    isHieuLuc: 1,
    isThamGia: 1,
    searchDatasetType: null,
    filter: {
      nguon_kinh_phi_id: null,
    },
  }),
  mounted() {
    this.getData();
    this.getChiSo();
  },

  methods: {
    resetForm() {
      this.diaDiemSelect = null;
      this.form = {
        ten: null,
        khu_sinh_thai: null,
        kich_thuoc: null,
        do_cao: null,
        hinh_dang: null,
        dia_diem_id: null,
        vi_tri: null,
        geom: null,
      };
      if (this.$refs["uploadable-map"])
        this.$refs["uploadable-map"].removeFeature();
    },
    showXoa(data) {
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa ô tiêu chuẩn " + data.ten + " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/otieuchuan/delete/";
    },
    xoaDuLieu() {
      axios
        .delete(env.endpointhttp + this.delLink + this.idDelete)
        .then(() => {
          this.typeNotification = 2;
          this.textNotification = "Xóa thành công";
          this.getData();
        })
        .catch(() => {
          this.typeNotification = 1;
          this.textNotification = "Vui lòng xóa các dữ liệu trực thuộc";
        })
        .finally(() => {
          this.confirmDelete = false;
        });
    },
    showFormAdd() {
      this.edit = false;
      this.showForm = true;
      this.resetForm();
    },
    showFormEdit(data) {
      Object.assign(this.form, data);
      this.edit = true;
      this.showForm = true;
      this.form.id = data.id;
      this.form.geom = data.geom;
      this.diaDiemSelect = Object.assign({}, data.dia_diem);
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        this.form.dia_diem_id = this.diaDiemSelect
          ? this.diaDiemSelect.id
          : null;
        if (validate) {
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/otieuchuan/add", this.form)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Thêm mới thành công";
                this.getData();
              }
              else {
                this.textNotification = response.data.error[Object.keys(response.data.error)[0]];
                this.typeNotification = 2;
                this.textNotification = "Thêm mới thành công";
              }
            })
            .catch(() => {
              this.typeNotification = 1;
              this.textNotification = "Thêm mới thất bại";
            })
            .finally(() => {
              this.loading = false;
              this.showForm = false;
              this.typeNotification = null;
              this.textNotification = null;
              this.disableButton = false;
            });
        }
      });
    },
    update() {
      this.$validator.validateAll().then((validate) => {
        this.form.dia_diem_id = this.diaDiemSelect
          ? this.diaDiemSelect.id
          : null;
        if (validate) {
          axios
            .post(env.endpointhttp + "admin/otieuchuan/edit", this.form)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Cập nhật thành công";
                this.getData();
                this.getChiSo();
              }
              else {
                this.typeNotification = 1;
                this.textNotification = response.data.message;
              }
            })
            .catch(() => {
              this.typeNotification = 1;
              this.textNotification = "Cập nhật thất bại";
            })
            .finally(() => {
              this.loading = false;
              this.showForm = false;
              this.typeNotification = null;
              this.textNotification = null;
            });
        }
      });
    },
    filterTable(phan_loai_id) {
      if (this.filter.phan_loai_id == phan_loai_id) {
        this.filter.phan_loai_id = null;
      } else {
        this.filter.phan_loai_id = phan_loai_id;
      }
      this.timKiem();
    },
    getData() {
      axios
        .get(env.endpointhttp + "admin/otieuchuan/get", {
          params: {
            page: this.page,
            search: this.search,
          },
        })
        .then((res) => {
          this.dataTable = res.data.data;
          this.page = res.data.current_page;
          this.pageCount = res.data.last_page;
        });
    },
    clickUpload() {
      this.$refs["upload-files"].click();
    },
    async handleUpload(e) {
      var isValidate = true;
      let files = e.target.files;
      let data = new FormData();
      if (!files || files.length == 0) {
        return;
      }
      for (let el of files) {
        const isLt2M = el.size / 1024 / 1024 < 50;
        if (!isLt2M) {
          this.typeNotification = 1;
          this.textNotification = "File phải nhỏ hơn 50Mb";
          isValidate = false;
          this.files = [];
          break;
        }
        data.append("file[]", el);
      }

      if (!isValidate) return;

      try {
        this.tienTrinhUpload = 1;
        let File_id = await axios.post("uploadfiles", data, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
          onUploadProgress: (progressEvent) => {
            var percentCompleted = Math.round(
              (progressEvent.loaded * 100) / progressEvent.total
            );
            this.tienTrinhUpload = percentCompleted;
          },
        });
        this.fileList = [...this.fileList, ...File_id.data.files];
        this.form.files = this.fileList.map((el) => el.id);
      } catch (error) {
        console.log(error);
      }
      this.$refs["upload-files"].value = null;
    },
    xoaTapTin(id) {
      this.fileList = this.fileList.filter((el) => el.id != id)
        ? this.fileList.filter((el) => el.id != id)
        : [];
      this.form.files = this.fileList.map((el) => el.id)
        ? this.fileList.map((el) => el.id)
        : [];
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.getData();
    },
    timKiem() {
      this.page = 1;
      this.getData();
    },
    taiTapTin(data) {
      window.open("/" + data.disk, "_blank");
    },
    showXoaBoLoc() {
      if (this.filter.phan_loai_id == null) {
        return false;
      } else return true;
    },
    deleteSearchFilter() {
      this.filter = {
        phan_loai_id: null,
      };
      this.showXoaBoLoc();
      this.timKiem();
    },
    getChiSo() {
      axios
        .get(env.endpointhttp + "admin/dichvuhesinhthai/chiso")
        .then((res) => {
          this.dataFilter = res.data;
        });
    },
  },
};
</script>

<style>
.chieucao {
  height: 400px
}

@media only screen and (max-height: 800px) {
  .chieucao {
    height: 300px
  }
}
@media only screen and (max-height: 600px) {
  .chieucao {
    height: 200px
  }
}
</style>
