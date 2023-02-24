<template>
  <div class="download__excel d-flex align-items-center" @click="download()">
    <span class="gb-icon-file-download"></span>
    <span>{{$t('nbds_lang.download')}}</span>
    <svg
      v-if="load"
      class="circular mini"
      viewBox="25 25 50 50"
      style="width: 20px;right: -10px;top: -8px;left: unset;"
    >
      <circle
        class="path"
        cx="50"
        cy="50"
        r="20"
        style="stroke: rgba(0, 0, 0, 0.8)"
        fill="none"
        stroke-width="2"
        stroke-miterlimit="10"
      ></circle>
    </svg>
  </div>
</template>

<script>
import * as env from "../../env.js";
import {saveAs} from "file-saver"
export default {
  props: ["value"],
  data: function () {
    return {
      load: false,
      data: [],
    };
  },
  methods: {
    async download() {
      if (
        this.value.dataset_type == "Occurrence" ||
        this.value.dataset_type == "Taxon"
      ) {
        this.load = true;
        try {
          const res = await axios.request({
            url: `${env.endpointhttp}admin/dataset/exportExcelDataSet/${this.value.id}`,
            params: {
              dataset_type: this.value.dataset_type,
            },
            responseType: "blob",
          });
          saveAs(
            new Blob([res.data]),
            `dataset_${this.value.dataset_type.toLowerCase()}.xls`
          );
        } finally {
          this.load = false;
        }
      }
    },
  },
};
</script>

<style scoped>
.download__excel {
  cursor: pointer;
}
</style>
