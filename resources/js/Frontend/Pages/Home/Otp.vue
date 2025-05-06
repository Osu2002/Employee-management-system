<template>
  <AppLayout>
    <div class="container-fluid wrapper d-flex align-items-center justify-content-center">
      <div class="row w-100 content">
        <div class="col-lg-5 col-md-12 d-flex justify-content-center align-items-center p-5">
          <div class="form-container">
            <form class="form-signin" id="formAccountSettings" @submit.prevent="submit">
              <h4 class="form-signin-heading">Enter OTP</h4>
              
              <input
                type="text"
                class="form-control mb-3"
                name="otp"
                v-model="form.otp"
                placeholder="XXXXXX"
                required
              />
              <p style="font-size: smaller;">OTP has been sent to <strong>{{ email }}</strong>. Please enter it below to proceed.</p>
              <button class="btn btn-lg btn-danger btn-block" type="submit">Submit</button>
              <Link class="btn btn-lg btn-danger btn-block" :href="route('index')">Cancel</Link>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from "@@/Layouts/AppLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import Swal from "sweetalert2";

export default {
  components: {
    Link,
    AppLayout
  },
  props: {
    id: Number,
    email: String
  },
  data() {
    return {
      form: useForm({
        emp_id: this.id,
        otp: ""
      })
    };
  },
  methods: {
    submit() {
      this.form.get(route("validateotp"));
    }
  }
};
</script>

<style scoped>
body {
  background: #eee !important;
  margin: 0;
  padding: 0;
}

.wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

#bg-video {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  z-index: -1;
}

.background-image {
  display: none;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url("/images/background-image.avif") no-repeat center center;
  background-size: cover;
  z-index: -1;
}

.intro-back {
  background-image: url("/images/nick-wessaert-JI01fn0U7Cg-unsplash-1-scaled-1.webp");
  background-size: cover;
  background-position: center;
}

.background-overlay {
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.623);
  z-index: 0;
}

.content {
  width: 100%;
  display: flex;
  justify-content: center;
}

.form-container {
  background-color: rgba(235, 235, 235, 0.9);
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  max-width: 380px;
  width: 100%;
}

.text-container {
  color: #fff;
}

@media (max-width: 768px) {
  #bg-video {
    display: none;
  }

  .background-image {
    display: block;
  }

  .form-container {
    width: 90%;
  }
}

.service-box {
  background-color: #f5f5f5;
  border: 1px solid #e3e3e3;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 20px;
  text-align: center;
}

.service-box img {
  height: 50px;
  margin-bottom: 20px;
}
</style>
