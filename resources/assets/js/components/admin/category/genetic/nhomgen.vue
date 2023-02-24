<template>
  <div class="white-box p-0">
    <components-notifications :typeInput="typeNotification" :textInput="textNotification"></components-notifications>
    <div class="loading-roles" v-if="loading">
      <components-loading-page></components-loading-page>
    </div>
    <div class="left-aside" style="width: 450px; height: auto">
      <div class="d-flex">
        <div style="flex: 1; font-size: 18px; font-weight: bold">
          Nhóm nguồn gen
        </div>
        <button type="button" class="btn btn-info btn-rounded" data-bs-toggle="modal" data-bs-target="#exampleModal"
          data-bs-placement="top" title="Thêm nhóm chỉ thị" @click="showFormAddNhomGen">
          <i class="fas fa-plus"></i> Thêm
        </button>
      </div>
      <hr />
      <div style="height: 550px; overflow-y: scroll;">
        <div v-for="(item, index) in nhomGens" :key="index" class="d-flex">
          <div class="d-flex"
            style="width: 100%; height: 60px; align-items: center; padding-left: 10px; padding-right: 10px"
            :style="{ background: nhom_gen_id == item.id ? '#D5F5E3' : '' }">
            <div class="c-flex" style="flex: 1; cursor: pointer" @click="filterNhomGen(item)">
              <div class="title-nhomchithi" style="max-width:320px; overflow:clip">
                {{ index + 1 }}. {{ item.ten }}
              </div>
              <div style="padding-left: 18px">{{ item.phan_loai.name }}</div>
            </div>

            <div class="d-flex" style="padding-left: 10px; cursor: pointer">
              <div @click="showEditNhomGen(item)" style="padding-right: 10px">
                <i class="fas fa-edit"></i>
              </div>
              <div @click="showXoaNhomGen(item)">
                <i class="fas fa-trash-alt"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="right-aside" style="margin-left: 450px">
      <div class="d-flex">
        <div style="flex: 1; font-size: 18px; font-weight: bold">
          Danh sách loại nguồn gen
        </div>
        <div class="d-flex">
          <input class="form-control" placeholder="Tìm kiếm" v-model="search" @keyup.enter="timKiem" />
        </div>
      </div>
      <div>
        <button type="button" class="btn btn-info btn-rounded" data-bs-toggle="modal" data-bs-target="#exampleModal"
          data-bs-placement="top" title="Thêm loại gen" @click="showFormAddLoaiGen">
          <i class="fas fa-plus"></i> Thêm loại gen
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
                  Tên loại gen
                </th>
                <th style="display: table-cell">Thuộc nhóm gen</th>
                <th style="display: table-cell; min-width: 70px">Mô tả</th>
                <th width="100" style="text-align: center">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <!-- <tr v-if="!dataset">
                    <td colspan="8">
                      <components-loading></components-loading>
                    </td>
                  </tr> -->
              <tr v-if="loaiGens && loaiGens.length == 0">
                <td colspan="6" class="emptyInfomation">
                  <h5>Dữ liệu đang được cập nhật, bạn vui lòng truy cập lại sau</h5>
                </td>
              </tr>
              <tr v-for="(item, index) in loaiGens" :key="index" style="cursor: context-menu">
                <td>
                  <!-- <span class="text-center-td">{{(page - 1)*10 + index + 1}}</span> -->
                  <div>{{ index + 1 }}</div>
                </td>
                <td>
                  <div style="white-space: initial; max-width: 400px; overflow:clip">
                    <div style="font-weight: 400; font-size: 15px">
                      {{ item.ten }}
                    </div>
                  </div>
                </td>
                <td>{{ item.nhom_gen.ten }}</td>
                <td>
                  <div style="white-space: initial">{{ item.mo_ta }}</div>
                </td>
                <td>
                  <button type="button" title="Chỉnh sửa" @click="showFormEditLoaiGen(item)"
                    class="btn btn-info btn-outline btn-circle btn-small m-r-5">
                    <i class="ti-pencil-alt"></i>
                  </button>
                  <button type="button" title="Xóa" class="btn btn-info btn-outline btn-circle btn-small m-r-5"
                    @click="showXoaLoaiGen(item)">
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

    <modal v-if="showFormNhomGen" @close="showFormNhomGen = false" :width="700"
      :title="editNhomGen ? 'Cập nhật nhóm nguồn gen' : 'Thêm nhóm nguồn gen'">
      <div slot="body">
        <div class="d-flex" style="justify-content: space-between">
          <div style="width: 48%">
            <label for="nhomchithi" class="form-label">Phân loại<span class="red-dot">*</span></label>
            <multiselect v-model="phanLoaiNhomGenSelect" :options="phanLoai" :searchable="true" track-by="id"
              name="selectNhom" label="name" placeholder=" Chọn một phân loại" v-validate="'required'"
              :show-labels="false">
            </multiselect>
            <span v-show="errors.has('selectNhom')" class="help is-danger" style="color: red">Phân loại nhóm gen không
              thể bỏ trống</span>
          </div>
          <div style="width: 48%">
            <label for="tenNhomGen" class="form-label">Tên nhóm<span class="red-dot">*</span></label>
            <input type="text" class="form-control" id="tenNhomGen" name="tenNhomGen" v-model="formNhomGen.ten"
              v-validate="'required'" />
            <span v-show="errors.has('tenNhomGen')" class="help is-danger" style="color: red">Tên nhóm gen không thể bỏ
              trống</span>
          </div>
        </div>
        <br />
        <div>
          <label for="motanhomchithi" class="form-label">Mô tả</label>
          <textarea type="text" class="form-control" v-model="formNhomGen.mo_ta" id="motanhomchithi" />
        </div>
        <br />
        <div>
          <label for="motanhomchithi" class="form-label">Ghi chú</label>
          <textarea type="text" class="form-control" v-model="formNhomGen.ghi_chu" id="motanhomchithi" />
        </div>
      </div>
      <div slot="footer">
        <button type="button" class="btn btn-success" v-if="!editNhomGen" @click="addNhomGen">
          {{ $t("admin.buttons.add") }}
        </button>
        <button type="button" class="btn btn-success" v-else @click="updateNhomGen">
          {{ $t("admin.buttons.edit") }}
        </button>
      </div>
    </modal>
    <modal v-if="confirmDelete" @close="confirmDelete = false" :title="deleteTitle">
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
    <modal v-if="showLoaiGen" @close="showLoaiGen = false" :width="600"
      :title="editLoaiGen ? 'Cập nhật loại gen' : 'Thêm loại gen'">
      <div slot="body">
        <div>
          <label for="chithi" class="form-label">Nhóm nguồn gen<span class="red-dot">*</span></label>
          <multiselect v-model="nhomGenSelect" :options="nhomGens" :searchable="true" track-by="id" name="selectNhomGen"
            label="ten" placeholder=" Chọn một nhóm nguồn gen" v-validate="'required'" :show-labels="false">
          </multiselect>
          <span v-show="errors.has('selectNhomGen')" class="help is-danger" style="color: red">Nhóm gen không thể bỏ
            trống</span>
        </div>
        <br />
        <div>
          <label for="chithi" class="form-label">Tên loại gen <span class="red-dot">*</span></label>
          <input type="text" class="form-control" id="chithi" name="tenChiThi" v-validate="'required'"
            v-model="formLoaiGen.ten" />
          <span v-show="errors.has('tenChiThi')" class="help is-danger" style="color: red">Tên chỉ thị không thể bỏ
            trống</span>
        </div>
        <br />
        <div>
          <label for="motachithi" class="form-label">Mô tả</label>
          <textarea type="text" class="form-control" v-model="formLoaiGen.mo_ta" id="motachithi" />
        </div>
        <br />
        <div>
          <label class="form-label">Ghi chú</label>
          <textarea type="text" class="form-control" v-model="formLoaiGen.ghi_chu" />
        </div>
      </div>
      <div slot="footer">
        <button type="button" class="btn btn-success" @click="addLoaiGen" v-if="!editLoaiGen">
          {{ $t("admin.buttons.add") }}
        </button>
        <button type="button" class="btn btn-success" @click="updateLoaiGen" v-else>
          {{ $t("admin.buttons.edit") }}
        </button>
      </div>
    </modal>
  </div>
