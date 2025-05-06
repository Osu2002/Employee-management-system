<template>
  <Head title="Mail Settings" />
  <AppLayout>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header">
            <h5>Mail Settings</h5>
            <p>Configure Mail settings.</p>
          </div>
          <div class="card-body">
            <form id="formAccountSettings" @submit.prevent="submit">
              <div class="row">
                <div class="mb-3 col-md-6">
                  <label for="from_address" class="form-label"
                    >From Address</label
                  >
                  <input
                    class="form-control"
                    type="text"
                    id="from_address"
                    name="from_address"
                    v-model="form.from_address"
                  />
                  <div class="text-danger">
                    {{ form.errors.from_address }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="from_name" class="form-label">From Name</label>
                  <input
                    class="form-control"
                    type="text"
                    name="from_name"
                    id="from_name"
                    v-model="form.from_name"
                  />
                  <div class="text-danger">
                    {{ form.errors.from_name }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="method" class="form-label">Mail Method</label>
                  <select
                    class="select2 form-control form-select"
                    id="method"
                    aria-label="Default select example"
                    v-model="form.method"
                  >
                    <option selected value="">-- Select --</option>
                    <option
                      v-for="mailMethod in mailMethods"
                      :key="mailMethod.id"
                      :value="mailMethod.id"
                    >
                      {{ mailMethod.name }}
                    </option>
                  </select>
                  <div class="text-danger">
                    {{ form.errors.method }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="host" class="form-label">SMTP Host</label>
                  <input
                    class="form-control"
                    type="text"
                    name="host"
                    id="host"
                    v-model="form.host"
                  />
                  <div class="text-danger">
                    {{ form.errors.host }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="port" class="form-label">SMTP Port</label>
                  <input
                    class="form-control"
                    type="text"
                    name="port"
                    id="port"
                    v-model="form.port"
                  />
                  <div class="text-danger">
                    {{ form.errors.port }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="username" class="form-label">SMTP Username</label>
                  <input
                    class="form-control"
                    type="text"
                    name="username"
                    id="username"
                    v-model="form.username"
                  />
                  <div class="text-danger">
                    {{ form.errors.username }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="password" class="form-label">SMTP Password</label>
                  <input
                    class="form-control"
                    type="text"
                    name="password"
                    id="password"
                    v-model="form.password"
                  />
                  <div class="text-danger">
                    {{ form.errors.password }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="encryption" class="form-label"
                    >Mail Encryption</label
                  >
                  <select
                    class="select2 form-control form-select"
                    id="encryption"
                    aria-label="Default select example"
                    v-model="form.encryption"
                  >
                    <option selected value="">-- Select --</option>
                    <option
                      v-for="mailEncryprion in mailEncryprions"
                      :key="mailEncryprion.id"
                      :value="mailEncryprion.id"
                    >
                      {{ mailEncryprion.name }}
                    </option>
                  </select>
                  <div class="text-danger">
                    {{ form.errors.encryption }}
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

export default {
  components: {
    Head,
    AppLayout,
  },
  props: {
    mailEncryprions: Object,
    mailMethods: Object,
    mailSettings: Object,
  },
  data() {
    return {
      form: useForm({
        from_address: "",
        from_name: "",
        method: "",
        host: "",
        port: "",
        username: "",
        password: "",
        encryption: "",
      }),
    };
  },
  mounted() {
    let self = this;
    if (this.mailSettings) {
      this.form.from_address = this.mailSettings.from_address;
      this.form.from_name = this.mailSettings.from_name;
      this.form.method = this.mailSettings.method;
      this.form.host = this.mailSettings.host;
      this.form.port = this.mailSettings.port;
      this.form.username = this.mailSettings.username;
      this.form.password = this.mailSettings.password;
      this.form.encryption = this.mailSettings.encryption;
    }
    $(".card-body").on("change", "#method", function (evt) {
      self.form.method = $(evt.target).val();
    });
    $(".card-body").on("change", "#encryption", function (evt) {
      self.form.encryption = $(evt.target).val();
    });
  },
  methods: {
    submit() {
      this.form.post(route("settings.mail.update"), {
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