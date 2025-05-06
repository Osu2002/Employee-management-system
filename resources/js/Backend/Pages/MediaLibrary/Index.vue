<template>
  <div>
    <Head title="Media Library" />
    <AppLayout>
      <div class="row">
        <div class="col-md-12">
          <div class="card mb-4">
            <div class="card-header">
              <h5>Media Library</h5>
              <p>Manage Your All Media.</p>
              <!-- <br> -->
              <div class="d-flex">
                <button
                  class="btn btn-sm btn-link"
                  @click="gotoCollection('default')"
                  v-if="form.c != 'default'"
                >
                  <i class="bx bx-left-arrow-alt" style="margin-top: -2px"></i>Back
                </button>

                <button
                  class="btn btn-sm btn-main ms-3"
                  data-bs-toggle="modal"
                  data-bs-target="#PreviewUploadMedia"
                  v-if="$root.hasPermission('media.create')"
                >
                  <i class="bx bxs-cloud-upload"></i> Upload Media
                </button>
              </div>
            </div>

            <div class="card-body" style="min-height: 50vh">
              <div class="row">
                <div class="col-12">
                  <div class="media-library-wrapper">
                    <div class="row">
                      <!-- collections -->
                      <div
                        class="col-6 col-sm-4 col-md-3 collections"
                        v-for="collection in collections.filter(
                        (c) => c.name != 'default'
                      )"
                        :key="collection.name"
                        @dblclick="gotoCollection(collection.name)"
                      >
                        <div class="card border overflow-hidden">
                          <div
                            class="card-body p-0"
                            data-bs-toggle="tooltip"
                            data-bs-offset="0,4"
                            data-bs-placement="right"
                            data-bs-html="false"
                            :title="collection.name ?? ''"
                          >
                            <i class="bx bxs-folder"></i>
                          </div>
                          <div class="card-footer p-0 p-2">
                            <div class="image-name text-capitalize">{{ collection.name ?? "" }}</div>
                          </div>
                        </div>
                      </div>
                      <!-- media -->
                      <div
                        class="col-6 col-sm-4 col-md-3 media"
                        v-for="media in allMedia"
                        :key="media.id"
                      >
                        <div class="card border">
                          <div class="card-body p-0" :title="media.name ?? ''">
                            <div class="preview rounded-top" style="overflow: hidden">
                              <img :src="media.original_url" style="width: 100%; object-fit:cover;" :alt="media.name" />
                            </div>
                          </div>
                          <div class="card-footer p-0 p-2 position-relative">
                            <div
                              class="image-name text-capitalize"
                            >{{ limitChars(media.name ?? "", 12) }}</div>
                            <div
                              class="image-name text-capitalize"
                              style="font-size: 12px"
                            >{{ humanReadableSize(media.size ?? 0) }}</div>
                            <button
                              type="button"
                              class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow media-download"
                              data-bs-toggle="dropdown"
                              aria-expanded="false"
                            >
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                              <li>
                                <a
                                  class="dropdown-item"
                                  :href="
                                  route('media.download', { id: media.id })
                                "
                                >
                                  <i class="bx bx-show"></i> Preview
                                </a>
                              </li>
                              <li>
                                <a
                                  class="dropdown-item"
                                  :href="
                                  route('media.download', { id: media.id })
                                "
                                >
                                  <i class="bx bxs-download"></i> Download
                                </a>
                              </li>
                              <li>
                                <a
                                  href="javascript:void(0);"
                                  class="dropdown-item"
                                  data-bs-toggle="modal"
                                  data-bs-target="#deleteMedia"
                                  @click="deleteMediaPrompt(media.id, media.name)"
                                >
                                  <i class="bx bx-trash"></i> Delete
                                </a>
                              </li>
                            </ul>
                            <!-- <a :href="route('media.download', {id: media.id})" class="media-download">
                            <i class="bx bxs-download"></i>
                            </a>-->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- model -->
            <div
              class="modal modal-top fade"
              id="deleteMedia"
              tabindex="-1"
              aria-hidden="true"
              data-bs-backdrop="false"
            >
              <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                  <div class="modal-header px-3 pb-2">
                    <h5 class="modal-title" id="exampleModalLabel2">Are You Sure?</h5>
                  </div>
                  <form id="formAccountDelete" @submit.prevent="deleteMedia">
                    <div class="modal-body py-0 px-3">
                      <div class="row">
                        <div class="col mb-1">
                          <p>Are you sure you want to delete "{{ media.name }}"?</p>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer px-3 pb-3">
                      <button
                        type="button"
                        class="btn btn-sm btn-label-secondary"
                        data-bs-dismiss="modal"
                        @click="resetform"
                      >Close</button>
                      <button type="submit" class="btn btn-sm btn-primary">Yes</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- model -->

            <!-- file upload model -->
            <div
              class="modal fade"
              id="PreviewUploadMedia"
              tabindex="-1"
              aria-hidden="true"
              data-bs-backdrop="false"
            >
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header pt-2">
                    <h5 class="modal-title" id="modalCenterTitle">Upload media</h5>
                  </div>
                  <div class="modal-body py-2">
                    <div class="row">
                      <FileInputComponent
                        id="file_upload"
                        parentCls="w-100 upload-height"
                        v-model="media.file"
                      />
                    </div>
                  </div>
                  <div class="modal-footer pb-2">
                    <button
                      type="button"
                      class="btn btn-label-secondary"
                      data-bs-dismiss="modal"
                      @click="resetform"
                    >Close</button>
                    <button type="button" class="btn btn-main" @click="uploadMedia">Upload</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- file upload model -->
          </div>
        </div>
      </div>
    </AppLayout>
  </div>
