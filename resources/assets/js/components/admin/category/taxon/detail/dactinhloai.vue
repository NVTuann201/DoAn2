<template>
  <div class="">
    <div class="row">
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label">
          Tên khoa học
          <span class="red-dot">*</span></label
        >
        <input
          v-model="form.scientific_name"
          class="form-control"
          v-validate="'required'"
          name="tenkhoahoc"
        />
        <span
          v-show="errors.has('tenkhoahoc')"
          class="help is-danger"
          style="color: red"
          >Tên không thể bỏ trống</span
        >
      </div>

      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Kiểu tên khoa học (Tên đồng danh) </label>
        <input v-model="form.scientific_name_style" class="form-control" />
      </div>

      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Tên chấp nhận </label>
        <input v-model="form.accepted_name_usage" class="form-control" />
      </div>

      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Tên cấp bậc gần nhất </label>
        <input v-model="form.parent_name_usage" class="form-control" />
      </div>

      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label">Tên gốc</label>
        <input v-model="form.original_name_usage" class="form-control" />
      </div>

      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Định danh tên khoa học </label>
        <input v-model="form.scientific_name_id" class="form-control" />
      </div>

      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Tên xuất bản </label>
        <input v-model="form.name_published_in" class="form-control" />
      </div>

      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Năm xuất bản </label>
        <input
          v-model="form.name_published_in_year"
          class="form-control"
          type="number"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
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
  methods: {
    validateAll() {
      return this.$validator.validateAll();
    },
  },
};
</script>

<style></style>
