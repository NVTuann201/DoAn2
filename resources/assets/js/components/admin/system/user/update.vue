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
          <div class="radio-list">
            <label class="radio-inline p-0">
              <div class="radio radio-info">
                <input
                  type="radio"
                  v-model="inactive"
                  name="radio"
                  id="radio1"
                  value="false"
                />
                <label for="radio1">Hoạt động</label>
              </div>
            </label>
            <label class="radio-inline">
              <div class="radio radio-info col-md-12">
                <input
                  type="radio"
                  v-model="inactive"
                  name="radio"
                  id="radio2"
                  value="true"
                />
                <label for="radio2">Không hoạt động</label>
              </div>
            </label>
          </div>
        </div>

        <div class="form-group col-md-6">
          <span
            @click="popUpChangePass = true"
            style="pointer"
            v-if="popUpChangePass == false"
            >{{ $t("admin.user.change_password") }} &nbsp;
            <i class="fa fa-caret-down"></i>
          </span>
          <span
            @click="popUpChangePass = false"
            style="pointer"
            v-if="popUpChangePass == true"
            >{{ $t("admin.user.change_password") }} &nbsp;
            <i class="fa fa-caret-up"></i>
          </span>
        </div>
        <div class="col-md-12 form-material">
          <div class="form-group" v-if="popUpChangePass == true">
            <label>{{ $t("admin.user.new_password") }}</label>
            <div>
              <input
                type="password"
                v-model="password"
                class="form-control form-control-line"
                v-validate="'min:6'"
                name="password"
              />
              <span
                v-show="errors.has('password')"
                class="help is-danger"
                style="color: red"
                >{{ $t("admin.error.password") }}</span
              >
            </div>
          </div>
          <div class="form-group" v-if="popUpChangePass == true">
            <label>{{ $t("admin.user.check_password") }}</label>
            <div>
              <input
                type="password"
                v-model="checkPassword"
                class="form-control form-control-line"
                v-validate="'min:6'"
                name="password_confirmation"
              />
              <span
                v-show="errors.has('password_confirmation')"
                class="help is-danger"
                style="color: red"
                >{{ $t("admin.error.password") }}</span
              >
            </div>
          </div>
        </div>
      </div>
    </div>

    <RoleDetail v-model="role" :danhmucs="danhmucs"/>
    <div class="col-sm-12">
      <div class="text-right">
        <button
          class="btn btn-inverse m-r-20"
          type="button"
          @click="cancelUpdateUser()"
        >
          {{ $t("admin.label.cancel") }}
        </button>
        <button class="btn btn-success" @click="updateUser()" type="button">
          {{ $t("admin.label.update") }}
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import { ValidationProvider } from "vee-validate";

import RoleDetail from "./role-detail.vue";
import { user_url_update } from "@/routes.js";
export default {
  components: {
    Multiselect,
    ValidationProvider,
    RoleDetail,
  },
  props: {
    value: { required: true },
    danhmucs: { type: Object, default: () => ({}) },
  },
  data: function () {
    return {
      email: null,
      phone: null,
      name: null,
      system: null,
      description: null,
      loading: false,
      action: true,
      typeNotification: null,
      textNotification: null,
      role: {
        role: null,
        coso_type: "",
        coso: null,
        provinces: [],
      },
      birthday: null,
      optionsTime: {
        format: "DD/MM/YYYY",
        useCurrent: false,
      },
      password: null,
      checkPassword: null,
      popUpChangePass: false,
      inactive: false,
      provinces: [],
    };
  },
  mounted() {
    this.getDefaultValue();
  },
  methods: {
    getDefaultValue() {
      let data = this.value;
      if (this.value) {
        this.id = data.id;
        this.name = data.name;
        this.phone = data.phone;
        this.email = data.email;
        this.role_id = data.role_id;
        this.birthday = data.birthday;
        this.inactive = data.inactive;
        let role = null;
        for (let i in this.danhmucs.roles) {
          if (this.role_id == this.danhmucs.roles[i].id) {
            role = this.danhmucs.roles[i];
            break;
          }
        }
        let coso = null;
        if (data.coso) {
          coso = {
            id: data.coso.id,
            name: data.coso.orig_name || data.coso.ten,
          };
        }
        this.role = {
          role,
          provinces: data.provinces,
          coso,
          coso_type: data.coso_type,
        };
      }
    },
    updateUser() {
        this.$validator.validate().then((valid) => {
        if (valid) {
          if (this.action == true) {
            if (this.password == this.checkPassword) {
              this.loading = true;
              this.action = false;
              let data = {
                email: this.email,
                birthday: this.birthday,
                password: this.password,
                role_id: this.role.role.id,
                name: this.name,
                phone: this.phone,
                inactive: this.inactive,
                province_ids: this.role.provinces
                  ? this.role.provinces.map((x) => x.id)
                  : "",
                coso_type: this.role.coso_type,
                coso_id: this.role.coso ? this.role.coso.id : "",
              };
              axios
                .post(user_url_update + "/" + this.id, data)
                .then((response) => {
                  if (response.data.message == "message.success") {
                    this.typeNotification = 2;
                    this.textNotification = "Cập nhật thành công.";
                    window.location.href = "/users";
                    this.action = true;
                  } else {
                    this.action = true;
                    this.typeNotification = 1;
                    this.textNotification =  response.data.error[Object.keys(response.data.error)[0]];
                  }
                })
                .catch((error) => {
                  this.action = true;
                  this.typeNotification = 1;
                  this.textNotification = "Cập nhật không thành công.";
                })
                .finally(() => {
                  this.loading = false;
                  this.typeNotification = null;
                  this.textNotification = null;
                });
            } else {
              this.typeNotification = 1;
              this.textNotification =
                "Nhập lại password (Password và nhập lại password không trùng nhau)";
            }
          }
        }
      });
    },
    cancelUpdateUser() {
      window.location.href = "/users";
    },
  },
};
</script>
