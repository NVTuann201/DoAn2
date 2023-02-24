const FILE_ICON = {
  excel: "mdi-file-excel-outline",
  pdf: "mdi-file-pdf-outline",
  word: "mdi-file-word-outline",
  image: "mdi-file-image",
  video: "mdi-file-video",
  music: "mdi-file-music",
  audio: "mdi-file-music",
  default: "mdi-file-outline",
};
export default {
  inject: {
    viewFile: {
      default: undefined,
    },
  },
  data: () => ({}),
  methods: {
    canView(file, type) {
      if (!this.viewFile) return false;
      return this.isTypeSupportView(file.type);
    },
    isTypeSupportView(file, type) {
      if (!file || !file.path) return false;
      type = type || file.file_type;
      return type == "image" || type == "pdf" || type == "video";
    },
    canDownload(file) {
      if (!file || file.file) return false;
      return true;
    },
    canDelete(file) {
      return this.removable && !file.is_delete;
    },
    canRestore(file) {
      return this.approvable && file.is_delete;
    },
    getIcon(type) {
      return FILE_ICON[type] || FILE_ICON.default;
    },
    includeFile(arr, file) {
      return arr.includes(file.name.split(".").pop());
    },
    canPreview(file) {
      return file.type.match("image.*") || file.type.match("video.*");
    },
    getType(file) {
      if (file.file_type) return file.file_type;
      if (file.is_new && file.file.type.match("image.*")) return "image";
      if (file.is_new && file.file.type.match("video.*")) return "video";
      if (file.is_new && file.file.type.match("audio.*")) return "audio";
      if (file.is_new && file.file.type.match("application/pdf")) return "pdf";
      if (this.includeFile(["png", "jpg"], file)) {
        return "image";
      }
      if (this.includeFile(["pdf"], file)) {
        return "pdf";
      }
      if (this.includeFile(["doc", "docx"], file)) {
        return "word";
      }
      if (
        this.includeFile(["xlsx", "xlsm", "xlsb", "xltx", "xltm", "xls"], file)
      ) {
        return "excel";
      }
    },
    async downloadFile(item) {
      // item.loading = true;
      // const res = await sdk.request({
      //   url: "/document/" + item.id,
      //   responseType: "blob"
      // });
      // saveAs(new Blob([res.data]), item.name);
      // item.loading = false;
    },
  },
};
