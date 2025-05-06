<template>
  <Head title="Activate Profile -" />
  <AuthLayout>
    <AuthFormHeader
      image=""
      title="Activate Profile"
      subtitle="Enter subtitle"
    />
    <button
      class="btn btn-main w-100 my-3"
      data-bs-toggle="modal"
      data-bs-target="#activateAccount"
      
    >
      Activate Profile
    </button>
    <p class="text-center">
      <Link
        :href="route('logout')"
        method="post"
        class="btn btn-link"
        as="button"
      >
        Logout
      </Link>
    </p>
  </AuthLayout>
  <!-- model -->
  <div
    class="modal modal-top fade"
    id="activateAccount"
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
        <form id="formAccountDelete" @submit.prevent="submit">
          <div class="modal-body py-0 px-3">
            <div class="row">
              <div class="col mb-1">
                <input
                  type="password"
                  id="confirmPassword"
                  class="form-control"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  v-model="form.current_password"
                  autofocus
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
      form: useForm({ current_password: "" }),
    };
  },
  methods: {
    submit() {
      this.form.post(route("profile.activate"), {
        preserveScroll: true,
        errorBag: "activateAccount",
        onStart: () => {
            $("#activateAccount").modal("hide");
        },
        onSuccess: () => {
          this.form.reset("current_password");
          this.$root.showMessage(
            "success",
            '<span class="text-success">Success</span><br/>',
            "Profile Activated Successfully! "
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