<template>
  <div class="row">
    <slot />
    <div
      class="form-group"
      :class="field.class ? field.class : 'col-12 col-sm-6 col-md-4 col-lg-3'"
      v-for="field in fields"
      :key="field.field"
    >
      <label class="form-label"> {{ field.label }} </label>
      <span class="red-dot" v-if="field.required">*</span>
      <DynamicText
        v-if="['number', 'item', 'text'].includes(field.type)"
        v-model="form[field.field]"
        v-bind="field.meta"
        :type="field.type"
        :name="field.field"
        v-validate="getRule(field)"
        :data-vv-name="field.field"
        :data-vv-as="field.field"
      />
      <components
        v-else
        :is="getFieldRended(field)"
        v-model="form[field.field]"
        v-bind="field.meta"
        :name="field.field"
        v-validate="getRule(field)"
        :data-vv-name="field.field"
        :data-vv-as="field.field"
      />
      <div style="min-height: 2rem">
        <transition name="slide">
          <span
            v-if="field.required && errors.has(field.field)"
            class="help is-danger"
            style="color: red"
          >
            Trường {{ field.label }} yêu cầu nhập
          </span>
        </transition>
      </div>
    </div>
  </div>
</template>

<script>
const FIELDS = {
  select: "dynamic-select",
  radio: "dynamic-radio",
  boolean: "dynamic-boolean",
  date: "dynamic-date",
  datetime: "DynamicDateTime",
};
import DynamicSelect from "./fields/dynamic-select.vue";
import DynamicDate from "./fields/dynamic-date.vue";
import DynamicDateTime from "./fields/dynamic-datetime.vue";
import DynamicText from "./fields/dynamic-text.vue";
import DynamicRadio from "./fields/dynamic-radio.vue";
import DynamicBoolean from "./fields/dynamic-boolean.vue";
export default {
  components: {
    DynamicSelect,
    DynamicText,
    DynamicRadio,
    DynamicBoolean,
    DynamicDate,
    DynamicDateTime,
  },
  props: {
    value: { type: Object, default: () => ({}) },
    fields: { type: Array, default: () => [] },
  },
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
    getFieldRended(field) {
      return FIELDS[field.type] || "dynamic-text";
    },
    getRule(field) {
      let rules = "";
      if (field.required) {
        rules += "required";
      }
      return rules;
    },
    async validateAll() {
      return this.$validator.validateAll();
    },
  },
};
</script>

<style></style>
