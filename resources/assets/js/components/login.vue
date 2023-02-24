<template>
  <main id="main" class="main ng-scope" role="main" ui-view style>
    <header-component></header-component>
    <div ui-view="main" class="viewContentWrapper ng-scope">
      <div class="site-content ng-scope">
        <div class="site-content__page">
          <div class="user white-background">
            <div class="wrapper-horizontal-stripes ng-scope">
              <div
                class="horizontal-stripe light-background"
                style="min-height: calc(100vh - 290px);"
              >
                <div class="card card--login" style="margin-top: 5vh;">
                  <div class="userLogin ng-isolate-scope" user-login>
                    <nav class="discreetTabs ng-scope">
                      <ul>
                        <li :class="popUpFrom ? '' : 'isActive'">
                          <a
                            style="cursor: pointer;"
                            class="inherit noUnderline ng-scope"
                            @click="popUpFrom=0"
                          >{{ $t("nbds_lang.message_please_login") }}</a>
                        </li>
                        <li :class="popUpFrom ? 'isActive' : ''" style="display: none">
                          <a
                            style="cursor: pointer;"
                            class="inherit noUnderline ng-scope"
                            @click="popUpFrom=1"
                          >Register</a>
                        </li>
                      </ul>
                    </nav>
                    <form
                      class="userLogin__form gb-form ng-pristine ng-scope ng-invalid ng-invalid-required"
                      v-if="!popUpFrom"
                    >
                      <div class="gb-form__group">
                        <label>
                          <span class="ng-scope">{{ $t("tank_auth_lang.auth_form_email") }}</span>
                          <input
                            type="text"
                            v-model="username"
                            required
                            class="ng-pristine ng-untouched ng-empty ng-invalid ng-invalid-required"
                          >
                        </label>
                      </div>
                      <div class="gb-form__group">
                        <label>
                          <span class="ng-scope">{{ $t("tank_auth_lang.auth_form_password") }}</span>
                          <input
                            type="password"
                            v-model="password"
                            required
                            class="ng-pristine ng-untouched ng-empty ng-invalid ng-invalid-required"
                          >
                        </label>
                        <div class="text-right">
                          <a
                            href
                            class="small ng-scope"
                          >{{ $t("tank_auth_lang.auth_form_forgot_password") }}</a>
                        </div>
                      </div>
                      <p class="small text-error ng-scope"></p>
                      <p class="m-t-1">
                        <button
                          class="gb-button--brand ng-scope"
                          type="button"
                          @click="summitForm()"
                        >{{ $t("tank_auth_lang.auth_form_login_button") }}</button>
                      </p>
                      <div class="text-center">
                        Không có tài khoản ?
                        <a
                            @click="register()"
                            class="ng-scope"
                            style="cursor: pointer;"
                          >Đăng kí</a>
                      </div>
                    </form>
                    <form
                      class="gb-form ng-pristine ng-scope ng-valid-editable ng-invalid ng-invalid-required ng-valid-pattern ng-valid-minlength ng-valid-maxlength"
                      style
                      v-if="popUpFrom"
                    >
                      <div class="ng-scope">
                        <div class="gb-form__group">
                          <label>
                            <span class="ng-scope">Country</span>
                            <input
                              type="text"
                              required
                              name="country"
                              autocomplete="off"
                              v-model="newCountry"
                            >
                            <ul class="dropdown-menu ng-isolate-scope ng-hide"></ul>
                          </label>
                          <div class="text-error small ng-inactive ng-hide" role="alert"></div>
                        </div>
                        <div class="gb-form__group">
                          <label>
                            <span class="ng-scope">Email</span>
                            <input
                              v-model="newEmail"
                              type="text"
                              name="email"
                              required
                              class="ng-pristine ng-untouched ng-empty ng-invalid ng-invalid-required ng-valid-pattern"
                              aria-invalid="true"
                            >
                          </label>
                          <div
                            class="text-error small ng-active ng-hide"
                            role="alert"
                            aria-live="assertive"
                            aria-hidden="true"
                          >
                            <div ng-message="required" class="ng-scope"></div>
                          </div>
                        </div>
                        <div class="gb-form__group">
                          <label>
                            <span class="ng-scope">Username</span>
                            <input
                              v-model="newName"
                              type="text"
                              name="username"
                              required
                              class="ng-pristine ng-untouched ng-empty ng-invalid ng-invalid-required ng-valid-pattern"
                              aria-invalid="true"
                            >
                          </label>
                          <div
                            class="text-error small ng-active ng-hide"
                            role="alert"
                            aria-live="assertive"
                            aria-hidden="true"
                          >
                            <div class="ng-scope"></div>
                          </div>
                        </div>
                        <div class="gb-form__group">
                          <label>
                            <span class="ng-scope">Password</span>
                            <input
                              v-model="newPass"
                              type="password"
                              name="password"
                              required
                              class="ng-pristine ng-untouched ng-empty ng-invalid ng-invalid-required ng-valid-minlength ng-valid-maxlength"
                              aria-invalid="true"
                            >
                          </label>
                          <div
                            class="text-error small ng-active ng-hide"
                            role="alert"
                            aria-live="assertive"
                            aria-hidden="true"
                          >
                            <div class="ng-scope"></div>
                          </div>
                        </div>
                        <p class="m-t-1">
                          <a href class="gb-button--brand ng-scope">Next</a>
                        </p>
                        <div class="text-error small" role="alert"></div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <footer-component></footer-component>
  </main>
</template>
<script>
import * as env from "../env.js";
import Multiselect from "vue-multiselect";
/*import Datepicker from "vuejs-datepicker";
import { en, vi } from "vuejs-datepicker/dist/locale";
import moment from "../../../../node_modules/moment/moment.js";*/

export default {
  components: {
    Multiselect
  },
  data: function() {
    return {
      popUpFrom: 0,
      username: "",
      password: "",
      newName: "",
      newPass: "",
      newCountry: "",
      newEmail: ""
    };
  },
  mounted() {},
  methods: {
    summitForm() {
      axios
        .post("/login", {
          username: this.username,
          password: this.password
        })
        .then(function(response) {
          window.location.href = "/admin";
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    register() {
      this.popUpFrom = 1;
    }
  }
};
</script>
<style>
.main {
  margin-top: 50px;
}
.site-content__page {
  min-height: auto !important;
}
</style>


