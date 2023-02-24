<template>
  <div class="white-box p-0">
    <div class="page-aside">
      <components-notifications :typeInput="typeNotification" :textInput="textNotification"></components-notifications>
      <div class="loading-roles" v-if="loading">
        <components-loading-page></components-loading-page>
      </div>
      <div class="left-aside">
        <div class="scrollable">
          <div class="d-flex">
            <div style="fonts-size: 16px; font-weight: bold; flex: 1">
              Bộ lọc
            </div>
            <button type="button" class="btn-sm btn-info btn-rounded" v-if="showXoaBoLoc()"
              @click="deleteSearchFilter()">
              <span>Xóa bộ lọc</span>
            </button>
          </div>
          <hr />
          <ul class="list-style-none" style="background: white">
            <div @click="isBanHanh = -isBanHanh" data-toggle="collapse" data-target=".filter1"
              style="cursor: pointer; font-weight: normal">
              Năm bắt đầu
              <span :class="
                isBanHanh == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
              " style="float: right"></span>
            </div>
            <li class="collapse filter1" style="margin-left: 15px; padding-top: 10px">
              <input placeholder="Nhập năm bắt đầu" v-model="filter.bat_dau" type="number" @keyup.enter="timKiem" />
            </li>
            <div @click="isHieuLuc = -isHieuLuc" data-toggle="collapse" data-target=".filter2"
              style="cursor: pointer; font-weight: normal" :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              ">
              Năm kết thúc
              <span :class="
                isHieuLuc == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
              " style="float: right"></span>
            </div>
            <li class="collapse filter2" style="margin-left: 15px; padding-top: 10px">
              <input placeholder="Nhập năm kết thúc" v-model="filter.ket_thuc" type="number" @keyup.enter="timKiem" />
            </li>
          </ul>
        </div>
      </div>
      <div class="right-aside">
        <div class="d-flex">
          <div style="flex: 1; font-size: 18px; font-weight: bold">
            Chương trình, đề tài dự án về Đa dạng sinh học
          </div>
          <div class="d-flex">
            <input class="form-control" placeholder="Tìm kiếm" v-model="search" @keyup.enter="timKiem" />
          </div>
        </div>
        <div>
          <button type="button" class="btn btn-info btn-rounded" data-bs-toggle="modal" data-bs-target="#exampleModal"
            data-bs-placement="top" title="Thêm mới" @click="showFormAdd">
            {{ $t("admin.buttons.add") }}
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
                  <th style="display: table-cell; min-width: 70px">Tên</th>
                  <th style="display: table-cell">Đơn vị phối hợp</th>
                  <th style="display: table-cell; min-width: 70px">
                    Đơn vị chủ trì
                  </th>
                  <th style="display: table-cell; min-width: 70px">
                    Thời gian
                  </th>
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
                        <div style="font-size: 16px; font-weight: 400; max-width: 400px; overflow:clip">
                          {{ item.ten }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>{{ item.don_vi_phoi_hop }}</div>
                  </td>
                  <td>
                    <div>
                      {{ item.don_vi_chu_tri }}
                    </div>
                  </td>
                  <td>
                    <div>
                      <div>Bắt đầu: {{ item.thoi_gian_bat_dau }}</div>
                      <div>Kết thúc: {{ item.thoi_gian_ket_thuc }}</div>
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
      <modal v-if="showForm" @close="showForm = false" :width="1100" :title="
        edit ? 'Cập nhật chương trình đề tài' : 'Thêm chương trình đề tài'
      ">
        <div slot="body">
          <div class="d-flex">
            <div style="flex: 1; padding-right: 15px">
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ten" class="form-label">Tên trương trình đề tài
                    <span class="red-dot">*</span></label>
                  <input type="text" class="form-control" id="ten" name="tendetai" v-model="form.ten"
                    v-validate="'required|max:255'" />
                  <span v-show="errors.has('tendetai')" class="help is-danger" style="color: red; font-size: 13px;">Tên
                    đề tài không thể bỏ trống | Tối đa 255 ký tự</span>
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label">Mã số<span class="red-dot"></span></label>
                  <input type="text" class="form-control" v-model="form.ma_so" v-validate="'max:255'" name="maso" />
                  <span v-show="errors.has('maso')" class="help is-danger" style="color: red; font-size: 13px;">
                    Tối đa 255 ký tự</span>
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ngaybanhanh" class="form-label">Thời gian bắt đầu
                  </label>
                  <input type="date" class="form-control" id="ngaybanhanh" name="tenNhomChiThi"
                    v-model="form.thoi_gian_bat_dau" />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ngayhieuluc" class="form-label">Thời gian kết thúc
                  </label>
                  <input type="date" class="form-control" id="ngayhieuluc" v-model="form.thoi_gian_ket_thuc" />
                </div>
              </div>

              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ten" class="form-label">Đơn vị phối hợp <span class="red-dot"></span></label>
                  <input type="text" class="form-control" id="ten" name="donviphoihop" v-model="form.don_vi_phoi_hop"
                    v-validate="'max:255'" />
                  <span v-show="errors.has('donviphoihop')" class="help is-danger" style="color: red; font-size: 13px;">
                    Tối đa 255 ký tự</span>
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="soquocgiathamgia" class="form-label">Quận huyện
                  </label>
                  <multiselect v-model="quanHuyenSelect" :options="quanhuyens" :searchable="true" track-by="id"
                    label="name_vietnamese" placeholder="Chọn một quận huyện" :show-labels="false">
                  </multiselect>
                </div>
              </div>
              <br />
              <div>
                <div class="d-flex">
                  <label class="form-label" style="flex: 1">Tệp đính kèm
                  </label>
                  <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                    data-bs-target="#exampleModal" data-bs-placement="top" title="Tải lên" @click="clickUpload">
                    <i class="fas fa-thumbtack"></i> Tải tệp lên
                  </button>
                  <input type="file" id="myfile" name="myfile" multiple ref="upload-files" style="display: none"
                    @change="handleUpload" />
                </div>
                <br />
                <div>
                  <div class="progress" style="height: 10px" v-show="
                    tienTrinhUpload &&
                    tienTrinhUpload != 0 &&
                    tienTrinhUpload != 100
                  ">
                    <div class="progress-bar" role="progressbar" style="font-weight: bold" aria-valuenow="25"
                      aria-valuemin="0" aria-valuemax="100" :style="{ width: tienTrinhUpload + 'px' }">
                      {{ tienTrinhUpload }}
                    </div>
                  </div>
                  <br />
                  <div class="c-flex">
                    <div class="d-flex" v-for="item in fileList" :key="item.id">
                      <div @click="taiTapTin(item)" style="flex: 1; padding-bottom: 5px; cursor: pointer"
                        :title="item.name">
                        {{
                            item.name && 40 > Number(item.name.length)
                              ? item.name
                              : "..." + item.name.substr(-39)
                        }}
                      </div>
                      <div>
                        <i class="fas fa-trash-alt" style="cursor: pointer" @click="xoaTapTin(item.id)"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div style="flex: 1; padding-left: 15px">
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ngaybanhanh" class="form-label">Thuộc chương trình
                  </label>
                  <input class="form-control"  v-validate="'max:255'" id="ngaybanhanh" name="chuongtrinh" v-model="form.thuoc_chuong_trinh" />
                  <span v-show="errors.has('chuongtrinh')" class="help is-danger" style="color: red; font-size: 13px;">
                    Tối đa 255 ký tự</span>
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ngayhieuluc" class="form-label">Đơn vị chủ trì
                  </label>
                  <input class="form-control"  v-validate="'max:255'"  name="donvi" id="ngayhieuluc" v-model="form.don_vi_chu_tri" />
                  <span v-show="errors.has('donvi')" class="help is-danger" style="color: red; font-size: 13px;">
                    Tối đa 255 ký tự</span>
                </div>
              </div>
              <br />
              <div>
                <label for="nhomchithi" class="form-label">Nội dung <span class="red-dot"></span></label>
                <textarea class="form-control" v-model="form.noi_dung" rows="6" />
              </div>
              <br />
            </div>
          </div>
        </div>
        <div slot="footer">
          <button type="button" class="btn btn-success" v-if="!edit" @click="add" :disabled="disableButton">
            {{ $t("admin.buttons.add") }}
          </button>
          <button type="button" class="btn btn-success" v-else @click="update" :disabled="disableButton">
            {{ $t("admin.buttons.edit") }}
          </button>
        </div>
      </modal>

      <modal v-if="confirmDelete" @close="confirmDelete = false" title="Xóa dữ liệu">
        <div slot="body">
          {{ contentDelete }}
        </div>
        <div slot="footer">
          <button type="button" class="btn btn-danger" @click="xoaDuLieu()">
            {{ $t("admin.buttons.delete") }}
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

export default {
  components: { ValidationProvider, Multiselect },
  props: ["quanhuyens"],
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
    quanHuyenSelect: null,
    confirmDelete: false,
    idDelete: null,
    delLink: null,
    contentDelete: "Bạn có chắc chắn muốn xóa dữ liệu này?",
    form: {
      ten: null,
      ma_so: null,
      thoi_gian_bat_dau: null,
      thoi_gian_ket_thuc: null,
      don_vi_phoi_hop: null,
      don_vi_chu_tri: null,
      quan_huyen_id: null,
      noi_dung: null,
      thuoc_chuong_trinh: null,
      files: [],
    },
    danhMucs: {},
    isStatus: 1,
    isBanHanh: 1,
    isHieuLuc: 1,
    isThamGia: 1,
    searchDatasetType: null,
    filter: {
      bat_dau: null,
      ket_thuc: null,
    },
  }),
  mounted() {
    this.getData();
  },

  methods: {
    resetForm() {
      this.quanHuyenSelect = null;
      this.form = {
        ten: null,
        ma_so: null,
        thoi_gian_bat_dau: null,
        thoi_gian_ket_thuc: null,
        don_vi_phoi_hop: null,
        don_vi_chu_tri: null,
        quan_huyen_id: null,
        noi_dung: null,
        thuoc_chuong_trinh: null,
        files: [],
      };
      this.fileList = [];
    },
    showXoa(data) {
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa chương trình, đề tài " +
        data.ten +
        " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/chuongtrinhdetai/delete/";
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
      this.resetForm();
      this.edit = false;
      this.showForm = true;
    },
    showFormEdit(data) {
      Object.assign(this.form, data);
      this.edit = true;
      this.showForm = true;
      if (data.files) {
        this.form.files = [...JSON.parse(data.files)];
      }
      this.fileList = [...data.fileList];
      this.quanHuyenSelect = this.quanhuyens.find(
        (el) => el.id == data.quan_huyen_id
      );
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        this.form.quan_huyen_id = this.quanHuyenSelect
          ? this.quanHuyenSelect.id
          : null;
        if (validate) {
          this.disableButton = true;
          axios
            .post(
              env.endpointhttp + "admin/chuongtrinhdetai/add",
              this.form
            )
            .then(() => {
              this.typeNotification = 2;
              this.textNotification = "Thêm mới thành công";
              this.getData();
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
        this.form.quan_huyen_id = this.quanHuyenSelect
          ? this.quanHuyenSelect.id
          : null;
        if (validate) {
          axios
            .put(
              env.endpointhttp + "admin/chuongtrinhdetai/edit",
              this.form
            )
            .then(() => {
              this.typeNotification = 2;
              this.textNotification = "Cập nhật thành công";
              this.getData();
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
    getData() {
      axios
        .get(env.endpointhttp + "admin/chuongtrinhdetai/list", {
          params: {
            page: this.page,
            search: this.search,
            bat_dau: this.filter.bat_dau,
            ket_thuc: this.filter.ket_thuc,
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
      if (this.filter.bat_dau == null && this.filter.ket_thuc == null) {
        return false;
      } else return true;
    },
    deleteSearchFilter() {
      this.filter = {
        bat_dau: null,
        ket_thuc: null,
      };
      this.showXoaBoLoc();
      this.timKiem();
    },
    searchHieuLuc(hieuLuc) {
      if (this.filter.hieu_luc === hieuLuc) {
        this.filter.hieu_luc = null;
      } else {
        this.filter.hieu_luc = hieuLuc;
      }
      this.timKiem();
    },
  },
};
</script>

<style>

</style>