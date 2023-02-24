<template>
  <div class="white-box p-0">
    <components-notifications
      :typeInput="typeNotification"
      :textInput="textNotification"
    ></components-notifications>
    <div class="loading-roles" v-if="loading">
      <components-loading-page></components-loading-page>
    </div>

    <div class="left-aside" style="width: 400px; height: auto">
      <div class="d-flex">
        <div style="flex: 1; font-size: 18px; font-weight: bold">Chỉ thị</div>
      </div>
      <hr />
      <div style="font-size: 16px">
        <div>
          Tên chỉ thị: <b>{{ chithi.ten }}</b>
        </div>
        <!-- <br />
        <div>
          Nhóm: <b>{{ chithi.nhom_chi_thi.ten }}</b>
        </div> -->
        <br />
        <div><b>Mô tả:</b> {{ chithi.mo_ta }}</div>
        <br />
        <div>
          <b>{{ chithi.so_thong_so }}</b> Thông số
        </div>
      </div>
    </div>
    <div class="right-aside" style="margin-left: 400px">
      <div class="d-flex">
        <div style="flex: 1; font-size: 18px; font-weight: bold">
          Danh sách thông số
        </div>
        <button
          type="button"
          class="btn btn-info btn-rounded"
          data-bs-toggle="modal"
          data-bs-target="#exampleModal"
          data-bs-placement="top"
          title="Thêm thông số"
          @click="showFormAddThongSo"
        >
          <i class="fas fa-plus"></i> Thêm thông số
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
                  Tên thông số
                </th>
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
              <tr v-if="thongSos && thongSos.length == 0">
                <td colspan="6" class="emptyInfomation">
                  <h5>Dữ liệu đang được cập nhật, bạn vui lòng truy cập lại sau</h5>
                </td>
              </tr>
              <tr
                v-for="(item, index) in thongSos"
                :key="index"
                style="cursor: context-menu"
              >
                <td>
                  <!-- <span class="text-center-td">{{(page - 1)*10 + index + 1}}</span> -->
                  <div>{{ index + 1 }}</div>
                </td>
                <td>
                  <div style="white-space: initial">{{ item.ten }}</div>
                </td>
                <td>
                  <div style="white-space: initial">{{ item.mo_ta }}</div>
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
                    title="Chỉnh sửa"
                    @click="showFormEditThongSo(item)"
                    class="btn btn-info btn-outline btn-circle btn-small m-r-5"
                  >
                    <i class="ti-pencil-alt"></i>
                  </button>
                  <button
                    type="button"
                    title="Xóa"
                    @click="showXoa(item)"
                    class="btn btn-info btn-outline btn-circle btn-small m-r-5"
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
      v-if="showChiThi"
      @close="showChiThi = false"
      :width="600"
      :title="editChiThi ? 'Cập nhật thông số' : 'Thêm thông số'"
    >
      <div slot="body">
        <div>
          <label for="chithi" class="form-label"
            >Chỉ thị <span class="red-dot">*</span></label
          >
          <multiselect
            v-model="chiThiSelect"
            :options="chiThis"
            :searchable="true"
            track-by="id"
            name="selectNhomChiThi"
            label="ten"
            placeholder=" Chọn một chỉ thị  "
            v-validate="'required'"
            :show-labels="false"
          >
          </multiselect>
          <span
            v-show="errors.has('selectNhomChiThi')"
            class="help is-danger"
            style="color: red"
            >Chỉ thị không thể bỏ trống</span
          >
        </div>
        <br />
        <div>
          <label for="chithi" class="form-label"
            >Tên thông số <span class="red-dot">*</span></label
          >
          <input
            type="text"
            class="form-control"
            id="chithi"
            name="tenChiThi"
            v-validate="'required'"
            v-model="formThongSo.ten"
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
            v-model="formThongSo.mo_ta"
            id="motachithi"
          />
        </div>
      </div>
      <div slot="footer">
        <button
          type="button"
          class="btn btn-success"
          @click="addThongSo"
          v-if="!editChiThi"
          :disabled="disableButton"
        >
          {{ $t("admin.buttons.add") }}
        </button>
        <button
          type="button"
          class="btn btn-success"
          @click="updateThongSo"
          :disabled="disableButton"
          v-else
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
</template>

