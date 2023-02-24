<template>
  <div class="justify-center">
    <!-- file -->
    <input
      type="file"
      ref="file"
      hidden
      multiple
      accept="image/*,video/*,application/pdf"
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
    <template v-if="!hiddenList && (files || value)">
      <div
        class="list--item--file mx-auto"
        style="max-width: 600px; width: 100%"
      >
        <div
          class="d-flex align-center mt-2 overflow-hidden"
          v-for="(item, index) in currentFiles"
          :key="index"
        >
          <div
            class="d-flex align-center full-width flex-grow-1 overflow-hidden"
          >
            <div class="flex-grow-0">
              <v-icon>mdi-file-outline</v-icon>
            </div>
            <div :title="item.name" class="flex-grow-1 text-truncate">
              {{ item.name }}
            </div>
          </div>
          <div
            class="ml-auto flex-grow-0 clickable"
            @click="removeFileUpload(item, index)"
          >
            <v-icon>mdi-close</v-icon>
          </div>
        </div>
        <!-- end file -->
      </div>
    </template>
  </div>
</template>
<script>
export default {
  props: ["dataForm", "value", "hiddenList"],
  data: () => ({
    files: [],
    drag: false,
    errSize: false,
  }),
  created() {},
  computed: {
    currentFiles() {
      return [].concat(this.files || [], this.value || []);
    },
    form: {
      get: function () {
        return this.dataForm;
      },
      set: function (value) {
        this.$emit("changeForm", value);
      },
    },
  },
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
      this.files = this.files ? [...this.files, ...files] : [...files];
      const input = this.$refs.file;
      input.type = "text";
      input.type = "file";
      input.value = "";
      this.$emit("change", files);
    },
    reset() {
      this.files = [];
      this.$emit("change", this.files);
    },
    removeFile(index) {
      this.files.splice(index, 1);
    },
    removeDocument(index) {
      this.form.documents.splice(index, 1);
    },
    removeFileUpload(item, index) {
      if (item.id) {
        let document = this.value.filter((x) => x.id != item.id);
        this.$emit("input", document);
        this.$emit("remove-document", item);
      } else {
        this.files.splice(index, 1);
        this.$emit("change", this.files);
      }
    },
    removeAllFile() {
      this.files = [];
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
