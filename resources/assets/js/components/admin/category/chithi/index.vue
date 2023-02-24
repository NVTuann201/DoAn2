<template>
  <div class="white-box p-0">
    <components-notifications
      :typeInput="typeNotification"
      :textInput="textNotification"
    ></components-notifications>
    <div class="loading-roles" v-if="loading">
      <components-loading-page></components-loading-page>
    </div>
    <!-- <div class="left-aside" style="width: 350px; height: auto">
      <div class="d-flex">
        <div style="flex: 1; font-size: 18px; font-weight: bold">
          Nhóm chỉ thị
        </div>
        <button
          type="button"
          class="btn btn-info btn-rounded"
          data-bs-toggle="modal"
          data-bs-target="#exampleModal"
          data-bs-placement="top"
          title="Thêm nhóm chỉ thị"
          @click="showFormAddNhomCT"
        >
          <i class="fas fa-plus"></i> Thêm
        </button>
      </div>
      <hr />
      <div>
        <div
          v-for="(item, index) in nhomChiThis"
          :key="index"
          class="d-flex"
          style="padding-bottom: 15px"
        >
          <div class="d-flex" style="width: 100%">
            <div
              class="c-flex"
              style="flex: 1; cursor: pointer"
              @click="showFormEditNhomCT(item)"
            >
              <div class="title-nhomchithi">
                {{ index + 1 }}. {{ item.ten }}
                <span style="font-weight: 300; font-size: 14px"
                  >({{ item.so_chi_thi }} chỉ thị)</span
                >
              </div>
            </div>

            <div class="d-flex" style="padding-left: 10px; cursor: pointer">
              <div @click="showXoaNhomChiThi(item)">
                <i class="fas fa-trash-alt"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> -->
    <div style="padding: 50px">
      <div class="d-flex">
        <div style="flex: 1; font-size: 18px; font-weight: bold">
          Danh sách chỉ thị
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
          @click="showFormAddChiThi"
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
                  Tên chỉ thị
                </th>
                <!-- <th style="display: table-cell">Thuộc nhóm chỉ thị</th> -->
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
              <tr v-if="chiThis && chiThis.length == 0">
                <td colspan="6" class="emptyInfomation">
                  <h5>Dữ liệu đang được cập nhật, bạn vui lòng truy cập lại sau</h5>
                </td>
              </tr>
              <tr
                v-for="(item, index) in chiThis"
                :key="index"
                style="cursor: context-menu"
              >
                <td>
                  <!-- <span class="text-center-td">{{(page - 1)*10 + index + 1}}</span> -->
                  <div>{{ index + 1 }}</div>
                </td>
                <td>
                  <div style="white-space: initial">
                    <div style="font-weight: 400; font-size: 15px">
                      {{ item.ten }}
                    </div>
                  </div>
                </td>
                <!-- <td>{{ item.nhom_chi_thi.ten }}</td> -->
                <td>
                  <div style="white-space: initial">{{ item.mo_ta }}</div>
                </td>
                <td>
                  <!-- <button
                    type="button"
                    class="btn btn-info btn-outline btn-circle btn-small m-r-5"
                    title="Danh sách thông số"
                    @click="viewThongSo(item.id)"
                  >
                    <i class="fas fa-check-square"></i>
                  </button> -->
                  <button
                    type="button"
                    title="Chỉnh sửa"
                    @click="showFormEditChiThi(item)"
                    class="btn btn-info btn-outline btn-circle btn-small m-r-5"
                  >
                    <i class="ti-pencil-alt"></i>
                  </button>
                  <button
                    type="button"
                    title="Xóa"
                    class="btn btn-info btn-outline btn-circle btn-small m-r-5"
                    @click="showXoaChiThi(item)"
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
      v-if="showNhomChiThi"
      @close="showNhomChiThi = false"
      :width="600"
      :title="editNhomChiThi ? 'Cập nhật nhóm chỉ thị' : 'Thêm nhóm chỉ thị'"
    >
      <div slot="body">
        <div>
          <label for="nhomchithi" class="form-label"
            >Tên nhóm chỉ thị <span class="red-dot">*</span></label
          >
          <input
            type="text"
            class="form-control"
            id="nhomchithi"
            name="tenNhomChiThi"
            v-model="formNhomChiThi.ten"
            v-validate="'required'"
          />
          <span
            v-show="errors.has('tenNhomChiThi')"
            class="help is-danger"
            style="color: red"
            >Tên nhóm chỉ thị không thể bỏ trống</span
          >
        </div>
        <br />
        <div>
          <label for="motanhomchithi" class="form-label">Mô tả</label>
          <textarea
            type="text"
            class="form-control"
            v-model="formNhomChiThi.mo_ta"
            id="motanhomchithi"
          ></textarea>
        </div>
      </div>
      <div slot="footer">
        <button
          type="button"
          class="btn btn-success"
          v-if="!editNhomChiThi"
          @click="addNhomChiThi"
        >
          <i class="fas fa-plus"></i>
          Thêm mới
        </button>
        <button
          type="button"
          class="btn btn-success"
          v-else
          @click="updateNhomChiThi"
        >
          <i class="fas fa-check"></i>
          Cập nhật
        </button>
      </div>
    </modal>
    <modal
      v-if="confirmDelete"
      @close="confirmDelete = false"
      :title="deleteTitle"
    >
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
    <modal
      v-if="showChiThi"
      @close="showChiThi = false"
      :width="600"
      :title="editChiThi ? 'Cập nhật chỉ thị' : 'Thêm chỉ thị'"
    >
      <div slot="body">
        <!-- <div>
          <label for="chithi" class="form-label"
            >Nhóm chỉ thị <span class="red-dot">*</span></label
          >
          <multiselect
            v-model="chiThiSelect"
            :options="nhomChiThis"
            :searchable="true"
            track-by="id"
            name="selectNhomChiThi"
            label="ten"
            placeholder=" Chọn một nhóm chỉ thị  "
            v-validate="'required'"
            :show-labels="false"
          >
          </multiselect>
          <span
            v-show="errors.has('selectNhomChiThi')"
            class="help is-danger"
            style="color: red"
            >Nhóm chỉ thị không thể bỏ trống</span
          >
        </div>
        <br /> -->
        <div>
          <label for="chithi" class="form-label"
            >Tên chỉ thị <span class="red-dot">*</span></label
          >
          <input
            type="text"
            class="form-control"
            id="chithi"
            name="tenChiThi"
            v-validate="'required|max:255'"
            v-model="formChiThi.ten"
          />
          <span
            v-show="errors.has('tenChiThi')"
            class="help is-danger"
            style="color: red"
            >Tên chỉ thị không thể bỏ trống</span
          >
        </div>
        <br />
        <div>
          <label for="motachithi" class="form-label">Mô tả</label>
          <textarea
            type="text"
            class="form-control"
            v-model="formChiThi.mo_ta"
            id="motachithi"
          ></textarea>
        </div>
      </div>
      <div slot="footer">
        <button
          type="button"
          class="btn btn-success"
          @click="addChiThi"
          v-if="!editChiThi"
        >
          {{ $t("admin.buttons.add") }}
        </button>
        <button
          type="button"
          class="btn btn-success"
          @click="updateChiThi"
          v-else
        >
          {{ $t("admin.buttons.edit") }}
        </button>
      </div>
    </modal>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import * as env from "../../../../env.js";
