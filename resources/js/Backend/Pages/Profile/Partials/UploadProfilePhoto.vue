<template>
  <div class="card-body">
    <div class="d-flex align-items-start align-items-sm-center gap-4">
      <img
        :src="prevImage ? prevImage : '/images/profile-avatar.png'"
        alt="user-avatar"
        class="d-block rounded"
        height="100"
        width="100"
        id="uploadedAvatar"
      />
      <div class="button-wrapper">
        <form>
          <label
            for="upload"
            class="btn btn-main btn-sm me-2 mb-2"
            tabindex="0"
          >
            <span class="d-none d-sm-block">Upload new photo</span>
            <i class="bx bx-upload d-block d-sm-none"></i>
            <input
              type="file"
              id="upload"
              class="account-file-input"
              hidden
              accept="image/png, image/jpeg"
              @input="handleFileChange"
            />
          </label>
        </form>
        <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
      </div>
    </div>
  </div>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";
export default {
  data() {
    return {
      form: useForm({
        _method: 'put',
        image: null,
      }),
      prevImage: null,
    };
  },
  mounted() {
    let self = this;
    this.prevImage = this.$page.props.user.profile_photo_path;

    $(function () {
      $("#upload").on("change", function () {
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

        if (/^image/.test(files[0].type)) {
          // only image file
          var reader = new FileReader(); // instance of the FileReader
          reader.readAsDataURL(files[0]); // read the local file

          reader.onloadend = function () {
            self.prevImage = this.result;
          };
        } else {
          self.prevImage = null;
        }
      });
    });
  },
  methods: {
    handleFileChange(e) {
      this.form.image = e.target.files[0];
      this.form.post(route("profile.update-photo"), {
        errorBag: "updateProfileInformation",
        preserveScroll: true,
        forceFormData:true,
        onSuccess: () => {
          this.form.reset("image");
          this.$root.showMessage(
            "success",
            '<span class="text-success">Success</span><br/>',
            "Profile Photo Updated Successfully! "
          );
        },
      });
    },
  },
};
</script>

<style>
</style>