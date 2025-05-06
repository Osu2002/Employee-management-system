<template>
  <Head title="Two Steps Verification - " />
  <AuthLayout>
    <AuthFormHeader
      image=""
      title="Two Steps Verification"
      :subtitle="
        'We sent a verification code to your ' +
        type +
        '. Enter the code from the ' +
        type +
        ' in the field below.'
      "
    />

    <form id="twoStepsForm" @submit.prevent="submit">
      <div class="mb-3">
        <div
          class="
            auth-input-wrapper
            d-flex
            align-items-center
            justify-content-sm-between
            numeral-mask-wrapper
          "
        >
          <input
            type="text"
            class="
              disable-number-arrow
              form-control
              auth-input
              h-px-50
              text-center
              numeral-mask
              text-center
              h-px-50
              mx-1
              my-2
            "
            maxlength="1"
            autofocus
           v-model="otp1"
          />
          <input
            type="text"
            class="
              disable-number-arrow
              form-control
              auth-input
              h-px-50
              text-center
              numeral-mask
              text-center
              h-px-50
              mx-1
              my-2
            "
            maxlength="1"
            v-model="otp2"
          />
          <input
            type="text"
            class="
              disable-number-arrow
              form-control
              auth-input
              h-px-50
              text-center
              numeral-mask
              text-center
              h-px-50
              mx-1
              my-2
            "
            maxlength="1"
            v-model="otp3"
          />
          <input
            type="text"
            class="
              disable-number-arrow
              form-control
              auth-input
              h-px-50
              text-center
              numeral-mask
              text-center
              h-px-50
              mx-1
              my-2
            "
            maxlength="1"
            v-model="otp4"
          />
          <input
            type="text"
            class="
              disable-number-arrow
              form-control
              auth-input
              h-px-50
              text-center
              numeral-mask
              text-center
              h-px-50
              mx-1
              my-2
            "
            maxlength="1"
           v-model="otp5"
          />
          <input
            type="test"
            class="
              disable-number-arrow
              form-control
              auth-input
              h-px-50
              text-center
              numeral-mask
              text-center
              h-px-50
              mx-1
              my-2
            "
            maxlength="1"
            v-model="otp6"
          />
        </div>
        <div class="text-danger">{{ form.errors.otp }}</div>
      </div>
      <button class="btn btn-main d-grid w-100 mb-3" :disabled="form.processing">
        Verify
      </button>
            <p class="text-center">
        
        <Link :href="route('logout')" method="post"> Logout </Link>
      </p>
      <div class="text-center">
        Didn't get the code?
        <a href="javascript:void(0);"> Resend </a>
      </div>
    </form>
  </AuthLayout>
</template>

<script>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import AuthFormHeader from "./Partials/AuthFormHeader.vue";

export default {
  components: {
    Head,
    Link,
    AuthLayout,
    AuthFormHeader,
  },
  props: {
    type: {
      type: String,
      default: "mobile",
    },
  },
  data() {
    return {
      otp1:"",
      otp2:"",
      otp3:"",
      otp4:"",
      otp5:"",
      otp6:"",
      form:useForm({
        otp:""
      })
    };
  },
  mounted() {
    let maskWrapper = document.querySelector(".numeral-mask-wrapper");

    for (let pin of maskWrapper.children) {
      pin.onkeyup = function (e) {
        // While entering value, go to next
        if (pin.nextElementSibling) {
          if (
            this.value.length === parseInt(this.attributes["maxlength"].value)
          ) {
            pin.nextElementSibling.focus();
          } else {
            return 
          }
        }

        // While deleting entered value, go to previous
        // Delete using backspace and delete
        if (pin.previousElementSibling) {
          if (e.keyCode === 8 || e.keyCode === 46) {
            pin.previousElementSibling.focus();
          }
        }
      };
    }
  },
  computed: {
    getOtp() {
      return this.otp1+this.otp2+this.otp3+this.otp4+this.otp5+this.otp6;
    }
  },
  methods: {
      submit() {
        this.form.otp = this.getOtp;
        this.form.post(route('2fa.verify'), {
          preserveScroll:true,
          onFinish:() => this.form.reset('otp'),
        })
      }
  }
};
</script>

<style>
</style>