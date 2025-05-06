<template>
  <div class="card">
    <h5 class="card-header">Change Password</h5>
    <div class="card-body">
      <form id="formAccountSettings" @submit.prevent="updatePassword">
        <div class="row">
          <div class="mb-3 col-md-6 form-password-toggle">
            <label class="form-label" for="currentPassword"
              >Current Password</label
            >
            <div class="input-group input-group-merge">
              <input
                class="form-control"
                type="password"
                name="currentPassword"
                id="currentPassword"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                v-model="form.current_password"
              />
              <span class="input-group-text cursor-pointer"
                ><i class="bx bx-hide"></i
              ></span>
            </div>
            <div class="text-danger">{{ form.errors.current_password }}</div>
          </div>
        </div>
        <div class="row">
          <div class="mb-3 col-md-6 form-password-toggle">
            <label class="form-label" for="newPassword">New Password</label>
            <div class="input-group input-group-merge">
              <input
                class="form-control"
                type="password"
                id="newPassword"
                name="newPassword"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                v-model="form.password"
              />
              <span class="input-group-text cursor-pointer"
                ><i class="bx bx-hide"></i
              ></span>
            </div>
            <div class="text-danger">{{ form.errors.password }}</div>
          </div>

          <div class="mb-3 col-md-6 form-password-toggle">
            <label class="form-label" for="confirmPassword"
              >Confirm New Password</label
            >
            <div class="input-group input-group-merge">
              <input
                class="form-control"
                type="password"
                name="confirmPassword"
                id="confirmPassword"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                v-model="form.password_confirmation"
              />
              <span class="input-group-text cursor-pointer"
                ><i class="bx bx-hide"></i
              ></span>
            </div>
            <div class="text-danger">
              {{ form.errors.password_confirmation }}
            </div>
          </div>
          <div class="col-12 mt-1">
            <button
              type="submit"
              class="btn btn-main me-2"
              :disabled="form.processing"
            >
              Update
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";

export default {
  data() {
    return {
      form: useForm({
        current_password: "",
        password: "",
        password_confirmation: "",
      }),
    };
  },
  methods: {
    updatePassword() {
      this.form.put(route("profile.update-password"), {
        errorBag: "updatePassword",
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset(
            "password",
            "password_confirmation",
            "current_password"
          );
            this.$root.showMessage(
              "success",
              '<span class="text-success">Success</span><br/>',
              "Password Successfully! "
            );
        },
        onError: () => {
          if (this.form.errors.password) {
            this.form.reset("password", "password_confirmation");
            // passwordInput.value.focus();
          }

          if (this.form.errors.current_password) {
            this.form.reset("current_password");
            // currentPasswordInput.value.focus();
          }
        },
      });
    },
  },
};
</script>

<style>
</style>