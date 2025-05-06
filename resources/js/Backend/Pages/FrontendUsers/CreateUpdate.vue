<template>
  <AppLayout>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header pb-0">
            <h5>Users</h5>
            <p>Manage Users.</p>
            <button class="btn btn-main" v-if="frontendUser" @click.prevent="viewHistory(frontendUser.id)">View Customer History</button>
          </div>
          <hr />
          <div class="card-body">
            <form id="formAccountSettings" @submit.prevent="submit">
              <div class="row">
                <div class="mb-3 col-md-6">
                  <label for="name" class="form-label">Full Name</label>
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
                  <label for="email" class="form-label">Email</label>
                  <input
                    class="form-control"
                    type="email"
                    id="email"
                    name="email"
                    v-model="form.email"
                  />
                  <div class="text-danger">
                    {{ form.errors.email }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="phone" class="form-label">Mobile No</label>
                  <input
                    class="form-control"
                    type="phone"
                    id="phone"
                    name="phone"
                    v-model="form.phone"
                  />
                  <div class="text-danger">
                    {{ form.errors.phone }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="dob" class="form-label">Date of birth</label>
                  <input
                    class="form-control"
                    type="date"
                    id="dob"
                    name="dob"
                    v-model="form.dob"
                  />
                  <div class="text-danger">
                    {{ form.errors.dob }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">

                </div>
                <div class="mb-3 col-md-6" v-if="!frontendUser">
                  <label for="password" class="form-label">Password</label>
                  <input
                    class="form-control"
                    type="password"
                    id="password"
                    name="password"
                    v-model="form.password"
                  />
                  <div class="text-danger">
                    {{ form.errors.password }}
                  </div>
                </div>
                <div class="mb-3 col-md-6" v-if="!frontendUser">
                  <label for="confirm_password" class="form-label"
                    >Confirm Password</label
                  >
                  <input
                    class="form-control"
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    v-model="form.password_confirmation"
                  />
                  <div class="text-danger">
                    {{ form.errors.password }}
                  </div>
                </div>
                <!-- <div class="mb-3 col-md-6">
                  <label for="avatar" class="form-label me-3"
                    >Profile Image</label
                  >
                  <br />
                  <FileInputComponent
                    id="avatar"
                    :prvImage="profileImage"
                    v-model="form.avatar"
                    :isRequired="false"
                  />
                  <div class="text-danger">
                    {{ form.errors.avatar }}
                  </div>
                </div> -->
              </div>
              <div class="mt-2">
                <button
                  type="submit"
                  class="btn btn-main me-2"
                  :disabled="form.processing"
                >
                  {{ frontendUser ? "Update" : "Save" }}
                </button>
                                <Link class="btn btn-outline-danger" :href="route('customer.index')">Cancel</Link>
              </div>
            </form>
          </div>
        </div>

        <div class="card" v-if="frontendUser && frontendUser.deleted_at == null">
          <div class="card-header pb-0">
            <h5>Update Password</h5>
          </div>
          <div class="card-body">
            <form id="formAccountSettings" @submit.prevent="updatePasssword">
              <div class="row">
                <div class="mb-3 col-md-6" v-if="frontendUser">
                  <label for="password" class="form-label">Password</label>
                  <input
                    class="form-control"
                    type="password"
                    id="password"
                    name="password"
                    v-model="password.password"
                  />
                  <div class="text-danger">
                    {{ password.errors.password }}
                  </div>
                </div>
                <div class="mb-3 col-md-6" v-if="frontendUser">
                  <label for="confirm_password" class="form-label"
                    >Confirm Password</label
                  >
                  <input
                    class="form-control"
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    v-model="password.password_confirmation"
                  />
                  <div class="text-danger">
                    {{ password.errors.password }}
                  </div>
                </div>
              </div>
              <div class="mt-2">
                <button
                  type="submit"
                  class="btn btn-main me-2"
                  data-bs-toggle="modal"
                  data-bs-target="#confirmPasword"
                  :disabled="
                    form.processing ||
                    !(
                      password.password.length > 0 &&
                      password.password_confirmation.length > 0
                    )
                  "
                >
                  Update
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- model -->
      <div
        class="modal modal-top fade"
        id="confirmPasword"
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
            <form id="formAccountDelete" @submit.prevent="changePassword">
              <div class="modal-body py-0 px-3">
                <div class="row">
                  <div class="col mb-1">
                    <input
                      type="password"
                      id="confirmPassword"
                      class="form-control"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      v-model="password.confirm_password"
                    />
                    <div class="text-danger">
                      {{ password.errors.confirm_password }}
                    </div>
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
  </AppLayout>
</template>


<script>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import FileInputComponent from "@/Components/FileInputComponent.vue";
import SuspendedUserAlert from "./Partials/SuspendedUserAlert.vue";

export default {
  components: {
    Link,
    AppLayout,
    FileInputComponent,
    SuspendedUserAlert,
  },

  props: {
    errors: Object,
    allRoles: Object,
    frontendUser: Object,
  },

  data() {
    return {
      form: useForm({
        id: "",
        name: "",
        dob: "",
        email: "",
        phone: "",
        password: "",
        password_confirmation: "",
        avatar: "",
      }),
      password: useForm({
        id: "",
        confirm_password: "",
        password: "",
        password_confirmation: "",
      }),
    };
  },
  mounted() {
    let self = this;

    if (this.frontendUser) {
      this.form.id = this.frontendUser.id;
      this.form.name = this.frontendUser.name;
      this.form.dob = this.frontendUser.dob;
      this.form.email = this.frontendUser.email;
      this.form.phone = this.frontendUser.phone;

    }
  },
  computed: {
    profileImage() {
      return this.frontendUser ? this.frontendUser.avatar ?? "" : "";
    },
  },
  methods: {
    submit() {
      this.form.post(
        this.frontendUser
          ? route("customer.update")
          : route("customer.store"),
        {
          onSuccess: () => {
            this.form.reset();
            this.$root.showMessage(
              "success",
              '<span class="text-success">Success</span><br/>',
              "A Record Was Created Successfully! "
            );
          },
          onError: () => {
            this.form.reset("password", "password_confirmation");
            this.$root.showMessage(
              "error",
              '<span class="text-danger">Error</span><br>',
              "Something went wrong! "
            );
          },
        }
      );
    },
    changePassword() {
      this.password
        .transform((data) => ({
          ...data,
          id: this.form.id,
        }))
        .put(route("customer.change.password"), {
          preserveScroll: true,
          errorBag: "changePassword",
          onSuccess: () => {
            $("#confirmPasword").modal("hide");
            this.password.reset(
              "confirm_password",
              "password",
              "password_confirmation"
            );
            this.$root.showMessage(
              "success",
              '<span class="text-success">Success</span><br/>',
              "A Record Was Created Successfully! "
            );
          },
          onError: () => {
            this.password.reset(
              "confirm_password",
              "password",
              "password_confirmation"
            );
            this.$root.showMessage(
              "error",
              '<span class="text-danger">Error</span><br>',
              "Something went wrong! "
            );
          },
          onFinish: () => {
            this.password.reset(
              "confirm_password",
              "password",
              "password_confirmation"
            );
          },
        });
    },
    viewHistory($id) {
      this.$inertia.get(route('customer.view', {
        id: $id
      }
        ));
    }
  },
};
</script>