import * as routes from "../../../../routes.js";
import { ValidationProvider } from "vee-validate";

export default {
  components: { Multiselect, ValidationProvider },
  data: () => ({
    showNhomChiThi: false,
    contentDelete: "",
    confirmDelete: false,
    showChiThi: false,
    editChiThi: false,
    editNhomChiThi: false,
    resetData: false,
    deleteTitle: "Xóa dữ liệu",
    idDelete: null,
    search: null,
    page: 1,
    pageCount: 0,
    nhomChiThis: [],
    typeNotification: null,
    textNotification: null,
    loading: false,
    chiThiSelect: null,
    formNhomChiThi: {
      ten: "",
      mo_ta: "",
    },
    delLink: "",
    formChiThi: {
      nhom_chi_thi_id: null,
      ten: "",
      mo_ta: "",
    },
    chiThis: [],
  }),
  mounted() {
    // this.getNhomChiThi();
    this.getChiThi();
  },
  methods: {
    showFormEditNhomCT(data) {
      this.showNhomChiThi = true;
      this.editNhomChiThi = true;
      this.formNhomChiThi = data;
    },
    showFormAddNhomCT() {
      this.showNhomChiThi = true;
      this.editNhomChiThi = false;
      this.formNhomChiThi = { ten: "", mo_ta: "" };
    },
    showFormAddChiThi() {
      this.showChiThi = true;
      this.editChiThi = false;
      this.formChiThi = {
        nhom_chi_thi_id: null,
        ten: "",
        mo_ta: "",
      };
      this.chiThiSelect = null;
    },
    showFormEditChiThi(data) {
      this.showChiThi = true;
      this.editChiThi = true;
      this.formChiThi = data;
      this.chiThiSelect = data.nhom_chi_thi;
    },
    showXoaNhomChiThi(data) {
      this.confirmDelete = true;
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa nhóm chỉ thị " + data.ten + " không?";
      this.idDelete = data.id;
      this.delLink = "admin/chithi/xoanhom/";
    },
    showXoaChiThi(data) {
      this.confirmDelete = true;
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa chỉ thị " + data.ten + " không?";
      this.idDelete = data.id;
      this.delLink = "admin/chithi/xoa/";
    },
    xoaDuLieu() {
      axios
        .delete(env.endpointhttp + this.delLink + this.idDelete)
        .then(() => {
          this.typeNotification = 2;
          this.textNotification = "Xóa thành công";
          this.getNhomChiThi();
          this.getChiThi();
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
    getChiThi() {
      axios
        .get(env.endpointhttp + "admin/chithi/getchithi", {
          params: {
            page: this.page,
            search: this.search,
          },
        })
        .then((res) => {
          this.chiThis = res.data.data;
          this.page = res.data.current_page;
          this.pageCount = res.data.last_page;
        });
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.getChiThi();
    },
    addChiThi() {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          axios
            .post(env.endpointhttp + "admin/chithi/add", this.formChiThi)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Thêm mới thành công";
              }
              else {
                this.action = true;
                this.typeNotification = 1;
                this.textNotification = response.data.message;
              }
            })
            .catch(() => {
              this.typeNotification = 1;
              this.textNotification = "Thêm mới thất bại";
            })
            .finally(() => {
              this.loading = false;
              this.showChiThi = false;
              this.typeNotification = null;
              this.textNotification = null;
              this.getChiThi();
            });
        }
      });
    },
    updateChiThi() {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          axios
            .post(env.endpointhttp + "admin/chithi/edit", this.formChiThi)
            .then((response) => {
              if (response.data.message == 'Done') {
                this.typeNotification = 2;
                this.textNotification = "Cập nhật thành công";
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
              this.showChiThi = false;
              this.typeNotification = null;
              this.textNotification = null;
              this.getChiThi();
            });
        }
      });
    },
    getNhomChiThi() {
      axios.get(env.endpointhttp + "admin/chithi/getnhom").then((res) => {
        this.nhomChiThis = res.data;
      });
    },
    addNhomChiThi() {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          axios
            .post(
              env.endpointhttp + "admin/chithi/addnhom",
              this.formNhomChiThi
            )
            .then(() => {
              this.typeNotification = 2;
              this.textNotification = "Thêm mới thành công";
              this.getNhomChiThi();
            })
            .catch(() => {
              this.typeNotification = 1;
              this.textNotification = "Thêm mới thất bại";
            })
            .finally(() => {
              this.loading = false;
              this.showNhomChiThi = false;
              this.typeNotification = null;
              this.textNotification = null;
            });
        }
      });
    },
    updateNhomChiThi() {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          axios
            .post(
              env.endpointhttp + "admin/chithi/editnhom",
              this.formNhomChiThi
            )
            .then(() => {
              this.typeNotification = 2;
              this.textNotification = "Cập nhật thành công";
              this.getNhomChiThi();
            })
            .catch(() => {
              this.typeNotification = 1;
              this.textNotification = "Cập nhật thất bại";
            })
            .finally(() => {
              this.showNhomChiThi = false;
              this.loading = false;
              this.typeNotification = null;
              this.textNotification = null;
            });
        }
      });
    },
    timKiem() {
      this.page = 1;
      this.getChiThi();
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