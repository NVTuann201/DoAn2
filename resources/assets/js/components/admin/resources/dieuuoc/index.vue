<template>
  <div class="white-box p-0">
    <div class="page-aside">
      <components-notifications
        :typeInput="typeNotification"
        :textInput="textNotification"
      ></components-notifications>
      <div class="loading-roles" v-if="loading">
        <components-loading-page></components-loading-page>
      </div>
      <div class="left-aside">
        <div class="scrollable">
          <div class="d-flex">
            <div style="fonts-size: 16px; font-weight: bold; flex: 1">
              {{ $t("admin.label.filter") }}
            </div>
            <button
              type="button"
              class="btn-sm btn-info btn-rounded"
              v-if="showXoaBoLoc()"
              @click="deleteSearchFilter()"
            >
              <span>{{ $t("admin.label.reset_filter") }}</span>
            </button>
          </div>
          <hr />
          <ul class="list-style-none" style="background: white">
            <div
              @click="isBanHanh = -isBanHanh"
              data-toggle="collapse"
              data-target=".filter1"
              style="cursor: pointer; font-weight: normal"
            >
              Năm ban hành
              <span
                :class="
                  isBanHanh == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <li
              class="collapse filter1"
              style="margin-left: 15px; padding-top: 10px"
            >
              <input
                placeholder="Nhập năm ban hành"
                v-model="filter.nam_ban_hanh"
                type="number"
                @keyup.enter="timKiem"
              />
            </li>
            <div
              @click="isHieuLuc = -isHieuLuc"
              data-toggle="collapse"
              data-target=".filter2"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              "
            >
              Năm có hiệu lực
              <span
                :class="
                  isHieuLuc == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <li
              class="collapse filter2"
              style="margin-left: 15px; padding-top: 10px"
            >
              <input
                placeholder="Nhập năm có hiệu lực"
                v-model="filter.name_co_hieu_luc"
                type="number"
                @keyup.enter="timKiem"
              />
            </li>
            <div
              @click="isThamGia = -isThamGia"
              data-toggle="collapse"
              data-target=".filter3"
              style="cursor: pointer; font-weight: normal"
            >
              Năm Việt Nam tham gia
              <span
                :class="
                  isThamGia == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <li
              class="collapse filter3"
              style="margin-left: 15px; padding-top: 10px"
            >
              <input
                placeholder="Nhập năm Việt Nam tham gia"
                v-model="filter.nam_viet_nam_tham_gia"
                type="number"
                @keyup.enter="timKiem"
              />
            </li>

            <div
              @click="isStatus = -isStatus"
              data-toggle="collapse"
              data-target=".filter4"
              style="cursor: pointer; font-weight: normal"
              :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              "
            >
              Hiệu lực
              <span
                :class="
                  isStatus == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
                "
                style="float: right"
              ></span>
            </div>
            <br />
            <li class="collapse filter4" style="margin-left: 15px">
              <a
                @click="searchHieuLuc(true)"
                style="cursor: pointer"
                :style="
                  filter.hieu_luc == true
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
                Còn Hiệu lực
                <span></span>
              </a>
            </li>
            <li class="collapse filter4" style="margin-left: 15px">
              <a
                @click="searchHieuLuc(false)"
                style="cursor: pointer"
                :style="
                  filter.hieu_luc == false
                    ? 'color: #408c5b; font-weight: bold'
                    : ''
                "
              >
                Hết Hiệu lực
                <span></span>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="right-aside">
        <div class="d-flex">
          <div style="flex: 1; font-size: 18px; font-weight: bold">
            {{ $t("admin.dieu_uoc_quoc_te.title_form_list") }}
          </div>
          <div class="d-flex">
            <input
              class="form-control"
              placeholder="Tìm kiếm"
              v-model="search"
              @keyup.enter="timKiem"
            />
          </div>
        </div>
        <div>
          <button
            type="button"
            class="btn btn-info btn-rounded"
            data-bs-toggle="modal"
            data-bs-target="#exampleModal"
            data-bs-placement="top"
            @click="showFormAdd"
          >
            {{ $t("admin.buttons.add") }}
          </button>
        </div>
        <hr />
        <div class="scrollable">
          <div class="table-responsive">
            <table
              class="
                table table-hover
                contact-list
                footable footable-1 footable-paging footable-paging-center
                breakpoint-lg
              "
            >
              <thead>
                <tr class="footable-header">
                  <th style="width: 60px">STT</th>
                  <th style="display: table-cell; min-width: 70px">
                    Tên điều ước
                  </th>
                  <th style="display: table-cell">Ngày ban hành</th>
                  <th style="display: table-cell; min-width: 70px">
                    Ngày hiệu lực
                  </th>
                  <th style="display: table-cell; min-width: 70px">
                    Trạng thái
                  </th>
                  <th width="100" style="text-align: center">Hành động</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="dieuUocs && dieuUocs.length == 0">
                  <td colspan="6" class="emptyInfomation">
                    <h5>Dữ liệu đang được cập nhật, bạn vui lòng truy cập lại sau</h5>
                  </td>
                </tr>
                <tr
                  v-for="(item, index) in dieuUocs"
                  :key="index"
                  style="cursor: context-menu"
                >
                  <td>                   
                    <div>{{ index + 1 }}</div>
                  </td>
                  <td>
                    <div style="white-space: initial">
                      <div style="font-weight: 400; font-size: 15px">
                        {{ item.ten }}
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>{{ item.ngay_ban_hanh }}</div>
                  </td>
                  <td>{{ item.ngay_hieu_luc }}</td>
                  <td>
                    <div>
                      {{ item.hieu_luc ? "Còn hiệu lực" : "Hết hiệu lực" }}
                    </div>
                  </td>
                  <td>
                    <button
                      type="button"
                      title="Chỉnh sửa"
                      @click="showFormEdit(item)"
                      class="
                        btn btn-info btn-outline btn-circle btn-small
                        m-r-5
                      "
                    >
                      <i class="ti-pencil-alt"></i>
                    </button>
                    <button
                      type="button"
                      title="Xóa"
                      @click="showXoa(item)"
                      class="
                        btn btn-info btn-outline btn-circle btn-small
                        m-r-5
                      "
                    >
                      <i class="ti-trash"></i>
                    </button>
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
      <modal
        v-if="showForm"
        @close="showForm = false"
        :width="1000"
        :title='edit ? $t("admin.buttons.edit") : $t("admin.buttons.add")'
      >
        <div slot="body">
          <div class="d-flex">
            <div style="flex: 1; padding-right: 15px">
              <div>
                <label for="ten" class="form-label"
                  >Tên điều ước <span class="red-dot">*</span></label
                >
                <input
                  type="text"
                  class="form-control input-sm"
                  id="ten"
                  name="tendieuuoc"
                  v-model="form.ten"
                  v-validate="'required'"
                />
                <span
                  v-show="errors.has('tendieuuoc')"
                  class="help is-danger"
                  style="color: red"
                  >Tên nhóm điều ước không thể bỏ trống</span
                >
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ngaybanhanh" class="form-label"
                    >Ngày ban hành
                  </label>
                  <input
                    type="date"
                    class="form-control input-sm"
                    id="ngaybanhanh"
                    name="tenNhomChiThi"
                    v-model="form.ngay_ban_hanh"
                  />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ngayhieuluc" class="form-label"
                    >Ngày hiệu lực
                  </label>
                  <input
                    type="date"
                    class="form-control input-sm"
                    id="ngayhieuluc"
                    v-model="form.ngay_hieu_luc"
                  />
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ngayvietnamthamgia" class="form-label"
                    >Ngày Việt Nam Tham gia
                  </label>
                  <input
                    type="date"
                    class="form-control input-sm"
                    id="ngayvietnamthamgia"
                    v-model="form.ngay_viet_nam_tham_gia"
                  />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="soquocgiathamgia" class="form-label"
                    >Số quốc gia tham gia
                  </label>
                  <input
                    type="number"
                    class="form-control input-sm"
                    id="soquocgiathamgia"
                    v-model="form.so_quoc_gia_tham_gia"
                  />
                </div>
              </div>
              <br />
              <div>
                <label for="nhomchithi" class="form-label"
                  >Trạng thái hiệu lực
                </label>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    id="flexCheckDefault"
                    v-model="form.hieu_luc"
                  />
                  <label class="form-check-label" for="inlineRadio1"
                    >Còn hiệu lực</label
                  >
                </div>
              </div>
              <br />
              <div>
                <div class="d-flex">
                  <label class="form-label" style="flex: 1"
                    >Tệp đính kèm
                  </label>
                  <button
                    type="button"
                    class="btn btn-info btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#exampleModal"
                    data-bs-placement="top"
                    title="Tải lên"
                    @click="clickUpload"
                  >
                    <i class="fas fa-thumbtack"></i> Tải tệp lên
                  </button>
                  <input
                    type="file"
                    id="myfile"
                    name="myfile"
                    multiple
                    ref="upload-files"
                    style="display: none"
                    @change="handleUpload"
                  />
                </div>
                <br />
                <div>
                  <div
                    class="progress"
                    style="height: 10px"
                    v-show="
                      tienTrinhUpload &&
                      tienTrinhUpload != 0 &&
                      tienTrinhUpload != 100
                    "
                  >
                    <div
                      class="progress-bar"
                      role="progressbar"
                      style="font-weight: bold"
                      aria-valuenow="25"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      :style="{ width: tienTrinhUpload + 'px' }"
                    >
                      {{ tienTrinhUpload }}
                    </div>
                  </div>
                  <div class="c-flex">
                    <div class="d-flex" v-for="item in fileList" :key="item.id">
                      <div
                        @click="taiTapTin(item)"
                        style="flex: 1; padding-bottom: 5px; cursor: pointer"
                        :title="item.name"
                      >
                        {{
                          item.name && item.name.length < 40
                            ? item.name
                            : "..." + item.name.substr(-39)
                        }}
                      </div>
                      <div>
                        <i
                          class="fas fa-trash-alt"
                          style="cursor: pointer"
                          @click="xoaTapTin(item.id)"
                        ></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div style="flex: 1; padding-left: 15px">
              <div>
                <label for="nhomchithi" class="form-label"
                  >Nội dung chính <span class="red-dot"></span
                ></label>
                <textarea
                  class="form-control"
                  v-model="form.noi_dung_chinh"
                  rows="3"
                />
              </div>
              <br />
              <div>
                <label for="nhomchithi" class="form-label"
                  >Nội dung toàn văn <span class="red-dot"></span
                ></label>
                <textarea
                  class="form-control"
                  v-model="form.noi_dung_toan_van"
                  rows="3"
                />
              </div>
              <br />
              <div>
                <label for="nhomchithi" class="form-label"
                  >Ghi chú <span class="red-dot"></span
                ></label>
                <textarea class="form-control" v-model="form.ghi_chu" />
              </div>
            </div>
          </div>
        </div>
        <div slot="footer">
          <button
            type="button"
            class="btn btn-success"
            v-if="!edit"
            @click="add"
            :disabled="disableButton"
          >
            {{ $t("admin.buttons.add") }}
          </button>
          <button
            type="button"
            class="btn btn-success"
            v-else
            @click="update"
            :disabled="disableButton"
          >
            {{ $t("admin.buttons.edit") }}
          </button>
        </div>
      </modal>

      <modal
        v-if="confirmDelete"
        @close="confirmDelete = false"
        title="Xóa dữ liệu"
      >
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

