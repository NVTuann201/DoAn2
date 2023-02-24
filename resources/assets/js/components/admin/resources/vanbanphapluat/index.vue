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
              Năm ban hành
              <span :class="
                isBanHanh == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
              " style="float: right"></span>
            </div>
            <li class="collapse filter1" style="margin-left: 15px; padding-top: 10px">
              <input placeholder="Nhập năm ban hành" v-model="filter.nam_ban_hanh" type="number"
                @keyup.enter="timKiem" />
            </li>
            <div @click="isHieuLuc = -isHieuLuc" data-toggle="collapse" data-target=".filter2"
              style="cursor: pointer; font-weight: normal" :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              ">
              Năm có hiệu lực
              <span :class="
                isHieuLuc == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
              " style="float: right"></span>
            </div>
            <li class="collapse filter2" style="margin-left: 15px; padding-top: 10px">
              <input placeholder="Nhập năm có hiệu lực" v-model="filter.name_co_hieu_luc" type="number"
                @keyup.enter="timKiem" />
            </li>

            <div @click="isStatus = -isStatus" data-toggle="collapse" data-target=".filter5"
              style="cursor: pointer; font-weight: normal" :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              ">
              Cơ quan ban hành
              <span :class="
                isStatus == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
              " style="float: right"></span>
            </div>
            <li class="collapse filter5" style="margin-left: 15px" v-for="item in dataFilter.co_quan" :key="item.id">
              <a @click="selectFilter(item.id, 'co_quan_id')" style="cursor: pointer" :style="
                filter.co_quan_id == item.id
                  ? 'color: #408c5b; font-weight: bold'
                  : ''
              ">
                {{ item.name }}
                <span>{{ item.so_luong }}</span>
              </a>
            </li>

            <div @click="isStatus = -isStatus" data-toggle="collapse" data-target=".filter7"
              style="cursor: pointer; font-weight: normal" :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              ">
              Loại
              <span :class="
                isStatus == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
              " style="float: right"></span>
            </div>
            <li class="collapse filter7" style="margin-left: 15px" v-for="item in dataFilter.loai" :key="item.id">
              <a @click="selectFilter(item.id, 'loai_id')" style="cursor: pointer" :style="
                filter.loai_id == item.id
                  ? 'color: #408c5b; font-weight: bold'
                  : ''
              ">
                {{ item.name }}
                <span>{{ item.so_luong }}</span>
              </a>
            </li>

            <div @click="isStatus = -isStatus" data-toggle="collapse" data-target=".filter6"
              style="cursor: pointer; font-weight: normal" :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              ">
              Lĩnh vực
              <span :class="
                isStatus == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
              " style="float: right"></span>
            </div>
            <li class="collapse filter6" style="margin-left: 15px" v-for="item in dataFilter.linh_vuc" :key="item.id">
              <a @click="selectFilter(item.id, 'linh_vuc_id')" style="cursor: pointer" :style="
                filter.linh_vuc_id == item.id
                  ? 'color: #408c5b; font-weight: bold'
                  : ''
              ">
                {{ item.name }}
                <span>{{ item.so_luong }}</span>
              </a>
            </li>

            <div @click="isStatus = -isStatus" data-toggle="collapse" data-target=".filter4"
              style="cursor: pointer; font-weight: normal" :style="
                searchDatasetType ? 'color: #000000; font-weight: bold' : ''
              ">
              Hiệu lực
              <span :class="
                isStatus == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'
              " style="float: right"></span>
            </div>
            <li class="collapse filter4" style="margin-left: 15px" v-for="(item, index) in dataFilter.hieu_luc"
              :key="index">
              <a @click="selectFilter(item.id, 'hieu_luc')" style="cursor: pointer" :style="
                filter.hieu_luc == item.id
                  ? 'color: #408c5b; font-weight: bold'
                  : ''
              ">
                {{ item.name }}
                <span>{{ item.so_luong }}</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="right-aside">
        <div class="d-flex">
          <div style="flex: 1; font-size: 18px; font-weight: bold">
            Danh sách văn bản pháp luật
          </div>
          <div class="d-flex">
            <input class="form-control" placeholder="Tìm kiếm" v-model="search" @keyup.enter="timKiem" />
          </div>
        </div>
        <div>
          <button type="button" class="btn btn-info btn-rounded" data-bs-toggle="modal" data-bs-target="#exampleModal"
            data-bs-placement="top" @click="showFormAdd">
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
                  <th style="display: table-cell; min-width: 70px">Văn bản</th>
                  <th style="display: table-cell">Số hiệu</th>
                  <th style="display: table-cell; min-width: 70px">
                    Cơ quan ban hành
                  </th>
                  <th style="display: table-cell; min-width: 70px">
                    Thời gian
                  </th>
                  <th style="display: table-cell; min-width: 70px">
                    Trạng thái
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
                        <div style="font-size: 16px; font-weight: 400; max-width: 300px; overflow:clip">
                          {{ item.phan_loai ? item.phan_loai.name : "" }}
                        </div>
                        <div style="font-weight: 400; font-size: 14px; max-width: 300px; overflow:clip">
                          {{ item.ten }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div style="max-width: 300px; overflow:clip">{{ item.so_hieu }}</div>
                  </td>
                  <td>
                    <div style="max-width: 300px; overflow:clip">
                      {{
                      item.co_quan_ban_hanh ? item.co_quan_ban_hanh.name : ""
                      }}
                    </div>
                  </td>
                  <td>
                    <div>
                      <div>Ban hành: {{ item.ngay_ban_hanh }}</div>
                      <div>Hiệu lực: {{ item.ngay_hieu_luc }}</div>
                    </div>
                  </td>
                  <td>
                    <div>
                      {{ item.hieu_luc ? "Còn hiệu lực" : "Hết hiệu lực" }}
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
        :title="edit ? 'Cập nhật văn bản pháp luật' : 'Thêm văn bản pháp luật'">
        <div slot="body">
          <div class="d-flex">
            <div style="flex: 1; padding-right: 15px">
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ten" class="form-label">Loại văn bản pháp luật
                    <span class="red-dot">*</span></label>
                  <multiselect v-model="loaiVanBanSelect" :options="danhmucs.phan_loais" :searchable="true"
                    track-by="id" name="loaiVanBanSelect" label="name" placeholder=" Chọn loại văn bản"
                    v-validate="'required'" :show-labels="false">
                  </multiselect>
                  <span v-show="errors.has('loaiVanBanSelect')" class="help is-danger" style="color: red">Loại không thể
                    bỏ trống</span>
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label">Tên Văn bản pháp luật <span class="red-dot">*</span></label>
                  <input type="text" class="form-control" id="ten" name="tenvanban" v-model="form.ten"
                    v-validate="'required'" />
                  <span v-show="errors.has('tenvanban')" class="help is-danger" style="color: red">Tên không thể bỏ
                    trống</span>
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ten" class="form-label">Số hiệu văn bản <span class="red-dot">*</span></label>
                  <input type="text" class="form-control" id="ten" name="sohieu" v-model="form.so_hieu"
                    v-validate="'required'" />
                  <span v-show="errors.has('sohieu')" class="help is-danger" style="color: red">Số hiệu không thể bỏ
                    trống</span>
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ten" class="form-label">Cơ quan ban hành <span class="red-dot">*</span></label>
                  <multiselect v-model="coQuanSelect" :options="danhmucs.co_quans" :searchable="true" track-by="id"
                    name="coquanSelect" label="name" placeholder=" Chọn cơ quan ban hành" v-validate="'required'"
                    :show-labels="false">
                  </multiselect>
                  <span v-show="errors.has('coquanSelect')" class="help is-danger" style="color: red">Cơ quan ban hành
                    không thể bỏ trống</span>
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ngaybanhanh" class="form-label">Ngày ban hành
                  </label>
                  <input type="date" class="form-control" id="ngaybanhanh" name="tenNhomChiThi"
                    v-model="form.ngay_ban_hanh" />
                </div>
                <div style="flex: 1; padding-left: 10px">
                  <label for="ngayhieuluc" class="form-label">Ngày hiệu lực
                  </label>
                  <input type="date" class="form-control" id="ngayhieuluc" v-model="form.ngay_hieu_luc" />
                </div>
              </div>
              <br />
              <div class="d-flex">
                <div style="flex: 1; padding-right: 10px">
                  <label for="ngayvietnamthamgia" class="form-label">Lĩnh vực<span class="red-dot">*</span>
                  </label>
                  <multiselect v-model="linhVucSelect" :options="danhmucs.linh_vucs" :searchable="true" track-by="id"
                    name="selectLinhVuc" label="name" placeholder=" Chọn một lĩnh vực" v-validate="'required'"
                    :show-labels="false">
                  </multiselect>
                  <span v-show="errors.has('selectLinhVuc')" class="help is-danger" style="color: red">Lĩnh vực không
                    thể bỏ trống</span>
                </div>
                <div style="flex: 1; padding-left: 10px" class="c-flex">
                  <label for="soquocgiathamgia" class="form-label">Trạng thái hiệu lực
                  </label>
                  <div style="padding-top: 5px">
                    <input class="form-check-input" type="checkbox" id="flexCheckDefault" v-model="form.hieu_luc"/>
                    <label class="form-check-label" for="inlineRadio1">Còn hiệu lực</label>
                  </div>
                </div>
              </div>
            </div>
            <div style="flex: 1; padding-left: 15px">
              <div>
                <label for="nhomchithi" class="form-label">Nội dung chính <span class="red-dot"></span></label>
                <textarea class="form-control" v-model="form.noi_dung_chinh" rows="3" onpaste="return false" oncopy="return false"/>
              </div>
              <br />
              <div>
                <label for="nhomchithi" class="form-label">Ghi chú <span class="red-dot"></span></label>
                <textarea class="form-control" v-model="form.ghi_chu" onpaste="return false" oncopy="return false"/>
              </div>
              <br />
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
                  <div class="c-flex">
                    <div class="d-flex" v-for="item in fileList" :key="item.id">
                      <div @click="taiTapTin(item)" style="flex: 1; padding-bottom: 5px; cursor: pointer"
                        :title="item.name">
                        {{
                        item.name && item.name.length < 40 ? item.name : "..." + item.name.substr(-39) }} </div>
                          <div>
                            <i class="fas fa-trash-alt" style="cursor: pointer" @click="xoaTapTin(item.id)"></i>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
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
  props: ["danhmucs"],
  components: { ValidationProvider, Multiselect },
  data: () => ({
    search: null,
    typeNotification: null,
    textNotification: null,
    confirmDelete: false,
    idDelete: null,
    delLink: null,
    contentDelete: "Bạn có chắc chắn muốn xóa dữ liệu này?",
    loading: false,
    showForm: false,
    page: 1,
    pageCount: 0,
    edit: false,
    tienTrinhUpload: null,
    fileList: [],
    loaiVanBanSelect: null,
    coQuanSelect: null,
    linhVucSelect: null,
    disableButton: false,
    dataTable: [],
    form: {
      loai_id: null,
      ten: null,
      so_hieu: null,
      co_quan_ban_hanh_id: null,
      ngay_ban_hanh: null,
      ngay_hieu_luc: null,
      linh_vuc_id: null,
      hieu_luc: true,
      noi_dung_chinh: null,
      noi_dung_toan_van: null,
      ghi_chu: null,
      files: [],
    },
    danhMucs: {},
    isStatus: 1,
    isBanHanh: 1,
    isHieuLuc: 1,
    isThamGia: 1,
    searchDatasetType: null,
    dataFilter: {},
    filter: {
      nam_ban_hanh: null,
      nam_co_hieu_luc: null,
      nam_viet_nam_tham_gia: null,
      hieu_luc: null,
      co_quan_id: null,
      loai_id: null,
      linh_vuc_id: null,
    },
  }),
  mounted() {
    this.getData();
    this.getDanhMuc();
    this.getChiSo();
  },

  methods: {
    showXoa(data) {
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa " +
        data.phan_loai.name +
        " " +
        data.so_hieu +
        " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/vanbanphapluat/delete/";
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
    resetForm() {
      this.loaiVanBanSelect = null;
      this.coQuanSelect = null;
      this.linhVucSelect = null;
      this.form = {
        loai_id: null,
        ten: null,
        so_hieu: null,
        co_quan_ban_hanh_id: null,
        ngay_ban_hanh: null,
        ngay_hieu_luc: null,
        linh_vuc_id: null,
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
      this.linhVucSelect = Object.assign({}, data.linh_vuc);
      this.coQuanSelect = Object.assign({}, data.co_quan_ban_hanh);
      this.loaiVanBanSelect = Object.assign({}, data.phan_loai);
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        this.form.loai_id = this.loaiVanBanSelect
          ? this.loaiVanBanSelect.id
          : null;
        this.form.co_quan_ban_hanh_id = this.coQuanSelect
          ? this.coQuanSelect.id
          : null;
        this.form.linh_vuc_id = this.linhVucSelect
          ? this.linhVucSelect.id
          : null;
        if (validate) {
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/vanbanphapluat/add", this.form)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Thêm mới thành công";
                this.getData();
              }
              else if(response.data.message=='datetime'){
                this.typeNotification = 1;
                this.textNotification = 'Ngày ban hành phải nhỏ hơn ngày hiệu lực'
              }
              else {
                this.typeNotification = 1;
                this.textNotification = response.data.error[Object.keys(response.data.error)[0]];
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
        this.form.loai_id = this.loaiVanBanSelect
          ? this.loaiVanBanSelect.id
          : null;
        this.form.co_quan_ban_hanh_id = this.coQuanSelect
          ? this.coQuanSelect.id
          : null;
        this.form.linh_vuc_id = this.linhVucSelect
          ? this.linhVucSelect.id
          : null;
        if (validate) {
          this.disableButton = true;
          axios
            .put(env.endpointhttp + "admin/vanbanphapluat/edit", this.form)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Cập nhật thành công";
                this.getData();
              }
              else if(response.data.message=='datetime'){
                this.typeNotification = 1;
                this.textNotification = 'Ngày ban hành phải nhỏ hơn ngày hiệu lực'
              }
              else{
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
              this.disableButton = false;
            });
        }
      });
    },
    getData() {
      axios
        .get(env.endpointhttp + "admin/vanbanphapluat/list", {
          params: {
            page: this.page,
            search: this.search,
            nam_ban_hanh: this.filter.nam_ban_hanh,
            nam_co_hieu_luc: this.filter.nam_co_hieu_luc,
            nam_viet_nam_tham_gia: this.filter.nam_viet_nam_tham_gia,
            hieu_luc: this.filter.hieu_luc,
            co_quan_id: this.filter.co_quan_id,
            loai_id: this.filter.loai_id,
            linh_vuc_id: this.filter.linh_vuc_id,
          },
        })
        .then((res) => {
          this.dataTable = res.data.data;
          this.page = res.data.current_page;
          this.pageCount = res.data.last_page;
        });
    },
    getDanhMuc() {
      axios
        .get(env.endpointhttp + "admin/vanbanphapluat/getdanhmuc")
        .then((res) => {
          this.danhMucs = res.data.data;
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
    getChiSo() {
      axios.get(env.endpointhttp + "admin/vanbanphapluat/chiso").then((res) => {
        this.dataFilter = res.data;
        console.log(this.dataFilter);
      });
    },
    showXoaBoLoc() {
      if (
        this.filter.nam_ban_hanh == null &&
        this.filter.nam_co_hieu_luc == null &&
        this.filter.nam_viet_nam_tham_gia == null &&
        this.filter.hieu_luc == null &&
        this.filter.co_quan_id == null &&
        this.filter.loai_id == null &&
        this.filter.linh_vuc_id == null
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
        co_quan_id: null,
        loai_id: null,
        linh_vuc_id: null,
      };
      this.showXoaBoLoc();
      this.timKiem();
    },
    selectFilter(value, name) {
      if (this.filter[name] == value) {
        this.filter[name] = null;
      } else {
        this.filter[name] = value;
      }
      this.timKiem();
    },
  },
};
</script>

<style>

</style>