<template>
  <div>
    <div class="loading-roles" v-if="loading">
      <components-loading-page></components-loading-page>
    </div>
    <div class="jq-toast-wrap bottom-right">
        <div class="jq-toast-single jq-has-icon jq-icon-info" style="text-align: left;border-radius: 0px/* display: none; */">
            <h2 class="jq-toast-heading" style="text-align:center">{{$t('nbds_lang.info')}}</h2>
        </div>
    </div>
    <div class="horizontal-stripe--paddingless light-background seperator ng-scope">
      <div class="container--normal">
        <div class="clearfix ng-scope">
          <div class="switch-group pull-right">
            <p class="small ng-scope" v-if="edit">{{$t('nbds_lang.label_stop_editing')}}</p>
            <p class="small ng-scope" v-else>{{$t('nbds_lang.label_start_editing')}}</p>
            <input id="speciesLookupDiscardInCsv" type="checkbox" />
            <label @click="toggle()" for="speciesLookupDiscardInCsv" class="switch"></label>
          </div>
        </div>

        <div class="card card--spaced">
          <div class="card__stripe card__content">
            <div>
              <form class="gb-form">
                <fieldset>
                  <div class="row">
                    <div class="col-xs-12 col-sm-6">
                      <div class="gb-form__group">
                        <label>
                          <span class="ng-scope">{{$t('nbds_lang.users.name')}}</span>
                          <input type="text" v-model="name" name="name" :disabled="!edit" />
                          <div
                            class="text-error small"
                            v-show="!name"
                            aria-live="assertive"
                            aria-hidden="true"
                          >
                            <div>{{$t('nbds_lang.users.message_name')}}</div>
                          </div>
                        </label>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                      <div class="gb-form__group">
                        <label>
                          <span class="ng-scope">{{$t('nbds_lang.users.email')}}</span>
                          <input
                            type="text"
                            name="email"
                            v-model="email"
                            disabled="disabled"
                            aria-invalid="false"
                          />
                        </label>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                      <div class="gb-form__group">
                        <label>
                          <span class="ng-scope">{{$t('nbds_lang.users.phone')}}</span>
                          <input type="text" v-model="phone" name="phone" :disabled="!edit" />
                        </label>
                      </div>
                    </div>
                    <div v-show="edit&&name" class="col-xs-12 col-sm-6">
                      <button
                        @click="saveProfile()"
                        class="pull-right m-t-1 text-uppercase gb-button--flat ng-scope"
                      >Save</button>
                    </div>
                  </div>
                </fieldset>
              </form>
            </div>
            <div v-if="edit" class="ng-scope">
              <form class="gb-form m-t-3">
                <fieldset>
                  <div class="row">
                    <div class="col-xs-12 col-sm-6">
                      <div class="gb-form__group">
                        <label>
                          <span class="ng-scope">{{$t('admin.profile.new_password')}}</span>
                          <input
                            type="password"
                            name="new_password"
                            aria-invalid="true"
                            v-model="new_password"
                          />
                        </label>
                        <div
                          v-show="!new_password&&(repeated_password||current_password)"
                          class="text-error small ng-active ng-hide"
                          aria-live="assertive"
                          aria-hidden="true"
                        >
                          <div>{{$t('nbds_lang.message_reset_password')}}</div>
                        </div>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                      <div class="gb-form__group">
                        <label>
                          <span
                            translate="profile.repeatNewPassword"
                            class="ng-scope"
                          >{{$t('admin.profile.check_new_password')}}</span>
                          <input
                            type="password"
                            name="repeated_password"
                            v-model="repeated_password"
                            aria-invalid="true"
                          />
                        </label>
                        <div
                          class="text-error small ng-hide"
                          v-show="new_password !== repeated_password"
                          aria-hidden="true"
                        >
                          <div>{{$t('nbds_lang.message_identical_password')}}</div>
                        </div>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                      <div class="gb-form__group">
                        <label>
                          <span>{{$t('admin.profile.current_password')}}</span>
                          <input
                            type="password"
                            v-model="current_password"
                            name="current_password"
                            aria-invalid="true"
                          />
                        </label>
                        <div
                          class="text-error small"
                          v-show="!current_password&&(new_password||repeated_password)"
                          aria-live="assertive"
                          aria-hidden="true"
                        >
                          <div>{{$t('nbds_lang.message_unregister')}}</div>
                        </div>
                      </div>
                    </div>

                    <div
                      class="col-xs-12 col-sm-6"
                      v-show="new_password&&repeated_password&&current_password"
                    >
                      <button
                        class="pull-right m-t-1 text-uppercase gb-button--flat ng-scope"
                      >{{$t('nbds_lang.menu_change_password')}}</button>
                    </div>
                  </div>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as env from "../env.js";

export default {
  props: {
    value: { type: Object }
  },
  data: function() {
    return {
      name: "",
      email: null,
      phone: null,
      edit: false,
      loading: false,
      new_password: "",
      repeated_password: "",
      current_password: "",
      typeNotification: null,
      textNotification: null
    };
  },
  created() {},
  mounted() {
    this.name = this.value.name;
    this.email = this.value.email;
    this.phone = this.value.phone;
  },
  methods: {
    toggle() {
      this.edit = !this.edit;
    },
    saveProfile() {
      if (this.new_password == this.repeated_password) {
        this.loading = true;
        axios
          .post(env.endpointhttp + "user/profile/update/" + this.value.id, {
            name: this.name,
            phone: this.phone
          })
          .then(response => {
            if (response.data.message == "message.success") {
              this.typeNotification = 2;
              this.textNotification = "Default";
            } else {
              this.typeNotification = 1;
              this.textNotification = "Default";
            }
          })
          .catch(error => {
            this.typeNotification = 1;
            this.textNotification = "Default";
          })
          .finally(() => {
            this.loading = false;
            this.typeNotification = null;
            this.textNotification = null;
          });
      }
    }
  }
};
</script>