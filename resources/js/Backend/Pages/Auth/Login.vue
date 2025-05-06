<template>
  <Head title="Login" />
  <AuthLayout>
    <AuthFormHeader
      image="/images/logo-dark.png"
      :title="'Welcome to Dashboard'"
    />

    <form id="formAuthentication" class="mb-3" @submit.prevent="submit">
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input
          type="email"
          class="form-control"
          id="email"
          name="email-username"
          placeholder="Enter your email address"
          v-model="form.email"
          required
          autofocus
          autocomplete="email"
        />
      </div>
      <div class="mb-3 form-password-toggle">
        <div class="d-flex justify-content-between">
          <label class="form-label" for="password">Password</label>
          <Link :href="route('password.request')" class="text-muted">
            <small>Forgot Password?</small>
          </Link>
        </div>
        <div class="input-group input-group-merge">
          <input
            :type="showPassword ? 'text' : 'password'"
            id="password"
            class="form-control"
            name="password"
            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
            aria-describedby="password"
            v-model="form.password"
            required
            autofocus
            autocomplete="current-password"
          />
          <span class="input-group-text cursor-pointer" @click="togglePassword"
            ><i
              class="bx"
              v-bind:class="showPassword ? 'bx-show' : 'bx-hide'"
            ></i
          ></span>
        </div>
      </div>
      <div class="mb-3">
        <div class="form-check">
          <input
            class="form-check-input"
            type="checkbox"
            id="remember-me"
            v-model="form.remember"
          />
          <label class="form-check-label" for="remember-me">
            Remember Me
          </label>
        </div>
      </div>
      <div class="mb-3">
        <button
          class="btn btn-main d-grid w-100"
          type="submit"
          :disabled="form.processing"
        >
          Sign in
        </button>
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
    canResetPassword: Boolean,
    status: String,
  },
  data() {
    return {
      form: this.$inertia.form({
        email: "",
        password: "",
        remember: false,
      }),
      showPassword: false,
    };
  },
  methods: {
    submit() {
      this.form
        .transform((data) => ({
          ...data,
          remember: this.form.remember ? "on" : "",
        }))
        .post(this.route("login"), {
          onFinish: () => this.form.reset("password"),
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