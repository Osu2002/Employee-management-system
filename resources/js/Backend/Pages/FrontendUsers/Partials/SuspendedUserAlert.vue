<template>
  <div class="alert alert-danger d-flex" role="alert">
    <span
      class="
        badge badge-center
        rounded-pill
        bg-danger
        border-label-danger
        p-3
        me-2
      "
      ><i class="bx bxs-user-x bx-sm"></i
    ></span>
    <div class="d-flex flex-column ps-1">
      <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">
        This Account Has Been Suspended!
      </h6>
      <span
        >If you want to active this user? - <b>Click "Restore" Button</b></span
      >
    </div>
    <button
      class="btn btn-danger btn-sm align-self-center ms-auto"
      @click.prevent="setActionType('restore')"
      data-bs-toggle="modal"
      data-bs-target="#confirmPasword"
    >
      Restore User
    </button>
    <button
      class="btn btn-dark btn-sm align-self-center ms-2"
      @click.prevent="setActionType('delete')"
      data-bs-toggle="modal"
      data-bs-target="#confirmPasword"
    >
      Delete User
    </button>
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
        <form id="formAccountDelete" @submit.prevent="submit">
          <div class="modal-body py-0 px-3">
            <div class="row">
              <div class="col mb-1">
                <input
                  type="password"
                  id="confirmPassword"
                  class="form-control"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  v-model="form.confirm_password"
                />
                <input type="hidden" v-model="form.actionType" />
                <div class="text-danger">
                  {{ form.errors.confirm_password }}
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer px-3 pb-3">
            <button
              type="button"
              class="btn btn-sm btn-label-secondary"
              data-bs-dismiss="modal"
              @click="resetForm"
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
import { useForm } from "@inertiajs/inertia-vue3";
export default {
  props: {
    userId: {
      default: "",
    },
  },
  data() {
    return {
      form: useForm({
        id: this.userId,
        confirm_password: "",
        actionType: "",
      }),
    };
  },
  methods: {
    setActionType(type) {
      this.form.actionType = type;
    },
    submit() {
      if (this.form.actionType == "delete") {
        this.deleteUser();
      } else if (this.form.actionType == "restore") {
        this.restoreUser();
      }
    },
    restoreUser() {
      this.form.post(route("settings.users.restore"), {
        errorBag: "restoreUser",
        onSuccess: () => {
          // $("#confirmPasword").modal("hide");
          $("#confirmPasword").hide();
          if ($("body").hasClass("modal-open")) {
            $("body").removeClass("modal-open").attr("style", "");
          }
          this.resetForm();
          this.$root.showMessage(
            "success",
            '<span class="text-success">Success</span><br/>',
            "User Restored Successfully! "
          );
        },
        onFinish: () => {
          this.form.reset("confirm_password");
        },
      });
    },
    deleteUser() {
      this.form.post(route("settings.users.remove"), {
        errorBag: "deleteUser",
        onSuccess: () => {
          // $("#confirmPasword").modal("hide");
          $("#confirmPasword").hide();
          if ($("body").hasClass("modal-open")) {
            $("body").removeClass("modal-open").attr("style", "");
          }
          this.resetForm();
          this.$root.showMessage(
            "success",
            '<span class="text-success">Success</span><br/>',
            "User Deleted Successfully! "
          );
        },
        onFinish: () => {
          this.form.reset("confirm_password");
        },
      });
    },
    resetForm() {
      this.form.reset("confirm_password", "actionType");
    },
  },
};
</script>

<style>
</style>