export default {
  components: { ValidationProvider },
  data: () => ({
    confirmDelete: false,
    idDelete: null,
    delLink: null,
    contentDelete: "Bạn có chắc chắn xóa dữ liệu này?",
    search: null,
    typeNotification: null,
    textNotification: null,
    disableButton: false,
    loading: false,
    showForm: false,
    page: 1,
    pageCount: 0,
    edit: false,
    tienTrinhUpload: null,
    fileList: [],
    dieuUocs: [],
    form: {
      ten: null,
      ngay_ban_hanh: null,
      ngay_hieu_luc: null,
      ngay_viet_nam_tham_gia: null,
      so_quoc_gia_tham_gia: null,
      hieu_luc: true,
      noi_dung_chinh: null,
      noi_dung_toan_van: null,
      ghi_chu: null,
      files: [],
    },
    isStatus: 1,
    isBanHanh: 1,
    isHieuLuc: 1,
    isThamGia: 1,
    searchDatasetType: null,
    filter: {
      nam_ban_hanh: null,
      nam_co_hieu_luc: null,
      nam_viet_nam_tham_gia: null,
      hieu_luc: null,
    },
  }),
  mounted() {
    this.getData();
  },

  methods: {
    showXoa(data) {
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa thông số quan trắc " + data.ten + " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/dieuuocquocte/delete/";
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
          this.textNotification = "Vui lòng xóa các dữ liệu ràng buộc khác";
        })
        .finally(() => {
          this.confirmDelete = false;
        });
    },

    resetForm() {
      this.form = {
        ten: null,
        ngay_ban_hanh: null,
        ngay_hieu_luc: null,
        ngay_viet_nam_tham_gia: null,
        so_quoc_gia_tham_gia: null,
        hieu_luc: true,
        noi_dung_chinh: null,
        noi_dung_toan_van: null,
        ghi_chu: null,
        files: [],
      };
      this.fileList = [];
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
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/dieuuocquocte/add", this.form)
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
        if (validate) {
          axios
            .put(env.endpointhttp + "admin/dieuuocquocte/edit", this.form)
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
        .get(env.endpointhttp + "admin/dieuuocquocte/list", {
          params: {
            page: this.page,
            search: this.search,
            nam_ban_hanh: this.filter.nam_ban_hanh,
            nam_co_hieu_luc: this.filter.nam_co_hieu_luc,
            nam_viet_nam_tham_gia: this.filter.nam_viet_nam_tham_gia,
            hieu_luc: this.filter.hieu_luc,
          },
        })
        .then((res) => {
          this.dieuUocs = res.data.data;
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
      if (
        this.filter.nam_ban_hanh == null &&
        this.filter.nam_co_hieu_luc == null &&
        this.filter.nam_viet_nam_tham_gia == null &&
        this.filter.hieu_luc == null
      ) {
        return false;
      } else return true;
    },
    deleteSearchFilter() {
      this.filter = {
        nam_ban_hanh: null,
        nam_co_hieu_luc: null,
        nam_viet_nam_tham_gia: null,
        hieu_luc: null,
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