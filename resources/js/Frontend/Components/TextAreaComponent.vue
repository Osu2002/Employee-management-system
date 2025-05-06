<template>
<div class="mb-3 col-12">
  <div class="form-group">
      <label :for="id">{{ label }} <sup v-if="isRequired">*</sup> </label>
      <textarea class="form-control" :id="id" rows="5" :placeholder="label" v-model="model"></textarea>
      <div class="text-danger">{{ error }}</div>
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
    label: {
      type: String,
      required: true,
    },
    error: {
      type: String,
      default: "",
    },
    isRequired: {
      type: Boolean,
      default: true,
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

<style>
</style>