<template>
  <Head title="Forgot Password - " />
  <AuthLayout>
    <AuthFormHeader
      image=""
      title="Forgot Password?"
      subtitle="Enter your email and we'll send you instructions to reset your password"
    />

    <form
      id="formAuthentication"
      class="mb-3"
      @submit.prevent="submit"
    >
      <div v-if="status" class="mb-4 text-success">
        {{ status }}
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input
          type="text"
          class="form-control"
          id="email"
          name="email"
          placeholder="Enter your email"
          autofocus
          v-model="form.email"
        />
      </div>

      <button class="btn btn-main d-grid w-100" :disabled="form.processing">
        Send Reset Link
      </button>
    </form>
    <div class="text-center">
      <Link
        :href="route('login')"
        class="d-flex align-items-center justify-content-center"
      >
        <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
        Back to login
      </Link>
    </div>
  </AuthLayout>
</template>

<script>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import AuthFormHeader from "./Partials/AuthFormHeader.vue";

export default {
  components: {
    Head,
    Link,
    AuthLayout,
    AuthFormHeader,
  },
  props: {
    status: String,
  },
  data() {
    return {
      form: useForm({
        email: "",
      }),
    };
  },
  methods: {
    submit() {
      this.form.post(route("password.email"));
    },
  },
};
</script>