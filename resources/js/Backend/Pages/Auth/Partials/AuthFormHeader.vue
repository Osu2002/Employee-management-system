<template>
  <!-- Logo -->
  <div class="app-brand justify-content-center mb-1">
    <a href="" class="app-brand-link gap-2">
      <span class="app-brand-logo">
        <!-- <img :src="image" :alt="title" style="width:250px;" /> -->
      </span>
    </a>
  </div>
  <!-- /Logo -->
  <h4 class="mb-2 text-center">{{ title }}</h4>
  <p class="mb-4 text-center">{{ subtitle }}</p>
</template>

<script>
export default {
  props: {
    image: {
      type: String,
      default: "",
    },
    title: {
      type: String,
      default: "",
    },
    subtitle: {
      type: String,
      default: "",
    },
  },
};
</script>

<style>
</style>