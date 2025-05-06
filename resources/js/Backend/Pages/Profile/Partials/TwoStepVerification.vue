<template>
  <div class="card mb-4">
    <h5 class="card-header">Two-steps verification</h5>
    <div class="card-body">
      <p class="fw-semibold mb-3">
        Two factor authentication is not enabled yet.
      </p>
      <p class="w-75">
        Two-factor authentication adds an additional layer of security to your
        account by requiring more than just a password to log in.
      </p>
      <form @submit.prevent="enableTwoFactor">
      <div class="form-check mb-3">
        <input
          class="form-check-input"
          type="checkbox"
          name="2femail"
          id="2femail"
          v-model="forEmail"
        />
        <label class="form-check-label" for="2femail">Enable for e-mail</label>
        <div class="mb-3 col-md-4">
          <input
            class="form-control"
            type="email"
            name="email"
            id="email"
            placeholder="Enter your email"
            v-if="forEmail"
            v-model="form.tf_email"
          />
        </div>
      </div>
      <div class="form-check mb-3">
        <input
          class="form-check-input"
          type="checkbox"
          name="2famobile"
          id="2famobile"
          v-model="forMobile"
        />
        <label class="form-check-label" for="2famobile"
          >Enable for mobile number</label
        >
        <div class="mb-3 col-md-4">
          <input
            class="form-control disable-number-arrow"
            type="number"
            name="mobile"
            id="mobile"
            placeholder="Enter your mobile number"
            v-if="forMobile"
            v-model="form.tf_mobile"
          />
        </div>
      </div>
      <button
        class="btn btn-main mt-2"
        :disabled="!forEmail && !forMobile"
      >
        Enable two-factor authentication
      </button>
      </form>
    </div>
  </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
export default {
  data() {
    return {
      forEmail: false,
      forMobile: false,
      form: useForm({
        tf_email: "",
        tf_mobile: "",
      })
    };
  },
  methods: {
    enableTwoFactor() {
      if((this.forEmail && this.form.tf_email != '') || (this.forMobile && this.form.tf_mobile != '')) {
        console.log('true')
      } else {
        console.log('false')

      }
    }
  }
};
</script>

<style>
</style>