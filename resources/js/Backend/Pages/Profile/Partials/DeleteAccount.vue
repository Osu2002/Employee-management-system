<template>
  <div class="card">
    <h5 class="card-header">Delete Account</h5>
    <div class="card-body">
      <div class="mb-3 col-12 mb-0">
        <div class="alert alert-warning">
          <h6 class="alert-heading fw-bold mb-1">
            Are you sure you want to delete your account?
          </h6>
          <p class="mb-0">
            Once you delete your account, there is no going back. Please be
            certain.
          </p>
        </div>
      </div>
      <div class="form-check mb-3">
        <input
          class="form-check-input"
          type="checkbox"
          name="accountDelete"
          id="accountDelete"
          v-model="isConfirmed"
        />
        <label class="form-check-label" for="accountDelete"
          >I confirm my account deletion</label
        >
      </div>
      <button
        type="submit"
        class="btn btn-danger delete-account"
        data-bs-toggle="modal"
        data-bs-target="#deleteAccount"
        :disabled="!isConfirmed"
      >
        Delete Account
      </button>
    </div>
    <!-- model -->
    <div
      class="modal modal-top fade"
      id="deleteAccount"
      tabindex="-1"
      aria-hidden="true"
      data-bs-backdrop="false"
    >
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header px-3 pb-2">
            <h5 class="modal-title" id="exampleModalLabel2">
              Enter Your Password
            </h5>
          </div>
          <form id="formAccountDelete" @submit.prevent="deleteUser">
            <div class="modal-body py-0 px-3">
              <div class="row">
                <div class="col mb-1">
                  <input
                    type="password"
                    id="confirmPassword"
                    class="form-control"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    v-model="form.current_password"
                  />
                  <div class="text-danger">{{ form.errors.current_password }}</div>
                </div>
              </div>
            </div>
            <div class="modal-footer px-3 pb-3">
              <button
                type="button"
                class="btn btn-sm btn-label-secondary"
                data-bs-dismiss="modal"
              >
                Close
              </button>
              <button type="submit" class="btn btn-sm btn-primary">
                Confirm
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- model -->
  </div>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";
export default {
  data() {
    return {
      isConfirmed: false,
      form: useForm({ current_password: "" }),
    };
  },
  methods: {
    deleteUser() {
      this.form.post(route("profile.delete"), {
        preserveScroll: true,
        errorBag: "deleteProfile",
        onStart:() => {
          },
        onSuccess: () => {
          $("#deleteAccount").modal("hide");
          this.form.reset("current_password");
          this.$root.showMessage(
            "success",
            '<span class="text-success">Success</span><br/>',
            "Profile Deleted Successfully! "
          );
        },
        onFinish: () => this.form.reset("current_password"),
      });
    },
  },
};
</script>

<style>
</style>