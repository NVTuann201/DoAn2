<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container" :style="{ width: width + 'px' }">
          <div class="modal-header">
            {{ title }}
          </div>

          <div class="modal-body">
            <slot name="body"></slot>
          </div>

          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-inverse"
              style="float: left"
              @click="$emit('close')"
            >
              {{ $t("admin.buttons.cancel") }}
            </button>
            <slot name="footer"> </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props: {
    width: {
      default: 500,
    },
    title: {
      type: String,
      default: "Xác nhận",
    },
  },

  data: () => ({}),
};
</script>

<style scoped>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  font-family: Helvetica, Arial, sans-serif;
  max-height: 95vh;
}

.modal-header {
  font-size: 18px;
  font-weight: bold;
  padding: 5px;
}

.modal-body {
  padding: 5px;
  margin: 10px 0;
  overflow: auto;
  max-height: 500px;
}
.modal-footer {
  padding: 0px;
  padding-top: 15px;
}
</style>