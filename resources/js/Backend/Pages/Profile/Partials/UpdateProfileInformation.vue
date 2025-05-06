<template>
  <div class="card-body">
    <form id="formAccountSettings" @submit.prevent="updateProfileInfo">
      <div class="row">
        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Name</label>
          <input
            class="form-control"
            type="text"
            id="name"
            name="name"
            v-model="form.name"
          />
          <div class="text-danger">
            {{ form.errors.name }}
          </div>
        </div>
        <div class="mb-3 col-md-6">
          <label for="email" class="form-label">E-mail Address</label>
          <input
            class="form-control"
            type="email"
            name="email"
            id="email"
            v-model="form.email"
          />
          <div class="text-danger">
            {{ form.errors.email }}
          </div>
        </div>
        <div class="mb-3 col-md-6">
          <label for="email" class="form-label">Mobile No.</label>
          <input
            class="form-control disable-number-arrow"
            type="number"
            id="mobile_no"
            name="mobile_no"
            v-model="form.mobile_no"
          />
          <div class="text-danger">
            {{ form.errors.mobile_no }}
          </div>
        </div>
      </div>
      <div class="mt-2">
        <button
          type="submit"
          class="btn btn-main me-2"
          :disabled="form.processing"
        >
          Update
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";
export default {
  props: {
  },
  data() {
    return {
      form: useForm({
        name: "",
        email: "",
        mobile_no: "",
      }),
    };
  },
  mounted() {
    let user = this.$page.props.user;
    this.form.name = user.name ?? '';
    this.form.email = user.email ?? '';
    this.form.mobile_no = user.mobile_no ?? '';
  },
  methods: {
    updateProfileInfo() {
      this.form.put(route("profile.update-info"), {
        errorBag: "updateProfileInformation",
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
            "Profile Information Updated Successfully! "
          ); 
        },
        onError: () => {},
      });
    },
  },
};
</script>

<style>
</style>