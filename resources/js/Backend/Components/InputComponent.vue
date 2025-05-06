<template>
  <div :class="className">
    <label :for="id" class="form-label"
      >{{ label }} <sup v-if="isRequired">*</sup></label
    >
    <input
      class="form-control"
      :type="type"
      :id="id"
      :placeholder="placeholder != '' ? placeholder : label"
      v-model="model"
      :step="step"
      :min="min"
      :disabled="disabled"
      :required="isRequired"
    />
    <div class="text-danger">
      {{ error }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    id: {
      type: String,
      required: true,
    },
    className: {
      type: String,
      default: "mb-3 col-md-6",
    },
    label: {
      type: String,
      required: true,
    },
    type: {
      type: String,
      default: "text",
    },
    placeholder: {
      type: String,
      default: "",
    },
    error: {
      type: String,
      default: "",
    },
    isRequired: {
      type: Boolean,
      default: true,
    },
    step: {
      type: Number,
      default: 1,
    },
    min: {
      type: String,
      default: "",
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    modelValue: {
      default: "",
    },
  },
  data() {
    return {
      model: this.modelValue,
    };
  },
  watch: {
    model(currentValue) {
      this.$emit("update:modelValue", currentValue);
    },
    modelValue(currentValue) {
      this.model = currentValue;
    },
  },
};
</script>

<style></style>