<script>
import Multiselect from "vue-multiselect";
import * as env from "../../../../env.js";
import * as routes from "../../../../routes.js";
import { ValidationProvider } from "vee-validate";

export default {
  components: { Multiselect, ValidationProvider },
  props: ["chithi"],
  data: () => ({
    showChiThi: false,
    editChiThi: false,
    search: null,
    disableButton: false,
    page: 1,
    pageCount: 0,
    typeNotification: null,
    textNotification: null,
    loading: false,
    chiThiSelect: null,
    confirmDelete: false,
    idDelete: null,
    delLink: null,
    contentDelete: "Bạn có chắc chắn muốn xóa dữ liệu này?",
    formThongSo: {
      chi_thi_id: null,
      ten: "",
      mo_ta: "",
    },
    chiThis: [],
    thongSos: [],
  }),
  mounted() {
    this.getChiThi();
    this.getThongSo();
  },
  methods: {
    showXoa(data) {
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa thông số " + data.ten + " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/thongso/delete/";
    },
    xoaDuLieu() {
      axios
        .delete(env.endpointhttp + this.delLink + this.idDelete)
        .then(() => {
          this.typeNotification = 2;
          this.textNotification = "Xóa thành công";
          this.getThongSo();
        })
        .catch(() => {
          this.typeNotification = 1;
          this.textNotification = "Vui lòng xóa các dữ liệu trực thuộc";
        })
        .finally(() => {
          this.confirmDelete = false;
        });
    },
    showFormAddThongSo() {
      this.showChiThi = true;
      this.editChiThi = false;
      this.formThongSo = {
        chi_thi_id: null,
        ten: "",
        mo_ta: "",
      };
      this.chiThiSelect = { id: this.chithi.id, ten: this.chithi.ten };
    },
    showFormEditThongSo(data) {
      this.showChiThi = true;
      this.editChiThi = true;
      this.formThongSo = data;
      this.chiThiSelect = { id: this.chithi.id, ten: this.chithi.ten };
    },
    getChiThi() {
      axios
        .get(env.endpointhttp + "admin/chithi/getchithi", {
          params: {
            perPage: 999999,
          },
        })
        .then((res) => {
          this.chiThis = res.data.data;
        });
    },
    getThongSo() {
      axios
        .get(env.endpointhttp + "admin/thongso", {
          params: {
            page: this.page,
            search: this.search,
            chi_thi_id: this.chithi.id,
          },
        })
        .then((res) => {
          this.thongSos = res.data.data;
          this.page = res.data.current_page;
          this.pageCount = res.data.last_page;
        });
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.getChiThi();
    },
    addThongSo() {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          this.formThongSo.chi_thi_id = this.chiThiSelect
            ? this.chiThiSelect.id
            : null;
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/thongso", this.formThongSo)
            .then(() => {
              this.typeNotification = 2;
              this.textNotification = "Thêm mới thành công";
              this.getThongSo();
            })
            .catch(() => {
              this.typeNotification = 1;
              this.textNotification = "Thêm mới thất bại";
            })
            .finally(() => {
              this.loading = false;
              this.disableButton = false;
              this.showChiThi = false;
              this.typeNotification = null;
              this.textNotification = null;
            });
        }
      });
    },
    updateThongSo() {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          this.formThongSo.chi_thi_id = this.chiThiSelect.id;
          axios
            .put(env.endpointhttp + "admin/thongso", this.formThongSo)
            .then(() => {
              this.typeNotification = 2;
              this.textNotification = "Cập nhật thành công";
              this.getThongSo();
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
            });
        }
      });
    },
  },
};
</script>

<style scoped>
.title-nhomchithi {
  font-size: 16px;
  font-weight: bold;
}
</style>