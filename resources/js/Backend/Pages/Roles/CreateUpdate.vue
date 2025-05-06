<template>
  <AppLayout>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header pb-0">
            <h5>Roles</h5>
            <p>Manage User Roles & Permissions.</p>
          </div>
          <hr>
          <div class="card-body">
            <form id="formAccountSettings" @submit.prevent="submit">
              <div class="row">
                <div class="mb-3 col-md-6">
                  <label for="role_name" class="form-label">Role Name</label>
                  <input
                    class="form-control"
                    type="text"
                    id="role_name"
                    name="role_name"
                    v-model="form.role_name"
                  />
                  <div class="text-danger">
                    {{ form.errors.role_name }}
                  </div>
                </div>
              </div>
              <div class="mt-2" v-if="$root.hasPermission('roles-permissions.create')">
                <button
                  type="submit"
                  class="btn btn-main me-2"
                  :disabled="form.processing"
                >
                 {{role ? 'Update' : 'Save'}}
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
import { Link, useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import SelectInputComponent from "@/Components/SelectInputComponent.vue";

export default {
  components: {
    Link,
    AppLayout,
    SelectInputComponent,
  },

  props: {
    errors: Object,
    role: Object,
    roleTypes: Object,
  },

  data() {
    return {
      form: useForm({
        id: "",
        role_name:"",
        type: "",
      }),
    };
  },
  mounted() {
    if (this.role) {
      this.form.id = this.role.id;
      this.form.role_name = this.role.name;
      this.form.type = this.role.type;
    }
  },
  methods: {
    submit() {
      this.form.post(
        this.role
          ? route("settings.roles.update")
          : route("settings.roles.store"),
        {
          onSuccess: () => {
            // this.form.reset();
            this.$root.showMessage(
              "success",
              '<span class="text-success">Success</span><br/>',
              "A Record Was Created Successfully! "
            );
          },
          onError: () => {
            this.$root.showMessage(
              "error",
              '<span class="text-danger">Error</span><br>',
              "Something went wrong! "
            );
          },
        }
      );
    },
  },
};
</script>
