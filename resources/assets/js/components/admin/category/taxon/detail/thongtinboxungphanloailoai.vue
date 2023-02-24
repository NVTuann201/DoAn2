<template>
  <div class="">
    <DynamicForm v-model="form" :fields="fields" ref="dynamicForm">
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <label class="form-label"> Cites </label>
        <multiselect
          v-model="form.cites"
          :options="cites"
          placeholder="Cites"
          :show-labels="false"
        />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <label class="form-label"> Nghị định </label>
        <multiselect
          v-model="form.nghi_dinh"
          :options="nghiDinhs"
          placeholder="Chọn một nghị định"
          :show-labels="false"
        />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <label class="form-label"> Phụ lục Nghị Định</label>
        <input
          v-model="form.phu_luc_nghi_dinh"
          class="form-control"
          placeholder="Nhập phụ lục của nghị định"
        />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> IUCN 2012 </label>
        <multiselect
          v-model="iucnSelect"
          :options="iucns"
          track-by="name"
          label="name"
          placeholder="Chọn một danh mục"
          :show-labels="false"
        />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <label class="form-label"> Sách đỏ Việt Nam</label>
        <multiselect
          v-model="sachDoSelect"
          :options="sachDos"
          track-by="name"
          label="name"
          placeholder="Chọn một danh mục"
          :show-labels="false"
        />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <label class="form-label"> Phân hạng theo sách đỏ</label>
        <input class="form-control" v-model="form.phan_hang_sach_do" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Luật về bảo vệ loài </label>
        <input
          v-model="form.other_vietnamese_law_to_protect_species"
          class="form-control"
        />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Nguồn gốc Việt Nam </label>
        <select class="form-control" v-model="form.provenance_in_vietnam">
          <option
            v-for="nguongoc in nguongoc.items"
            :key="nguongoc.value"
            :value="nguongoc.value"
          >
            {{ nguongoc.text }}
          </option>
        </select>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Nguồn gốc địa phương </label>
        <select class="form-control" v-model="form.provenance_in_local">
          <option
            v-for="item in nguongoc.items"
            :key="item.value"
            :value="item.value"
          >
            {{ item.text }}
          </option>
        </select>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Tình trạng xâm nhập </label>
        <input v-model="form.invasive_status" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Sử dụng </label>
        <input v-model="form.use" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Mô tả hình thái </label>
        <input v-model="form.morphological_description" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Đặc tính hành vi </label>
        <select
          class="form-control"
          v-model="form.classification_of_behavioral_features"
        >
          <option
            v-for="item in dactinhhanhvi.items"
            :key="item.value"
            :value="item.value"
          >
            {{ item.text }}
          </option>
        </select>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Phân bổ địa phương </label>
        <select class="form-control" v-model="form.distribution_in_local">
          <option
            v-for="item in phanbodiaphuong.items"
            :key="item.value"
            :value="item.value"
          >
            {{ item.text }}
          </option>
        </select>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Phân bổ Việt Nam </label>
        <select class="form-control" v-model="form.distribution_in_vietnam">
          <option
            v-for="item in phanbodiaphuong.items"
            :key="item.value"
            :value="item.value"
          >
            {{ item.text }}
          </option>
        </select>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Phân bổ thế giới </label>
        <input v-model="form.distribution_in_the_world" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Phương pháp bảo vệ </label>
        <input v-model="form.protection_measures" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Thời gian sinh sản </label>
        <input v-model="form.reproductive_time" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Hình thức sinh sản </label>
        <input v-model="form.reproductive_form" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Điều kiện sinh sản </label>
        <input v-model="form.reproduction" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Thời gian sinh trưởng </label>
        <input v-model="form.development_time" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Thức ăn </label>
        <input v-model="form.food" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Đặc tính sinh học </label>
        <input
          v-model="form.other_biological_characteristics"
          class="form-control"
        />
      </div>
    </DynamicForm>
  </div>
</template>

<script>
import DynamicForm from "@/modules/dynamic-form/dynamic-form.vue";
import Multiselect from "vue-multiselect";

