<template>
  <div>
    <div class="d-flex">
      <label class="form-label" style="flex: 1">Tệp đính kèm </label>
      <div>
        <button
          type="button"
          class="btn btn-info btn-sm"
          data-bs-toggle="modal"
          data-bs-target="#exampleModal"
          data-bs-placement="top"
          title="Tải lên"
          @click="clickUpload"
        >
          <i class="fas fa-thumbtack"></i> Tải tệp lên
        </button>
      </div>
      <input
        type="file"
        id="myfile"
        name="myfile"
        multiple
        ref="upload-files"
        style="display: none"
        @change="handleUpload"
      />
    </div>
    <br />
    <div>
      <div
        class="progress"
        style="height: 10px"
        v-show="
          tienTrinhUpload && tienTrinhUpload != 0 && tienTrinhUpload != 100
        "
      >
        <div
          class="progress-bar"
          role="progressbar"
          style="font-weight: bold"
          aria-valuenow="25"
          aria-valuemin="0"
          aria-valuemax="100"
          :style="{ width: tienTrinhUpload + 'px' }"
        >
          {{ tienTrinhUpload }}
        </div>
      </div>
      <div class="c-flex">
        <div class="d-flex" v-for="item in files" :key="item.id">
          <div
            @click="taiTapTin(item)"
            style="flex: 1; padding-bottom: 5px; cursor: pointer"
            :title="item.name"
          >
            {{
              item.name && item.name.length < 40
                ? item.name
                : "..." + item.name.substr(-39)
            }}
          </div>
          <div>
            <i
              class="fas fa-trash-alt"
              style="cursor: pointer"
              @click="xoaTapTin(item.id)"
            ></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["form", "field"],
  data() {
    return { tienTrinhUpload: 0, files: [], uploaded: 0 };
  },
  watch: {
    uploaded(val) {
      this.files = [...this.form[this.field]];
    },
  },
  methods: {
    clickUpload() {
      this.$refs["upload-files"].click();
    },
    async handleUpload(e) {
      var isValidate = true;
      let files = e.target.files;
      let data = new FormData();
      if (!files || files.length == 0) {
        return;
      }
      for (let el of files) {
        const isLt2M = el.size / 1024 / 1024 < 50;
        if (!isLt2M) {
          this.typeNotification = 1;
          this.textNotification = "File phải nhỏ hơn 50Mb";
          isValidate = false;
          this.form[this.field] = [];
          break;
        }
        data.append("file[]", el);
      }

      if (!isValidate) return;

      try {
        this.tienTrinhUpload = 1;
        let File_id = await axios.post("/admin/uploadfiles", data, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
          onUploadProgress: (progressEvent) => {
            var percentCompleted = Math.round(
              (progressEvent.loaded * 100) / progressEvent.total
            );
            this.tienTrinhUpload = percentCompleted;
          },
        });
        console.log(this.form, this.field);
        this.form[this.field] = [
          ...this.form[this.field],
          ...File_id.data.files,
        ];
        this.uploaded++;
        // this.form[this.field] = this.form[this.field].map((el) => el.id);
      } catch (error) {
        console.log(error);
      }
      this.$refs["upload-files"].value = null;
    },
    xoaTapTin(id) {
      this.form[this.field].splice(
        this.form[this.field].findIndex((i) => i.id === id),
        1
      );
      this.files = [...this.form[this.field]];
    },
    taiTapTin(data) {
      window.open("/" + data.disk, "_blank");
    },
  },
  created() {
    this.files = [...this.form[this.field]];
  },
};
</script>
