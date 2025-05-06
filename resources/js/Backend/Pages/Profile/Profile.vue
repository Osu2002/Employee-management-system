<template>
<div>
  <Head title="Profile" />
  <AppLayout>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <h5 class="card-header">Profile Details</h5>

          <UploadProfilePhoto v-if="$root.hasPermission('profile.updatePhoto')" />

          <hr class="my-0" />

          <UpdateProfileInformation v-if="$root.hasPermission('profile.updateInfo')" />

        </div>
        <UpdatePassword class="mb-4" v-if="$root.hasPermission('profile.updatePassword')"/>
        <!-- <TwoStepVerification class="mb-4" v-if="$root.hasPermission('profile.2faAuth')"/>
          <DeactivateAccount class="mb-4" v-if="$root.hasPermission('profile.deactivate')"/>
          <DeleteAccount v-if="$root.hasPermission('profile.delete')"/> -->
      </div>
    </div>
  </AppLayout>
</div>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import UploadProfilePhoto from "./Partials/UploadProfilePhoto.vue";
import UpdateProfileInformation from "./Partials/UpdateProfileInformation.vue";
import UpdatePassword from "./Partials/UpdatePassword.vue";
import TwoStepVerification from "./Partials/TwoStepVerification.vue";
import DeactivateAccount from "./Partials/DeactivateAccount.vue";
import DeleteAccount from "./Partials/DeleteAccount.vue";
export default {
  components: {
    Head,
    AppLayout,
    UploadProfilePhoto,
    UpdateProfileInformation,
    UpdatePassword,
    TwoStepVerification,
    DeactivateAccount,
    DeleteAccount,
  },
};
</script>

<style>
</style>