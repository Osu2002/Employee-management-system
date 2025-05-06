<template>
  <AppLayout>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header pb-0">
            <h5>Permissions</h5>
            <p>Manage User Roles & Permissions.</p>
          </div>
          <hr />
          <div class="card-body">
            <form class="form-validate" @submit.prevent="submit">
              <table
                id="mytable"
                class="
                  display
                  table table-responsive table-striped table-hover
                  text-nowrap
                "
              >
                <thead>
                  <tr>
                    <th class="text-left" width="150px">Name</th>
                    <th width="14%">Select All</th>
                    <th>Permissions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(section, index) in allPermissions" :key="index">
                    <td class="text-left text-capitalize">
                      {{ index[0].toUpperCase() + index.slice(1).replace('-', " ") }}
                    </td>
                    <td class="text-center">
                      <input
                        class="form-check-input perm_checkbox_all"
                        style="cursor: pointer"
                        type="checkbox"
                        :id="'checkbox_' + index.replaceAll(' ', '')"
                        @click="toggleCheckboxes(index.replaceAll(' ', ''))"
                      />
                    </td>
                    <td>
                      <div class="row">
                        <div
                          class="col-3 my-2"
                          v-for="perm in section"
                          :key="perm.id"
                        >
                          <input
                            :class="
                              'form-check-input me-2 perm_checkbox ' +
                              index.replaceAll(' ', '')
                            "
                            :id="perm.name"
                            style="cursor: pointer"
                            type="checkbox"
                            v-model="form.permissions"
                            :value="perm.name"
                          />
                          <label
                            class="form-check-label text-capitalize"
                            :for="perm.name"
                            style="font-size: 13px; cursor: pointer"
                          >
                            {{ perm.name.replace(index + ".", "") }}
                          </label>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="mt-4" v-if="$root.hasPermission('roles-permissions.edit')">
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
import { Link, useForm } from "@inertiajs/inertia-vue3";

import AppLayout from "@/Layouts/AppLayout.vue";

export default {
  components: {
    Link,
    AppLayout,
  },

  props: {
    errors:Object,
    role: Object,
    allPermissions: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      form: useForm({
        id: null,
        permissions: [],
      }),
    };
  },
  mounted() {
    if (this.role) {
      this.form.id = this.role.id;
      this.checkCheckboxes();
    }
  },
  methods: {
    submit() {
      if (this.form.permissions.length == 0) {
        this.$root.showMessage(
          "error",
          '<span class="text-danger">Error</span><br>',
          "No permission selected!"
        );
      } else {
        this.form.post(
          route("settings.roles.permissions.update"),
          {
            onSuccess: () => {
              this.resetForm();
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
      }
    },
    resetForm() {
      var self = this; //you need this because *this* will refer to Object.keys below`

      //Iterate through each object field, key is name of the object field`
      Object.keys(this.$data.form).forEach(function (key, index) {
        self.$data.form[key] = "";
      });
      Object.keys(this.$props.errors).forEach(function (key, index) {
        self.$props.errors[key] = "";
      });
    },

    toggleCheckboxes(section_name) {
      let parent_checkbox = document.querySelector(`#checkbox_${section_name}`);
      let checkboxes = document.querySelectorAll(
        `.perm_checkbox.${section_name}`
      );

      for (let checkbox of checkboxes) {
        checkbox.checked = parent_checkbox.checked;

        if (checkbox.checked) {
          // console.log(this.form.permissions.indexOf(checkbox.value) )
          if (this.form.permissions.indexOf(checkbox.value) === -1) {
            this.form.permissions.push(checkbox.value);
          }
          // console.log(this.form.permissions);
        } else {
          let index = this.form.permissions.indexOf(checkbox.value);
          this.form.permissions.splice(index, 1);
          // console.log(this.form.permissions);
        }
      }
    },

    checkCheckboxes() {
      let checkboxes = document.querySelectorAll(`.perm_checkbox`);

      for (let checkbox of checkboxes) {
        for (let perm of this.role.permissions) {
          // console.log(perm.name, checkbox.value, checkbox.value === perm.name);
          if (checkbox.value === perm.name) {
            checkbox.checked = true;

            // console.log(this.form.permissions.indexOf(checkbox.value) )

            if (this.form.permissions.indexOf(checkbox.value) === -1) {
              this.form.permissions.push(checkbox.value);
            }
          }
        }
      }

      console.log(this.form.permissions);
    },
  },
};
</script>
