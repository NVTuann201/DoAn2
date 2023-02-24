<template>
  <div>
    <components-notifications
      :typeInput.sync="typeNotification"
      :textInput.sync="textNotification"
    ></components-notifications>
    <FieldUploadFile @change="onChangFile" />
    <FieldGrid
      :items.sync="files"
      ref="list"
      class="p-10"
      @click:delete="onDelete"
    >
      <template #item-detail="{ value }">
        <div class="c-flex overflow-hidden">
          <DynamicForm
            class="m-0 flex-grow-1 overflow-auto p-20"
            v-model="value.properties"
            :fields="fields_Image"
            ref="form"
          />
          <div class="p-10 d-flex">
            <button class="btn" @click="onCancel()">Huỷ</button>
            <div class="flex-grow-1"></div>
            <button class="btn btn-info" @click="onUploadFile(value)">
              {{
                value.isNew ? $t("admin.buttons.add") : $t("admin.buttons.edit")
              }}
            </button>
          </div>
        </div>
      </template>
    </FieldGrid>
    <components-loading v-show="loading" />
  </div>
</template>

<script>
import DynamicForm from "@/modules/dynamic-form/dynamic-form.vue";
import FieldUploadFile from "@/modules/file/file-drag-drop.vue";
import FieldGrid from "@/modules/file/file-grid.vue";
import { fields_Image } from "./field";
import { image_url } from "@/routes";
import { JsonToFormData } from "@/utils/json-to-form-data";
export default {
  props: {
    subject_id: { type: [String, Number], required: true },
    //subject_type là đường dẫn model hoặc được định nghĩa ở Relation::morphMap
    subject_type: { type: [String, Number], required: true },
  },
  components: { FieldUploadFile, FieldGrid, DynamicForm },
  computed: {},
  data: () => ({
    fields_Image,
    files: [],
    typeNotification: null,
    textNotification: null,
    loading: false,
  }),
  mounted() {
    this.getData();
  },
  methods: {
    getData() {
      this.loading = true;
      axios
        .get(image_url, {
          params: {
            subject_id: this.subject_id,
            subject_type: this.subject_type,
          },
        })
        .then((res) => {
          this.files = res.data.map((x) => ({
            id: x.id,
            name: x.title,
            file_type: "image",
            link: x.media && x.media[0] ? x.media[0].link : "",
            thumbnail: x.media && x.media[0] ? x.media[0].thumbnail : "",
            properties: x,
          }));
        })
        .finally(() => {
          this.loading = false;
        });
    },
    onChangFile(files) {
      let filesNews = files.map((file, index) => ({
        id: new Date().getTime() + Math.floor(Math.random() * 10000) + 1,
        name: file.name,
        link: window.URL.createObjectURL(file),
        thumbnail: window.URL.createObjectURL(file),
        file,
        isNew: true,
        properties: {},
      }));
      this.$refs.list.onPreview(filesNews[0]);
    },
    async onUploadFile(value) {
      let validate = await this.$refs.form.validateAll();
      if (!validate) {
        this.textNotification = "Dữ liệu không thỏa mãn";
        this.typeNotification = 1;
        return;
      }
      this.loading = true;
      let data = value.properties;
      data["subject_id"] = this.subject_id;
      data["subject_type"] = this.subject_type;
      try {
        if (value.isNew) {
          data.file = value.file;
          data = JsonToFormData(data);
          await axios.post(image_url, data);
        } else {
          await axios.put(image_url + "/" + value.id, data);
        }
        value.isNew = false;
        if (value.isNew) {
          this.files = this.files.unshift(value);
          this.textNotification = "Thêm mới thành công";
        } else {
          let index = this.files.findIndex((x) => x.id == value.id);
          this.$set(this.files, index, value);
          this.textNotification = "Cập nhật thành công";
        }
        this.$refs.list.onClosePreview();
        this.typeNotification = 2;
      } catch (error) {
        console.error(error);
        this.textNotification = "Thêm mới không thành công";
        this.typeNotification = 1;
      } finally {
        this.loading = false;
      }
    },
    onCancel() {
      this.$refs.list.onClosePreview();
    },
    async onDelete(item) {
      try {
        await axios.delete(image_url + "/" + item.id);
        let index = this.files.findIndex((x) => x.id == item.id);
        this.files.splice(index, 1);
        this.textNotification = "Xoá thành công";
        this.typeNotification = 2;
      } catch (error) {
        console.error(error);
        this.textNotification = "Xoá không thành công";
        this.typeNotification = 1;
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style></style>
