<template>
  <div>
    <components-notifications
      :typeInput="typeNotification"
      :textInput="textNotification"
    ></components-notifications>
    <div class="loading-roles" v-if="loading">
      <components-loading-page></components-loading-page>
    </div>
    <div class="d-flex">
      <div style="flex: 1; font-weight: bold; font-size: 18px">
        Danh mục loại hình quan trắc
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
    <div class="d-flex">
      <button
        type="button"
        class="btn btn-info btn-rounded"
        data-bs-toggle="modal"
        data-bs-target="#exampleModal"
        data-bs-placement="top"
        title="Thêm mới"
        @click="showFormAddLoaiHinh"
      >
        {{ $t("admin.buttons.add") }}
      </button>
    </div>
    <br />

    <div>
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
                  Tên loại hình quan trắc
                </th>
                <th style="display: table-cell; min-width: 70px">Mô tả</th>
                <th width="100" style="text-align: center">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loaiHinhs && loaiHinhs.length == 0">
                <td colspan="6" class="emptyInfomation">
                  <h5>Dữ liệu đang được cập nhật, bạn vui lòng truy cập lại sau</h5>
                </td>
              </tr>
              <tr
                v-for="(item, index) in loaiHinhs"
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
                    title="Chỉnh sửa"
                    @click="showFormEdit(item)"
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

    <modal
      v-if="showForm"
      @close="showForm = false"
      :width="600"
      :title="
        edit ? 'Cập nhật loại hình quan trắc' : 'Thêm Loại hình quan trắc'
      "
    >
      <div slot="body">
        <div>
          <label for="nhomchithi" class="form-label"
            >Tên Loại hình <span class="red-dot">*</span></label
          >
          <input
            type="text"
            class="form-control"
            id="nhomchithi"
            name="tenNhomChiThi"
            v-model="form.ten"
            v-validate="'required'"
          />
          <span
            v-show="errors.has('tenNhomChiThi')"
            class="help is-danger"
            style="color: red"
            >Tên loại hình quan trắc không thể bỏ trống</span
          >
        </div>
        <br />
        <div>
          <label for="motanhomchithi" class="form-label">Mô tả</label>
          <textarea
            type="text"
            class="form-control"
            v-model="form.mo_ta"
            id="motanhomchithi"
          />
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
  </div>
</template>

<script>
import { ValidationProvider } from "vee-validate";
import * as env from "../../../../env.js";

export default {
  components: { ValidationProvider },
  data: () => ({
    page: 1,
    pageCount: 0,
    loaiHinhs: [],
    disableButton: false,
    form: {
      ten: "",
      mo_ta: "",
    },
    confirmDelete: false,
    idDelete: null,
    delLink: null,
    contentDelete: "Bạn có chắc chắn muốn xóa dữ liệu này?",
    search: null,
    edit: false,
    showForm: false,
    typeNotification: null,
    textNotification: null,
    loading: false,
  }),
  mounted() {
    this.getLoaHinh();
  },
  methods: {
    showXoa(data) {
      this.contentDelete =
        "Bạn có chắc chắn muốn xóa loại hình quan trắc " + data.ten + " không?";
      this.idDelete = data.id;
      this.confirmDelete = true;
      this.delLink = "admin/quantrac/loaihinh/";
    },
    xoaDuLieu(){
        axios
        .delete(env.endpointhttp + this.delLink + this.idDelete)
        .then(() => {
          this.typeNotification = 2;
          this.textNotification = "Xóa thành công";
          this.getLoaHinh();
        })
        .catch(() => {
          this.typeNotification = 1;
          this.textNotification = "Vui lòng xóa các dữ liệu trực thuộc";
        })
        .finally(() => {
          this.confirmDelete = false;
        });
    },
    showFormAddLoaiHinh() {
      this.edit = false;
      this.showForm = true;
      this.form = {
        ten: "",
        mo_ta: "",
      };
    },
    showFormEdit(data) {
      this.edit = true;
      this.showForm = true;
      this.form = data;
    },
    add() {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          this.disableButton = true;
          axios
            .post(env.endpointhttp + "admin/quantrac/loaihinh", this.form)
            .then(() => {
              this.typeNotification = 2;
              this.textNotification = "Thêm mới thành công";
              this.getLoaHinh();
            })
            .catch(() => {
              this.typeNotification = 1;
              this.textNotification = "Thêm mới thất bại";
            })
            .finally(() => {
              this.loading = false;
              this.disableButton = false;
              this.showForm = false;
              this.typeNotification = null;
              this.textNotification = null;
            });
        }
      });
    },
    update() {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          axios
            .put(env.endpointhttp + "admin/quantrac/loaihinh", this.form)
            .then(() => {
              this.typeNotification = 2;
              this.textNotification = "Cập nhật thành công";
              this.getLoaHinh();
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
    getLoaHinh() {
      axios
        .get(env.endpointhttp + "admin/quantrac/loaihinh", {
          params: {
            page: this.page,
            search: this.search,
          },
        })
        .then((res) => {
          this.loaiHinhs = res.data.data;
          this.page = res.data.current_page;
          this.pageCount = res.data.last_page;
        });
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      this.getLoaHinh();
    },
    timKiem() {
      this.page = 1;
      this.getLoaHinh();
    },
  },
};
</script>

<style>
</style>