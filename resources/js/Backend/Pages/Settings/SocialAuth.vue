<template>
  <Head title="Social Auth Settings" />
  <AppLayout>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header">
            <h5>Social Auth Settings</h5>
            <p>Configure Social Auth settings.</p>
          </div>
          <hr />
          <div class="card-body">
            <form id="formAccountSettings" @submit.prevent="submit">
              <h5>Facebook</h5>
              <div class="row">
                <div class="mb-3 col-md-6">
                  <label for="facebook_id" class="form-label"
                    >Facebook Id</label
                  >
                  <input
                    class="form-control"
                    type="text"
                    id="facebook_id"
                    name="facebook_id"
                    v-model="form.facebook_id"
                  />
                  <div class="text-danger">
                    {{ form.errors.facebook_id }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="facebook_secret" class="form-label"
                    >Facebook Secret</label
                  >
                  <input
                    class="form-control"
                    type="text"
                    name="facebook_secret"
                    id="facebook_secret"
                    v-model="form.facebook_secret"
                  />
                  <div class="text-danger">
                    {{ form.errors.facebook_secret }}
                  </div>
                </div>
                <h5 class="mt-4">Google</h5>
                <div class="mb-3 col-md-6">
                  <label for="google_id" class="form-label">Google Id</label>
                  <input
                    class="form-control"
                    type="text"
                    name="google_id"
                    id="google_id"
                    v-model="form.google_id"
                  />
                  <div class="text-danger">
                    {{ form.errors.google_id }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="google_secret" class="form-label"
                    >Google Secret</label
                  >
                  <input
                    class="form-control"
                    type="text"
                    name="google_secret"
                    id="google_secret"
                    v-model="form.google_secret"
                  />
                  <div class="text-danger">
                    {{ form.errors.google_secret }}
                  </div>
                </div>
              </div>
              <div class="mt-2" v-if="$root.hasPermission('system-setting.edit')">
                <button
                  type="submit"
                  class="btn btn-main me-2"
                  :disabled="form.processing || (form.facebook_id == '' && form.google_id == '')"
                >
                  Update
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

export default {
  components: {
    Head,
    AppLayout,
  },
  props: {
    settings: Object,
  },
  data() {
    return {
      form: useForm({
        facebook_id: "",
        facebook_secret: "",
        google_id: "",
        google_secret: "",
      }),
    };
  },
  mounted() {
    if (this.settings) {
      this.form.facebook_id = this.settings.facebook_id;
      this.form.facebook_secret = this.settings.facebook_secret;
      this.form.google_id = this.settings.google_id;
      this.form.google_secret = this.settings.google_secret;
    }
  },
  methods: {
    submit() {
      this.form.post(route("settings.social-auth.update"), {
        preserveScroll: true,
        onSuccess: () => {
          this.$root.showMessage(
            "success",
            '<span class="text-success">Success</span><br/>',
            "Settings Updated Successfully! "
          );
        },
      });
    },
  },
};
</script>

<style>
</style>