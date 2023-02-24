<template>
  <div class="notification">
    <div class="info-message" v-show="typeNotification == 0">
      <div class="jq-toast-wrap top-right">
        <div
          class="jq-toast-single jq-has-icon jq-icon-info"
          style="text-align: left; border-radius: 0px; /* display: none; */"
        >
          <span
            class="jq-toast-loader"
            id="info-notification"
            style="
              background-color: #ff6849;
              -webkit-transition: width 6s ease-in;
              -o-transition: width 6s ease-in;
              transition: width 6s ease-in;
            "
          ></span>
          <span class="close-jq-toast-single" @click="typeNotification = null"
            >×</span
          >
          <h2 class="jq-toast-heading" v-if="textNotification">Thông tin</h2>
          {{ textNotification }}
        </div>
      </div>
    </div>
    <div class="warning-message" v-if="typeNotification == 1">
      <div class="jq-toast-wrap top-right">
        <div
          class="jq-toast-single jq-has-icon jq-icon-warning"
          style="text-align: left; border-radius: 0px"
        >
          <span
            class="jq-toast-loader"
            id="warning-notification"
            style="
              -webkit-transition: width 7s ease-in;
              -o-transition: width 7s ease-in;
              transition: width 7s ease-in;
              background-color: #ff6849;
            "
          ></span>
          <span class="close-jq-toast-single" @click="typeNotification = null"
            >×</span
          >
          <h2 class="jq-toast-heading">Cảnh báo</h2>
          {{ textNotification }}
        </div>
      </div>
    </div>
    <div class="success-message" v-if="typeNotification == 2">
      <div class="jq-toast-wrap top-right">
        <div
          class="jq-toast-single jq-has-icon jq-icon-success"
          style="text-align: left; border-radius: 0px; /* display: none; */"
        >
          <span
            class="jq-toast-loader"
            id="success-notification"
            style="
              -webkit-transition: width 7s ease-in;
              -o-transition: width 7s ease-in;
              transition: width 7s ease-in;
              background-color: #ff6849;
            "
          ></span
          ><span class="close-jq-toast-single" @click="typeNotification = null"
            >×</span
          >
          <h2 class="jq-toast-heading">Thành công</h2>
          {{ textNotification }}
        </div>
      </div>
    </div>
    <div class="danger-message" v-if="typeNotification == 3">
      <div class="jq-toast-wrap top-right">
        <div
          class="jq-toast-single jq-has-icon jq-icon-error"
          style="text-align: left; border-radius: 0px; /* display: none; */"
        >
          <span
            class="jq-toast-loader"
            id="error-notification"
            style="
              -webkit-transition: width 7s ease-in;
              -o-transition: width 7s ease-in;
              transition: width 7s ease-in;
              background-color: #ff6849;
            "
          ></span
          ><span class="close-jq-toast-single" @click="typeNotification = null"
            >×</span
          >
          <h2 class="jq-toast-heading">Nguy hiểm</h2>
          {{ textNotification }}
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    textInput: { type: String },
    typeInput: { type: Number },
  },
  data: function () {
    return {
      typeNotification: null,
      textNotification: null,
    };
  },
  watch: {
    textInput(value) {
      if (!(value === null)) {
        this.textNotification = value;
        setTimeout(this.addClassWith, 5);
      }
    },
    typeInput(value) {
      if (!(value === null)) {
        this.typeNotification = value;
        setTimeout(this.clearData, 7000);
      }
    },
  },
  methods: {
    clearData() {
      this.textNotification = null;
      this.typeNotification = null;
      if (this.typeNotification == 0) {
        $("#info-notification").removeClass("jq-toast-loaded");
      } else if (this.typeNotification == 1) {
        $("#warning-notification").removeClass("jq-toast-loaded");
      } else if (this.typeNotification == 2) {
        $("#success-notification").removeClass("jq-toast-loaded");
      } else if (this.typeNotification == 3) {
        $("#error-notification").removeClass("jq-toast-loaded");
      }
      this.$emit("update:typeInput", null);
      this.$emit("update:textInput", null);
    },
    addClassWith() {
      if (this.typeNotification == 0) {
        $("#info-notification").addClass("jq-toast-loaded");
      } else if (this.typeNotification == 1) {
        $("#warning-notification").addClass("jq-toast-loaded");
      } else if (this.typeNotification == 2) {
        $("#success-notification").addClass("jq-toast-loaded");
      } else if (this.typeNotification == 3) {
        $("#error-notification").addClass("jq-toast-loaded");
      }
    },
  },
};
</script>
