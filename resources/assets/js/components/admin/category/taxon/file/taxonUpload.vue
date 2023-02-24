<template>
  <div>
    <FieldUploadFile @change="onChangFile" />
    <FieldGrid :items.sync="files" ref="list" class="p-10">
      <template #item-detail="{ value }">
        <div class="c-flex" style="overflow: hidden">
          <div class="p-20" style="overflow: auto">
            <DynamicForm
              class="m-0 flex-grow-1"
              :value="value.properties"
              :fields="fields_Image"
              @input="onUpdateData"
            />
          </div>
        </div>
      </template>
    </FieldGrid>
  </div>
</template>

<script>
import DynamicForm from "@/modules/dynamic-form/dynamic-form.vue";
import FieldUploadFile from "@/modules/file/file-drag-drop.vue";
import FieldGrid from "@/modules/file/file-grid.vue";
import { fields_Image } from "../field";
export default {
  props: { value: { type: Array, default: () => [] } },
  components: { FieldUploadFile, FieldGrid, DynamicForm },
  computed: {
    files: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      },
    },
  },
  data: () => ({ fields_Image }),
  methods: {
    onUpdateData(value) {
      console.log("🚀 ~ onUpdateData ~ value", value);
    },
    onChangFile(files) {
      let filesNews = files.map((file, index) => ({
        id: new Date().getTime() + Math.floor(Math.random() * 10000) + 1,
        name: file.name,
        is_new: true,
        is_delete: false,
        file_type: "image",
        link: window.URL.createObjectURL(file),
        thumbnail: window.URL.createObjectURL(file),
        file,
        properties: {},
      }));
      this.files = this.files.concat(filesNews);
      this.$refs.list.onPreview(filesNews[0]);
    },
  },
};
</script>

<style></style>