export default {
  components: { DynamicForm, Multiselect },
  props: { value: { type: Object, default: () => ({}) } },
  computed: {
    form: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      },
    },
  },
  watch: {
    sachDoSelect(val) {
      this.form.red_list = null;
      if (val && val.value) {
        this.form.red_list = val.value;
      }
    },
    iucnSelect(val) {
      this.form.iucn_2012 = null;
      if (val && val.value) {
        this.form.iucn_2012 = val.value;
      }
    },
  },
  mounted(){
    this.sachDoSelect = this.form.red_list ? this.sachDos.find(el => el.value == this.form.red_list) : null
    this.iucnSelect = this.form.iucn_2012 ? this.iucns.find(el => el.value == this.form.iucn_2012) : null
  },
  data: () => ({
    sachDoSelect: null,
    iucnSelect: null,
    cites: ["I", "II", "III"],
    nghiDinhs: ["Nghị định 160", "Nghị định 32", "Nghị định 64"],
    iucns: [
      { value: "EX", name: "Tuyệt chủng (Extinct - EX)" },
      {
        value: "EW",
        name: "Tuyệt chủng ngoài thiên nhiên (Extinct in the wild - EW)",
      },
      { value: "CR", name: "Cực kì nguy cấp (Critically Endangered - CR)" },
      { value: "EN", name: "Nguy cấp (Endangered - EN)" },
      { value: "VU", name: "Sắp nguy cấp (Vulnerable - VU)" },
      { value: "NT", name: "Sắp bị đe dọa (NT)" },
      { value: "LC", name: "Ít quan tâm (Least concern - LC)" },
      { value: "DD", name: "Thiếu dữ liệu (Data deficient - DD)" },
      { value: "NE", name: "Không được đánh giá (Not evaluated - NE)" },
    ],
    sachDos: [
      { value: "EX", name: "Tuyệt chủng (Extinct - EX)" },
      {
        value: "EW",
        name: "Tuyệt chủng ngoài thiên nhiên (Extinct in the wild - EW)",
      },
      { value: "CR", name: "Rất nguy cấp (Critically Endangered - CR)" },
      { value: "EN", name: "Nguy cấp (Endangered - EN)" },
      { value: "VU", name: "Sẽ nguy cấp (Vulnerable - VU)" },
      { value: "CD", name: "Phụ thuộc bảo tồn" },
      { value: "NT", name: "Sắp bị đe dọa" },
      { value: "LC", name: "Ít lo ngại" },
      { value: "DD", name: "Thiếu dữ liệu" },
      { value: "NE", name: "Không đánh giá" },
    ],
    fields: [
      {
        label: "Môi trường sống",
        type: "select",
        field: "habitat",
        meta: {
          items: [
            { value: 1, text: "Nước mặn" },
            { value: 2, text: "Nước lợ" },
            { value: 3, text: "Nước ngọt" },
            { value: 4, text: "Lưỡng cư" },
            { value: 5, text: "Trên cạn" },
          ],
        },
      },
      {
        label: "Đặc tính sinh thái",
        type: "text",
        field: "ecological_characteristics",
      },
      {
        label: "Thông tin khác",
        type: "text",
        field: "other_information_related_to_species",
      },
      {
        label: "Tự nhiên",
        type: "text",
        field: "naturalness",
      },
      {
        label: "Nhạy cảm",
        type: "text",
        field: "sensitivity",
      },
      {
        label: "Hiếm",
        type: "select",
        field: "rarity",
        meta: {
          items: [
            { value: 1, text: "Loài quý hiếm" },
            { value: 2, text: "Loài then chốt" },
            { value: 3, text: "Loài kinh tế" },
          ],
        },
      },
      {
        label: "Tình trạng Phân bố ở Việt Nam",
        type: "text",
        field: "distribution_status_in_vietnam",
      },
      {
        label: "Loài đặc hữu",
        type: "boolean",
        field: "local_endemism",
      },
      // {
      //   label: "Phân loại học",
      //   type: "search",
      //   field: "taxon_id",
      // },
      {
        label: "Rừng ngập mặn",
        type: "text",
        field: "mangrove",
      },
      {
        label: "Dạng sống",
        type: "text",
        field: "lifeform",
      },
      {
        label: "Nhạy cảm môi trường",
        type: "text",
        field: "environmentally_sensitive",
      },
      {
        label: "Phong phú và hiếm",
        type: "select",
        field: "rich_and_rare",
        meta: {
          items: [
            { value: 1, text: "Phong phú" },
            { value: 2, text: "Xu hướng tăng" },
            { value: 3, text: "Xu hướng giảm" },
            { value: 4, text: "Hiếm" },
          ],
        },
      },
      {
        label: "Số lượng cá thể",
        type: "number",
        field: "population",
        meta: {
          min: 0,
        },
      },
      {
        label: "Ghi chú",
        type: "select",
        field: "note",
        meta: {
          items: [
            { value: 1, text: "Loài mới phát hiện" },
            { value: 2, text: "Loài lâu không xuất hiện (>5 năm)" },
            { value: 3, text: "Khác" },
          ],
        },
      },
      {
        label: "Nguồn gốc cơ sở",
        type: "select",
        field: "base_origin",
        meta: {
          items: [
            { value: 1, text: "Tự nhiên" },
            { value: 2, text: "Gây nuôi" },
            { value: 3, text: "Cứu hộ" },
            { value: 4, text: "Tặng cho" },
            { value: 5, text: "Thuê" },
            { value: 6, text: "Nhập khẩu" },
            { value: 7, text: "Khác" },
          ],
        },
      },
      {
        label: "Mục đích nuôi trồng",
        type: "select",
        field: "cultivation_purpose",
        meta: {
          items: [
            { value: 1, text: "Bảo tồn" },
            { value: 2, text: "Nghiên cứu khoa học" },
            { value: 3, text: "Tham quan du lịch" },
            { value: 4, text: "Làm xiếc" },
            { value: 5, text: "Thương mại" },
            { value: 6, text: "Sưu tập" },
            { value: 7, text: "Trưng bày" },
            { value: 8, text: "Khác" },
          ],
        },
      },
      {
        label: "Áp lực",
        type: "text",
        field: "pressure",
      },
      {
        label: "Loài cạnh tranh",
        type: "text",
        field: "competing_species",
      },
      {
        label: "Loài cộng sinh",
        type: "text",
        field: "symbiotic_species",
      },
      {
        label: "Loài được bảo vệ",
        type: "text",
        field: "protected",
      },
      {
        label: "Loài đang bảo vệ",
        type: "text",
        field: "other_porotectec",
      },
      {
        label: "Biện pháp bảo tồn",
        type: "text",
        field: "protective_measures",
      },
      {
        label: "Lợi ích kinh tế",
        type: "text",
        field: "benefit",
      },
      {
        label: "Khác",
        type: "text",
        field: "other",
      },
    ],
    dactinhhanhvi: {
      items: [
        { value: 1, text: "Định cư" },
        { value: 2, text: "Di cư" },
        { value: 3, text: "Quần đàn" },
        { value: 4, text: "Đơn độc" },
      ],
    },
    phanbodiaphuong: {
      items: [
        { value: 1, text: "Phổ biến" },
        { value: 2, text: "Có hạn" },
        { value: 3, text: "Hạn hữu" },
      ],
    },
    nguongoc: {
      items: [
        { value: 1, text: "Bản địa" },
        { value: 2, text: "Tự nhiên hoang dã" },
        { value: 3, text: "Bán hoang dã" },
        { value: 4, text: "Cư trú" },
        { value: 5, text: "Du nhập" },
        { value: 6, text: "Xâm lấn" },
        { value: 7, text: "Di cư" },
        { value: 8, text: "Nhập nội" },
        { value: 9, text: "Lai" },
        { value: 10, text: "Chưa biết" },
      ],
    },
  }),
  methods: {
    validateAll() {
      return Promise.all([
        this.$validator.validateAll(),
        this.$refs.dynamicForm.validateAll(),
      ]).then((res) => res.every((e) => !!e));
    },
  },
};
</script>

<style></style>
