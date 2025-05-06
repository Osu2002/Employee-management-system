<template>
  <Head title="Register -" />
  <AuthLayout>
    <AuthFormHeader
      image=""
      :title="'Welcome to ' + 'REPLACE' + ' Dashboard'"
    />

    <form id="formAuthentication" class="mb-3" @submit.prevent="submit">
      <div class="mb-3">
        <label for="fullname" class="form-label">Full Name</label>
        <input
          type="text"
          class="form-control"
          id="fullname"
          name="fullname"
          placeholder="Enter your full name"
          autofocus
          v-model="form.name"
        />
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input
          type="email"
          class="form-control"
          id="email"
          name="email"
          placeholder="Enter your email"
          v-model="form.email"
        />
      </div>
      <div class="mb-4 form-password-toggle">
        <label class="form-label" for="password">Password</label>
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
      <div class="mb-4 form-password-toggle">
        <label class="form-label" for="confirm-password">Confirm Password</label>
        <div class="input-group input-group-merge">
          <input
            :type="showPassword ? 'text' : 'password'"
            id="confirm-password"
            class="form-control"
            name="confirm-password"
            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
            aria-describedby="confirm-password"
            v-model="form.password_confirmation"
            required
          />
          <span class="input-group-text cursor-pointer" @click="togglePassword"
            ><i
              class="bx"
              v-bind:class="showPassword ? 'bx-show' : 'bx-hide'"
            ></i
          ></span>
        </div>
      </div>
      <button class="btn btn-main d-grid w-100">Sign up</button>
    </form>

    <p class="text-center">
      <span>Already have an account?</span>
      <Link :href="route('login')">
        <span> Sign in instead</span>
      </Link>
    </p>
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
  data() {
    return {
      form: useForm({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        terms: false,
      }),
      showPassword: false,
    };
  },
  methods: {
    submit() {
      this.form.post(route("register"), {
        onFinish: () => this.form.reset("password","password_confirmation"),
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

<style>
</style>