</template>

<script>
import Multiselect from "../../../vue-multiselect";
import * as env from "../../../../env.js";
import * as routes from "../../../../routes.js";
import { ValidationProvider } from "vee-validate";

export default {
  props: ["phanLoai"],
  components: { Multiselect, ValidationProvider },
  data: () => ({
    showFormNhomGen: false,
    contentDelete: "",
    confirmDelete: false,
    showLoaiGen: false,
    editLoaiGen: false,
    editNhomGen: false,
    resetData: false,
    deleteTitle: "Xóa dữ liệu",
    idDelete: null,
    search: null,
    nhom_gen_id: null,
    page: 1,
    pageCount: 0,
    nhomGens: [],
    typeNotification: null,
    textNotification: null,
    loading: false,
    nhomGenSelect: null,
    phanLoaiNhomGenSelect: null,
    formNhomGen: {
      ten: "",
      phan_loai_id: null,
      mo_ta: "",
      ghi_chu: null,
    },
    delLink: "",
    formLoaiGen: {
      nhom_gen_id: null,
      ten: "",
      mo_ta: "",
      ghi_chu: null,
    },
    loaiGens: [],
  }),
  mounted() {
    this.getNhomGen();
    this.getLoaiGen();
  },
  methods: {
    filterNhomGen(data) {
      if (this.nhom_gen_id == data.id) {
        this.nhom_gen_id = null
      } else
        this.nhom_gen_id = data.id
      this.timKiem()
    },
    showEditNhomGen(data) {
      this.showFormNhomGen = true;
      this.editNhomGen = true;
      Object.assign(this.formNhomGen, data);
      this.phanLoaiNhomGenSelect = data.phan_loai;
    },
    showFormAddNhomGen() {
      this.showFormNhomGen = true;
      this.editNhomGen = false;
      this.formNhomGen = {
        nhom_gen_id: null,
        ten: "",
        mo_ta: "",
        ghi_chu: null,
      };
    },

    showFormAddLoaiGen() {
      this.showLoaiGen = true;
      this.editLoaiGen = false;
      this.formLoaiGen = {
        nhom_chi_thi_id: null,
        ten: "",
        mo_ta: "",
      };
      this.nhomGenSelect = null;
    },
    showFormEditLoaiGen(data) {
      this.showLoaiGen = true;
      this.editLoaiGen = true;
      this.formLoaiGen = data;
      this.nhomGenSelect = data.nhom_gen;
    },
    showXoaNhomGen(data) {
      this.confirmDelete = true;
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa nhóm gen " + data.ten + " không?";
      this.idDelete = data.id;
      this.delLink = "admin/genetic/xoanhom/";
    },
    showXoaLoaiGen(data) {
      this.confirmDelete = true;
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa loại gen " + data.ten + " không?";
      this.idDelete = data.id;
      this.delLink = "admin/genetic/xoaloai/";
    },
    xoaDuLieu() {
      axios
        .delete(env.endpointhttp + this.delLink + this.idDelete)
        .then(() => {
          this.typeNotification = 2;
          this.textNotification = "Xóa thành công";
          this.getNhomGen();
          this.getLoaiGen();
        })
        .catch(() => {
          this.typeNotification = 1;
          this.textNotification = "Vui lòng xóa các dữ liệu trực thuộc";
        })
        .finally(() => {
          this.confirmDelete = false;
          // this.typeNotification = null;
          // this.textNotification = null;
        });
    },
    getLoaiGen() {
      axios
        .get(env.endpointhttp + "admin/genetic/loaigen", {
          params: {
            page: this.page,
            search: this.search,
            nhom_gen_id: this.nhom_gen_id
          },
        })
        .then((res) => {
          this.loaiGens = res.data.data;
          this.page = res.data.current_page;
          this.pageCount = res.data.last_page;
        });
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.getLoaiGen();
    },
    addLoaiGen() {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          this.formLoaiGen.nhom_gen_id = this.nhomGenSelect
            ? this.nhomGenSelect.id
            : null;
          this.formLoaiGen.ten=this.formLoaiGen.ten.replace(/\s+/g, ' ').trim()
          axios
            .post(env.endpointhttp + "admin/genetic/addloai", this.formLoaiGen)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Thêm mới thành công";
                this.getLoaiGen();
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
              this.showLoaiGen = false;
              this.typeNotification = null;
              this.textNotification = null;
            });
        }
      });
    },
    updateLoaiGen() {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          this.formLoaiGen.nhom_gen_id = this.nhomGenSelect
            ? this.nhomGenSelect.id
            : null;
          this.formLoaiGen.ten=this.formLoaiGen.ten.replace(/\s+/g, ' ').trim()
          axios
            .post(env.endpointhttp + "admin/genetic/editloai", this.formLoaiGen)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Cập nhật thành công";
                this.getLoaiGen();
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
              this.showLoaiGen = false;
              this.typeNotification = null;
              this.textNotification = null;
            });
        }
      });
    },
    getNhomGen() {
      axios.get(env.endpointhttp + "admin/genetic/nhomgen").then((res) => {
        this.nhomGens = res.data;
      });
    },
    addNhomGen() {
      this.$validator.validateAll().then((validate) => {
        this.formNhomGen.phan_loai_id = this.phanLoaiNhomGenSelect
          ? this.phanLoaiNhomGenSelect.id
          : null;
        if (validate) {
          axios
            .post(env.endpointhttp + "admin/genetic/addnhom", this.formNhomGen)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Thêm mới thành công";
                this.getNhomGen();
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
              this.showFormNhomGen = false;
              this.typeNotification = null;
              this.textNotification = null;
            });
        }
      });
    },
    updateNhomGen() {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          axios
            .post(env.endpointhttp + "admin/genetic/editnhom", this.formNhomGen)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Cập nhật thành công";
                this.getNhomGen();
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
              this.showFormNhomGen = false;
              this.loading = false;
              this.typeNotification = null;
              this.textNotification = null;
            });
        }
      });
    },
    timKiem() {
      this.page = 1;
      this.getLoaiGen();
    },
    viewThongSo(id) {
      window.location.href = "/admin/thongso/" + id;
    },
  },
};
</script>

<style scoped>
.title-nhomchithi {
  font-size: 16px;
  font-weight: 500;
}
</style>