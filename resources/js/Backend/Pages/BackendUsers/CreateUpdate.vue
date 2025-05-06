<template>
  <AppLayout>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header pb-0">
            <h5>Backend Users</h5>
            <p>Manage Backend Users.</p>
          </div>
          <hr />
          <div class="card-body">
            <SuspendedUserAlert v-if="backendUser ? backendUser.deleted_at : null" :userId="backendUser.id" />
            <form id="formAccountSettings" @submit.prevent="submit">
              <div class="row">
                <div class="mb-3 col-md-6">
                  <label for="name" class="form-label">Full Name</label>
                  <input class="form-control" type="text" id="name" name="name" v-model="form.name" />
                  <div class="text-danger">
                    {{ form.errors.name }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="email" class="form-label">Email</label>
                  <input class="form-control" type="email" id="email" name="email" v-model="form.email" />
                  <div class="text-danger">
                    {{ form.errors.email }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="mobile_no" class="form-label">Mobile No</label>
                  <input class="form-control" type="mobile_no" id="mobile_no" name="mobile_no"
                    v-model="form.mobile_no" />
                  <div class="text-danger">
                    {{ form.errors.mobile_no }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="role" class="form-label">Role</label>
                  <select class="select2 form-control form-select" id="role" aria-label="Default select example"
                    v-model="form.role">
                    <option selected value="">-- Select --</option>
                    <option v-for="r in allRoles" :key="r.id" :value="r.id">
                      {{ r.name }}
                    </option>
                  </select>
                  <div class="text-danger">
                    {{ form.errors.role }}
                  </div>
                </div>
                <!-- <div class="mb-3 col-md-6">
                  <label for="branch" class="form-label">Branch</label>
                  <select class="select2 form-control form-select" id="branch" aria-label="Default select example"
                    v-model="form.branch"> -->
                    <!-- <option selected value="">-- Select --</option> -->
                    <!-- <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                      {{ branch.name }}
                    </option>
                  </select>
                  <div class="text-danger">
                    {{ form.errors.branch }}
                  </div>
                </div> -->
                <!-- <div class="mb-3 col-md-6">
                  <label for="service_types" class="form-label">Prefered Services<sup>*</sup></label>
                  <select class="select2 form-control form-select" id="service_types"
                    aria-label="Default select example" v-model="form.service_types" :multiple="true"> -->
                    <!-- <option selected value="">-- Select --</option> -->
                    <!-- <option v-for="type in service_types" :key="type.id" :value="type.id">
                      {{ type.name }}
                    </option>
                  </select>
                  <div class="text-danger">
                    {{ form.errors.service_types }}
                  </div>
                </div> -->

                <div class="h5 my-4">Authentication</div>

                <div class="mb-3 col-md-6" v-if="!backendUser">
                  <label for="password" class="form-label">Password</label>
                  <input class="form-control" type="password" id="password" name="password" v-model="form.password" />
                  <div class="text-danger">
                    {{ form.errors.password }}
                  </div>
                </div>
                <div class="mb-3 col-md-6" v-if="!backendUser">
                  <label for="confirm_password" class="form-label">Confirm Password</label>
                  <input class="form-control" type="password" id="confirm_password" name="confirm_password"
                    v-model="form.password_confirmation" />
                  <div class="text-danger">
                    {{ form.errors.password }}
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="profile_image" class="form-label me-3">Profile Image</label>
                  <br />
                  <FileInputComponent :isRequired="false" id="profile_image" :prvImage="profileImage"
                    v-model="form.profile_image" />
                  <div class="text-danger">
                    {{ form.errors.profile_image }}
                  </div>
                </div>
              </div>
              <div class="mt-2">
                <button type="submit" class="btn btn-main me-2" :disabled="form.processing">
                  {{ backendUser ? "Update" : "Save" }}
                </button>
                <Link class="btn btn-outline-danger" :href="route('settings.users')">Cancel</Link>
              </div>
            </form>
          </div>
        </div>

        <div class="card" v-if="backendUser && backendUser.deleted_at == null">
          <div class="card-header pb-0">
            <h5>Update Password</h5>
          </div>
          <div class="card-body">
            <form id="formAccountSettings" @submit.prevent="updatePasssword">
              <div class="row">
                <div class="mb-3 col-md-6" v-if="backendUser">
                  <label for="password" class="form-label">Password</label>
                  <input class="form-control" type="password" id="password" name="password"
                    v-model="password.password" />
                  <div class="text-danger">
                    {{ password.errors.password }}
                  </div>
                </div>
                <div class="mb-3 col-md-6" v-if="backendUser">
                  <label for="confirm_password" class="form-label">Confirm Password</label>
                  <input class="form-control" type="password" id="confirm_password" name="confirm_password"
                    v-model="password.password_confirmation" />
                  <div class="text-danger">
                    {{ password.errors.password }}
                  </div>
                </div>
              </div>
              <div class="mt-2">
                <button type="submit" class="btn btn-main me-2" data-bs-toggle="modal" data-bs-target="#confirmPasword"
                  :disabled="form.processing ||
              !(
                password.password.length > 0 &&
                password.password_confirmation.length > 0
              )
              ">
                  Update
                </button>
                <Link class="btn btn-outline-danger" :href="route('settings.users')">Cancel</Link>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- model -->
      <div class="modal modal-top fade" id="confirmPasword" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
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
                    <input type="password" id="confirmPassword" class="form-control"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      v-model="password.confirm_password" />
                    <div class="text-danger">
                      {{ password.errors.confirm_password }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer px-3 pb-3">
                <button type="button" class="btn btn-sm btn-label-secondary" data-bs-dismiss="modal">
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
    backendUser: Object,
    branches: Array,
    service_types: Object
  },

  data() {
    return {
      form: useForm({
        id: "",
        name: "",
        email: "",
        mobile_no: "",
        role: "",
        // branch: "",
        password: "",
        password_confirmation: "",
        profile_image: "",
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

    if (this.backendUser) {
      this.form.id = this.backendUser.id;
      this.form.name = this.backendUser.name;
      this.form.email = this.backendUser.email;
      this.form.mobile_no = this.backendUser.mobile_no;
      this.form.role = this.backendUser.roles.length > 0 ? this.backendUser.roles[0].id : "";
      // this.form.branch = this.backendUser.branch.id;
    }
    $(".card-body").on("change", "#role", function (evt) {
      self.form.role = $(evt.target).val();
    });
    $(".card-body").on("change", "#branch", function (evt) {
      self.form.branch = $(evt.target).val();
    });
  },
  computed: {
    profileImage() {
      return this.backendUser ? this.backendUser.profile_photo_path ?? "" : "";
    },
  },
  methods: {
    submit() {
      this.form.post(
        this.backendUser
          ? route("settings.users.update")
          : route("settings.users.store"),
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
        .put(route("settings.users.change.password"), {
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
  },
};
</script>
