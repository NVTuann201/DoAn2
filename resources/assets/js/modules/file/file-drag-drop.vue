<template>
  <div class="justify-center">
    <!-- file -->
    <input
      type="file"
      ref="file"
      hidden
      :multiple="multiple"
      :accept="accept"
      @change="fileChange"
    />
    <div
      class="file__upload text-center"
      @click="goAddFile"
      :class="[drag ? 'drag' : '']"
      @dragover="
        overrideDefault($event);
        hoverDrag();
      "
      @dragenter="
        overrideDefault($event);
        hoverDrag();
      "
      @dragleave="
        overrideDefault($event);
        hoverDragEnd();
      "
      @drop="
        overrideDefault($event);
        hoverDragEnd();
        fileChange($event);
      "
    >
      <v-icon size="30">mdi-paperclip</v-icon>
      <div class="mt-2">Chọn hoặc kéo thả để tải lên tài liệu đính kèm</div>
    </div>
    <!-- end file -->
  </div>
</template>
<script>
export default {
  props: {
    accept: { type: String, default: "image/*" },
    multiple: Boolean,
  },
  data: () => ({
    drag: false,
  }),
  created() {},
  computed: {},
  methods: {
    // inputfile
    //drag event
    hoverDrag() {
      this.drag = true;
    },
    hoverDragEnd() {
      this.drag = false;
    },
    overrideDefault(e) {
      e.preventDefault();
      e.stopPropagation();
    },
    goAddFile() {
      this.$refs.file.click();
    },
    fileChange(e) {
      let files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
      files = Array.from(files);

      const input = this.$refs.file;
      input.type = "text";
      input.type = "file";
      input.value = "";
      this.$emit("change", files);
    },
  },
};
</script>
<style scoped>
/* effect drag */
.drag {
  border: 3px dashed "#4CAF50";
}
.file__upload {
  background-color: #eceff1;
  padding: 25px 20%;
  border-radius: 6px;
  cursor: pointer;
}
.v-icon.mdi-paperclip {
  transform: rotate(45deg);
}
.err-warning {
  color: "#cd2131";
  font-size: 13px !important;
}
.ls_document {
  color: "#4CAF50";
}
@media (max-width: 600px) {
  .file__upload {
    background-color: #eceff1;
    padding-left: 50px !important;
    padding-right: 50px !important;
    border-radius: 6px;
    cursor: pointer;
  }
}
[hidden],
template {
  display: none !important;
}
</style>
