<template>
  <Head title="Reset Password - " />
  <AuthLayout>
    <AuthFormHeader
      image=""
      title="Reset Password"
      subtitle="for john.doe@email.com"
    />

    <form
      id="formAuthentication"
      class="mb-3"
      @submit.prevent="submit"
    >
      <div class="mb-3 form-password-toggle">
        <label class="form-label" for="password">New Password</label>
        <div class="input-group input-group-merge">
          <input
            :type="showPassword ? 'text' : 'password'"
            id="password"
            class="form-control"
            name="password"
            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
            aria-describedby="password"
            v-model="form.password"
          />
          <span class="input-group-text cursor-pointer" @click="togglePassword"
            ><i
              class="bx"
              v-bind:class="showPassword ? 'bx-show' : 'bx-hide'"
            ></i
          ></span>
        </div>
      </div>
      <div class="mb-3 form-password-toggle">
        <label class="form-label" for="confirm-password"
          >Confirm Password</label
        >
        <div class="input-group input-group-merge">
          <input
            :type="showPassword ? 'text' : 'password'"
            id="confirm-password"
            class="form-control"
            name="confirm-password"
            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
            aria-describedby="password"
            v-model="form.password_confirmation"
          />
          <span class="input-group-text cursor-pointer" @click="togglePassword"
            ><i
              class="bx"
              v-bind:class="showPassword ? 'bx-show' : 'bx-hide'"
            ></i
          ></span>
        </div>
      </div>
      <button class="btn btn-main d-grid w-100 mb-3">
        Set new password
      </button>
      <div class="text-center">
        <Link :href="route('login')">
          <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
          Back to login
        </Link>
      </div>
    </form>
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
    email: String,
    token: String,
  },
  data() {
    return {
      form: useForm({
        token: this.token,
        email: this.email,
        password: "",
        password_confirmation: "",
      }),
      showPassword: false,
    };
  },
  methods: {
    submit() {
      this.form.post(route("password.update"), {
        onFinish: () => this.form.reset("password", "password_confirmation"),
      });
    },
        togglePassword() {
      if (this.showPassword) {
        this.showPassword = false;
      } else {
        this.showPassword = true;
      }
    },
  },
};
</script>