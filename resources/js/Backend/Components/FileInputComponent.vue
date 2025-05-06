<template>
  <div>
    <div
    :id="id + 'ImagePreview'"
    :class="'image-preview-box '+parentCls"
    src="http://via.placeholder.com/350x150"
    alt=""
  >
    <img v-if="prvImage" :src="prvImage" style="width: 100%; height: 100%; object-fit: cover" />
  </div>
  <input
    type="file"
    :id="id"
    class="form-control"
    style="display: none !important"
    @input="handleFileChange"
    :multiple="isMultiple"
    :required="isRequired"
  />
  </div>
</template>

<script>
export default {
  props: {
    id: {
      type: String,
      required: true,
    },
    prvImage: {
      type: String,
      default: "",
    },
    isMultiple: {
      type: Boolean,
      default: false,
    },
    parentCls: {
      type:String,
      default:""
    },
    modelValue: {
      default: [],
    },
    isRequired: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      model: this.modelValue,
    };
  },
  mounted() {
    let self = this;

    $("#" + self.id + "ImagePreview").click(function () {
      $("#" + self.id).click();
    });

                $("#" + self.id + "ImagePreview").css(
              "background-image",
              ""
            );

    $(function () {
      $("#" + self.id).on("change", function () {
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

        if (/^image/.test(files[0].type)) {
          // only image file
          var reader = new FileReader(); // instance of the FileReader
          reader.readAsDataURL(files[0]); // read the local file

          reader.onloadend = function () {
            // set image data as background of div
            $("#" + self.id + "ImagePreview").css(
              "background-image",
              "url(" + this.result + ")"
            );
          };
        } else {
          $("#" + self.id + "ImagePreview").css(
            "background-image",
            "url('/images/file-upload-success.png')"
          );
        }
      });
    });
  },
  computed: {
    setImage() {
      return this.prvImage;
    },
  },
  methods: {
    handleFileChange(e) {
      // Whenever the file changes, emit the 'input' event with the file data.
      this.$emit(
        "update:modelValue",
        this.isMultiple ? e.target.files : e.target.files[0]
      );
    },
  },
  watch: {
    model(currentValue) {
      this.$emit("update:modelValue", currentValue);
    },
    modelValue(currentValue) {
      this.model = currentValue;
    },
  },
};
</script>

<style>
.image-preview-box {
  width: 80px;
  height: 80px;
  cursor: pointer;
  border: 2px dashed #d3d3d3;
  background-position: center center;
  background-size: cover !important;
  background-repeat: no-repeat;
  background-size: 65px 65px;
  display: inline-block;
  background-image: url("/images/file-upload.png");
}
</style>