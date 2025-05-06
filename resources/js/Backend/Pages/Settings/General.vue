<template>
  <Head title="General Settings" />
  <AppLayout>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header">
            <h5>General Settings</h5>
            <p>Configure general site settings.</p>
          </div>
          <div class="card-body">
            <form id="formAccountSettings" @submit.prevent="submit">
              <div class="row">
                <div class="mb-3 col-md-6">
                  <label for="app_name" class="form-label">App Name</label>
                  <input
                    class="form-control"
                    type="text"
                    id="app_name"
                    name="app_name"
                    v-model="form.app_name"
                  />
                  <div class="text-danger">
                    {{ form.errors.app_name }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="app_description" class="form-label"
                    >App Description</label
                  >
                  <input
                    class="form-control"
                    type="text"
                    name="app_description"
                    id="app_description"
                    v-model="form.app_description"
                  />
                  <div class="text-danger">
                    {{ form.errors.app_description }}
                  </div>
                </div>
                <div class="mb-3 col-md-6 d-flex align-items-center">
                  <label for="email" class="form-label me-3">App Favicon</label>
                  <FileInputComponentVue
                    id="app_favicon"
                    :prvImage="appFavicon"
                    v-model="form.app_favicon"
                  />
                  <div class="text-danger">
                    {{ form.errors.app_favicon }}
                  </div>
                </div>
                <div class="mb-3 col-md-6 d-flex align-items-center">
                  <label for="email" class="form-label me-3">App Logo</label>
                  <FileInputComponentVue
                    id="app_logo"
                    :prvImage="appLogo"
                    v-model="form.app_logo"
                  />
                  <div class="text-danger">
                    {{ form.errors.app_logo }}
                  </div>
                </div>
              </div>
              <div class="mt-2" v-if="$root.hasPermission('system-setting.edit')">
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
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import FileInputComponentVue from "../../Components/FileInputComponent.vue";

export default {
  components: {
    Head,
    AppLayout,
    FileInputComponentVue,
  },
  props: {
    settings: Object,
  },
  data() {
    return {
      form: useForm({
        app_name: "",
        app_description: "",
        app_favicon: "",
        app_logo: "",
      }),
    };
  },
  mounted() {
    console.log("");
    if (this.settings) {
      // this.settings.map((s) => {
      //   this.form[s.key] = s.value;
      // });

      // reset image fields
      this.form.app_name= this.settings.app_name,
      this.form.app_description= this.settings.app_description,
      this.form.app_favicon= "",
      this.form.app_logo = "";
    }
  },
  methods: {
    submit() {
      this.form.post(route('settings.general.update'), {
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
  computed: {
    appFavicon() {
      return this.settings.app_favicon
    },
    // appLogo() {
    //   return this.settings
    //     .filter((i) => i.key == "app_logo")
    //     .map((s) => s.value)[0];
    // },
  },
};
</script>

<style>
</style>