<template>
  <div class="white-box clearfix">
    <components-notifications
      :typeInput="typeNotification"
      :textInput="textNotification"
    >
    </components-notifications>
    <div class="loading-roles" v-if="loading">
      <components-loading-page></components-loading-page>
    </div>
    <div class="row">
      <div class="col-sm-12 col-md-6">
        <div class="form-material">
          <div class="form-group col-md-12">
            <label class="form-label">
              {{ $t("admin.user.name") }}<span class="red-dot">*</span>
            </label>
            <input
              type="text"
              v-model="name"
              name="name"
              class="form-control"
              v-validate="'required|max:255'"
              :placeholder="$t('admin.user.input_name')"
            />
            <p class="help-block is-danger" v-show="errors.has('name')">
              {{ $t("admin.error.name") }}
            </p>
          </div>
          <div class="form-group col-md-12">
            <label class="form-label">
              {{ $t("admin.user.email") }}<span class="red-dot">*</span>
            </label>
            <input
              type="text"
              v-model="email"
              name="email"
              class="form-control form-control-line"
              v-validate="'required|email'"
              :placeholder="$t('admin.user.input_email')"
            />
            <p class="help-block is-danger" v-show="errors.has('email')">
              {{ $t("admin.error.email") }}
            </p>
          </div>
          <div class="form-group col-md-12">
            <label class="form-label">
              {{ $t("admin.user.birthday") }}
            </label>
            <date-picker v-model="birthday" :config="optionsTime"></date-picker>
          </div>
          <div class="form-group col-md-12">
            <label class="form-label">
              {{ $t("admin.label.phone") }}
            </label>
            <input
              type="tel"
              pattern="[0-9]{10}"
              name="phone"
              v-model="phone"
              class="form-control form-control-line"
              :placeholder="$t('admin.user.input_phone')"
              v-validate="'max:10'"
            />
            <p class="help-block is-danger" v-show="errors.has('phone')">
              {{ $t("admin.error.phone") }}
            </p>
          </div>
          <div class="form-group col-md-12">
            <label class="form-label">
              {{ $t("admin.user.password") }}<span class="red-dot">*</span>
            </label>
            <input
              type="password"
              v-model="password"
              class="form-control form-control-line"
              v-validate="'required'"
              name="password"
            />
            <p class="help-block is-danger" v-show="errors.has('password')">
              {{ $t("admin.error.password") }}
            </p>
          </div>
          <div class="form-group col-md-12">
            <label class="form-label">
              {{ $t("admin.user.check_password")
              }}<span class="red-dot">*</span>
            </label>
            <input
              type="password"
              name="checkPassword"
              v-model="checkPassword"
              class="form-control form-control-line"
              v-validate="'required'"
            />
            <p class="help-block is-danger" v-show="errors.has('checkPassword')">
              {{ $t("admin.error.checkPassword") }}
            </p>
          </div>
        </div>
      </div>
      <RoleDetail v-model="role" :danhmucs="danhmucs" :submit="submit"/>
      <div class="col-sm-12">
        <div class="text-right">
          <button
            class="btn btn-inverse m-r-20"
            type="button"
            @click="setCancel()"
          >
            {{ $t("admin.label.cancel") }}
          </button>
          <button class="btn btn-success" @click="addUser()" type="button">
            {{ $t("admin.label.create_new") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import { ValidationProvider } from "vee-validate";
import { user_url_add } from "@/routes.js";
import RoleDetail from "./role-detail.vue";
export default {
  props: { danhmucs: { type: Object, default: () => ({}) } },
  components: {
    Multiselect,
    ValidationProvider,
    RoleDetail,
  },
  data: function () {
    return {
      types: [],
      provinces: [],
      email: null,
      phone: null,
      name: null,
      system: null,
      description: null,
      loading: false,
      action: true,
      password: null,
      checkPassword: null,
      typeNotification: null,
      textNotification: null,
      role: {
        role: null,
        coso_type: "",
        coso: null,
        provinces: [],
      },
      options: ["list", "of", "options"],
      birthday: null,
      optionsTime: {
        format: "DD/MM/YYYY",
        useCurrent: false,
      },
      types: [
        { id: 1, name: "Khu b???o t???n" },
        { id: 2, name: "C?? s??? b???o t???n" },
        { id: 3, name: "H??nh lang ??a d???ng sinh h???c" },
        { id: 4, name: "??a d???ng sinh h???c cao" },
        { id: 5, name: "V??ng ng???p n????c quan tr???ng" },
        { id: 6, name: "V??ng chim quan tr???ng" },
        { id: 7, name: "C???nh quan sinh th??i" },
        { id: 8, name: "Khu d??? tr??? sinh quy???n" },
      ],
      submit: false
    };
  },
  mounted() {},
  methods: {
    addUser() {
      this.submit = true;
      this.$validator.validateAll();
      // console.log(this.role.role, this.action)
      if (this.$validator.errors.items.length) return;  
      if (this.action == true && this.role.role) {
        if (this.password == this.checkPassword) {
          this.loading = true;
          this.action = false;
          let data = {
            email: this.email,
            password: this.password,
            birthday: this.birthday,
            role_id: this.role.role.id,
            name: this.name,
            phone: this.phone,
            province_ids: this.role.provinces
              ? this.role.provinces.map((x) => x.id)
              : "",
            coso_type: this.role.coso_type,
            coso_id: this.role.coso ? this.role.coso.id : "",
          };
          axios
            .post(user_url_add, data)
            .then((response) => {
              if (response.data.message == "message.success") {
                this.typeNotification = 2;
                this.textNotification = "Th??m m???i th??nh c??ng.";
                window.location.href = "/users";
                this.action = true;
              } else {
                this.action = true;
                this.typeNotification = 1;
                this.textNotification = response.data.error[Object.keys(response.data.error)[0]];
              }
            })  
            .catch((error) => {
              this.action = true;
              this.typeNotification = 1;
              this.textNotification = "Th??m m???i kh??ng th??nh c??ng." ;
            })
            .finally(() => {
              this.loading = false;
              this.typeNotification = null;
              this.textNotification = null;
            });
        } else {
          this.typeNotification = 1;
          this.textNotification =
            "Nh???p l???i password (Password v?? nh???p l???i password kh??ng tr??ng nhau)";
        }
      }
    },
    setCancel() {
      window.location.href = "/users";
    },
  },
};
</script>
