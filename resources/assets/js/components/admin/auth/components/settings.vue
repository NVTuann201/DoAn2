<template>
  <div class="tab-pane" id="settings">
    <components-notifications
      :typeInput="typeNotification"
      :textInput="textNotification"
    >
    </components-notifications>
    <div class="loading-roles" v-if="loading">
      <components-loading-page></components-loading-page>
    </div>
    <div class="form-horizontal form-material">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8">
            <div class="card card-default">
              <div class="card-header"></div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4 col-md-offset-4" v-if="image">
                    <img
                      :src="image"
                      class="img-responsive"
                      height="70"
                      width="90"
                    />
                  </div>
                  <div class="col-md-4 col-md-offset-4" v-if="!image">
                    <img
                      :src="avatar_url"
                      class="img-responsive"
                      height="70"
                      width="90"
                    />
                  </div>
                  <div
                    class="col-md-8 col-md-offset-2"
                    style="margin-top: 10px; margin-bottom: 10px"
                  >
                    <label
                      for="files"
                      class="btn btn-success"
                      v-show="update"
                      >{{ $t("admin.profile.upload_img") }}</label
                    ><span style="margin-left: 10px">{{
                      $t("admin.profile.upload_img_note")
                    }}</span>
                    <input
                      type="file"
                      id="files"
                      name="image"
                      v-validate="'size:32768'"
                      style="visibility: hidden"
                      v-on:change="onImageChange"
                      accept="image/x-png,image/gif,image/jpeg"
                      class="form-control"
                    />
                    <span
                      v-show="errors.has('image')"
                      class="help is-danger"
                      style="color: red"
                      >{{ $t("admin.organization.error.image") }}</span
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-12">{{ $t("admin.profile.full_name") }}</label>
        <div class="col-md-12">
          <input
            type="text"
            v-model="name"
            class="form-control form-control-line"
            :class="update ? '' : 'pointer-events'"
          />
        </div>
      </div>
      <div class="form-group" style="overflow: visible">
        <label class="col-md-12">{{ $t("admin.profile.birthday") }}</label>
        <div class="col-md-12">
          <date-picker
            v-model="birthday"
            :config="optionsTime"
            class="form-control form-control-line"
            :class="update ? '' : 'pointer-events'"
          ></date-picker>
        </div>
      </div>
      <div class="form-group">
        <label for="example-email" class="col-md-12">{{
          $t("admin.profile.email")
        }}</label>
        <div class="col-md-12">
          <input
            type="email"
            v-model="email"
            class="form-control form-control-line"
            :class="update ? '' : 'pointer-events'"
            name="example-email"
            id="example-email"
          />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-12">{{ $t("admin.profile.phone") }}</label>
        <div class="col-md-12">
          <input
            type="text"
            v-model="phone"
            class="form-control form-control-line"
            :class="update ? '' : 'pointer-events'"
          />
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-9" v-show="!update">
          <button class="btn btn-success" @click="update = true" type="button">
            {{ $t("admin.profile.update") }}
          </button>
        </div>
        <div class="col-sm-9" v-show="update">
          <button
            class="btn"
            @click="update = false"
            style="background-color: #555; color: white"
            type="button"
          >
            {{ $t("admin.profile.cancel") }}
          </button>
        </div>
        <div class="col-sm-3" v-show="update">
          <button
            class="btn btn-success"
            @click="updateProfile()"
            type="button"
            :disabled="this.loading"
          >
            {{ $t("admin.profile.submit") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { endpoint, endpointhttp } from "../../../../env.js";
import { ValidationProvider } from "vee-validate";

export default {
  components: {
    ValidationProvider,
  },
  props: {
    value: { type: String },
    imageurl: { type: String },
  },
  data: function () {
    return {
      code: null,
      name: null,
      email: null,
      phone: null,
      birthday: null,
      update: false,
      image: "",
      avatar_url: null,
      optionsTime: {
        format: "DD/MM/YYYY",
        useCurrent: false,
      },
      file: [],
      media: [],
      typeNotification: null,
      textNotification: null,
      loading: false,
    };
  },
  created() {
    this.image = this.imageurl;
    this.code = JSON.parse(this.value);
    this.getData();
  },
  methods: {
    getData() {
      this.name = this.code.name;
      this.email = this.code.email;
      this.phone = this.code.phone;
      this.birthday = this.code.birthday;
      this.avatar_url = this.code.avatar_url;
      if (this.code.media.length > 0)
        this.media = this.code.media[0].id ? this.code.media[0] : [];
    },
    updateProfile() {
      this.loading = true;
      let formData = new FormData();
      formData.append("images", this.file);
      axios
        .post(endpointhttp + "uploadimage", formData, {
          header: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          this.media = response.data.result;
          axios
            .post(endpointhttp + "profile", {
              name: this.name,
              email: this.email,
              phone: this.phone,
              birthday: this.birthday,
              media: this.media,
            })
            .then((response) => {
              if (response.data.message == "message.success") {
                this.action = true;
                this.typeNotification = 2;
                this.textNotification = "Cập nhật thành công";
                this.update = false;
                window.location.href = "/profile";
              } else {
                this.action = true;
                this.typeNotification = 1;
                this.textNotification = "Cập nhật không thành công";
              }
            })
            .catch((error) => {
              this.action = true;
              this.typeNotification = 1;
              this.textNotification = "Cập nhật không thành công";
            })
            .finally(() => {
              this.loading = false;
              this.typeNotification = null;
              this.textNotification = null;
            });
        });
    },
    onImageChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.file = files[0];
      this.createImage(files[0]);
    },
    createImage(file) {
      let reader = new FileReader();
      let vm = this;
      reader.onload = (e) => {
        vm.image = e.target.result;
      };
      reader.readAsDataURL(file);
    },
  },
};
</script>