</template>

<script>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import FileInputComponent from "@/Components/FileInputComponent.vue";

export default {
  components: {
    Head,
    AppLayout,
    FileInputComponent
  },
  props: {
    collections: Object,
    allMedia: Object
  },
  data() {
    return {
      form: {
        c: "default"
      },
      media: useForm({
        id: "",
        name: "",
        file: ""
      })
    };
  },
  mounted() {
    const urlParams = new URLSearchParams(window.location.search);
    const param = urlParams.get("c");
    this.form.c = param;

    $("#file_uploadImagePreview").css("background-image", "");
  },
  methods: {
    limitChars(string, length) {
      return string.length > length
        ? string.substring(0, length - 3) + "..."
        : string;
    },
    humanReadableSize(size) {
      let sizeInBytes = size;
      let sizeInKiloBytes = size / 1024;
      if (sizeInBytes < 1024) {
        return sizeInBytes.toFixed(2) + "B";
      } else if (sizeInKiloBytes < 1024) {
        return sizeInKiloBytes.toFixed(2) + "KB";
      } else {
        return (sizeInKiloBytes / 1024).toFixed(2) + "MB";
      }
    },
    setImage(image) {
      return "background-image:url(" + image + ")";
    },
    gotoCollection(name) {
      this.form.c = name;
      this.$inertia.reload({
        preserveScroll: false,
        data: { ...this.form }
      });
    },
    deleteMediaPrompt(id, name) {
      this.media.id = id;
      this.media.name = name;
    },
    deleteMedia() {
      this.media.post(route("media.delete"), {
        preserveScroll: true,
        onSuccess: () => {
          $("#deleteMedia").modal("hide");
          this.resetform();
          this.$root.showMessage(
            "success",
            '<span class="text-success">Success</span><br/>',
            "Profile Deleted Successfully! "
          );
        },
        onFinish: () => {
          $("#deleteMedia").modal("hide");
          this.resetform();
        }
      });
    },
    resetform() {
      this.media.reset("id", "name", "file");
    },
    uploadMedia() {
      this.media
        .transform(data => ({
          ...data,
          collection: this.form.c
        }))
        .post(route("media.upload"), {
          forceFormData: true,
          preserveScroll: true,
          onSuccess: () => {
            this.resetform();
            $("#PreviewUploadMedia").modal("hide");
            this.$root.showMessage(
              "success",
              '<span class="text-success">Success</span><br/>',
              "File Uploaded Successfully! "
            );
          },
          onFinish: () => {
            $("#deleteMedia").modal("hide");
            this.resetform();
            $("#file_uploadImagePreview").css("background-image", "");
          }
        });
    }
  }
};
</script>

<style>
.upload-height {
  height: 250px;
  background-size: contain !important;
}
</style>