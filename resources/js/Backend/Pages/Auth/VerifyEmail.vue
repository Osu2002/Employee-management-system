<template>
  <Head title="Email Verification - " />
  <AuthLayout>
    <AuthFormHeader
      image=""
      title="Email Verification"
      subtitle="Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another."
    />
    <form id="formAuthentication" class="mb-3" @submit.prevent="submit">
      <div v-if="verificationLinkSent" class="mb-4 text-success">
        A new verification link has been sent to the email address you provided
        during registration.
      </div>
      <button class="btn btn-main w-100 my-3" :disabled="form.processing">
        Send Verification Email
      </button>
      <p class="text-center">
        
        <Link :href="route('logout')" method="post"> Logout </Link>
      </p>
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
    status: String,
  },
  data() {
    return {
      form: useForm(),
    };
  },
  methods: {
    submit() {
      this.form.post(route("verification.send"));
    },
  },
  computed: {
    verificationLinkSent() {
      return this.status === "verification-link-sent";
    },
  },
};
</script>