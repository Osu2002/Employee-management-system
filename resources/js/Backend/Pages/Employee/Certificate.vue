<template>
  <AppLayout>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header pb-0">
            <div class="col-12 mb-4">
              <div class="row">
                <div class="col-1">
                  <Link class="back-button" :href="route('employee.edit', employee.id)">
                  <i class="back-icon fas fa-arrow-left p-2"></i>
                  </Link>
                </div>
                <div class="col-11 d-flex justify-content-end">
                  <h5 class="m-0 text-center">{{ employee.name }}</h5>
                </div>

              </div>
            </div>
            <h5>Employee Certificate</h5>
            <p>Download Intern Certificate</p>
            <button type="button" class="btn btn-danger" @click="downloadCertificate">Download</button>
            <h1></h1>
          </div>
          <div id="certificate" class="certificate">
            <div
              style="background-image: url(/backend/assets/images/fdfdfdf.jpg); background-size:contain;background-repeat:no-repeat;background-position:center center">
              <img style="margin-top: 15px;" src="/backend/assets/images/Weblook-300x65.png" alt="">
              <h1><b>INTERNSHIP CERTIFICATE</b></h1>
              <p class="subheading">This certificate is awarded to</p>
              <h2 class="mb-0">{{ employee.name }}</h2>
              <p>-----------------------------------------------------------------------------------------</p>
              <p class="details">for his excellent performance during his internship period</p>
              <p class="details">at Weblook International (Pvt) Ltd,</p>
              <p class="details">from {{ formattedJoinDate }} to {{ formattedEndDate }}</p>

              <div class="qr-code">
                <img :src="qrCodeImageUrl" alt="QR Code" />
              </div>

              <div class="signature">
                <div class="left">
                  <p class="mb-0">-----------------------------</p>
                  <p>Date</p>
                </div>
                <div class="right">
                  <p class="mb-0">-----------------------------</p>
                  <p class="mb-0">Viraj Cabral</p>
                  <p class="title">Managing Director</p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </AppLayout>
</template>


<script>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import QRCode from "qrcode";
import html2pdf from 'html2pdf.js';

export default {
  components: {
    Link,
    AppLayout
  },
  props: {
    errors: Object,
    employee: Object,
    employeeUrl: Object,
  },
  data() {
    return {
      form: useForm({
        id: "",
        name: "",
        email: "",
        designation: "",
        join_date: "",
        end_date: "",
        status: "",
        profile_image: ""
      }),
      qrCodeData: "",
      qrCodeImageUrl: ""
    };
  },
  mounted() {
    this.generateQRCode();
    if (this.employee) {
      this.form.id = this.employee.id;
      this.form.name = this.employee.name;
      this.form.email = this.employee.email;
      this.form.designation = this.employee.designation;
      this.form.join_date = this.employee.join_date;
      this.form.end_date = this.employee.end_date;
      this.form.status = this.employee.status;
    }
  },
  computed: {
    profileImage() {
      return this.employee ? this.employee.media.length > 0 ? this.employee.media[0].original_url : "" : "";
    },
    formattedJoinDate() {
      return this.formatDate(this.employee.join_date);
    },
    formattedEndDate() {
      return this.formatDate(this.employee.end_date);
    }
  },
  methods: {
    // Function to generate QR code
    generateQRCode() {
      const qrData = JSON.stringify(this.employeeUrl);
      this.qrCodeData = qrData;
      // Generate QR code image URL
      QRCode.toDataURL(qrData, { width: 180, height: 150 })
        .then(url => {
          this.qrCodeImageUrl = url;
        })
        .catch(err => {
          console.error("Error generating QR code:", err);
        });
    },
    downloadCertificate() {
      const element = document.getElementById('certificate');
      const opt = {
        margin: 0,
        filename: 'internship_certificate.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
      };
      html2pdf().from(element).set(opt).save().then(() => {
        console.log('PDF downloaded successfully');
      }).catch(err => {
        console.error('Error downloading PDF:', err);
      });
    },
    formatDate(dateString) {
      const date = new Date(dateString);
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return date.toLocaleDateString(undefined, options);
    },
    submit() {
      this.form.post(
        this.employee
          ? route("employee.update")
          : route("employee.store")
      );
    }
  }
};
</script>

<style>
.certificate {
  width: 11.69in;
  /* A4 width in inches for landscape */
  height: 8.27in;
  /*A4 height in inches for landscape*/
  /* padding: 50px; */
  text-align: center;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  /* background-image: url("/backend/assets/images/background.png"); */
  background-size: cover;
  /* margin-left: 1px; */
}

.certificate>div {
  margin: 20px;
  /* border: 1px solid #000; */
  padding: 30px;
}

.certificate h1 {
  font-size: 3.5em;
  margin-top: 0.7em;
  color: rgb(130, 129, 129);
}

.certificate .subheading {
  color: red;
  font-size: 1.5em;
  margin-top: 1em;
}

.certificate h2 {
  font-size: 2.5em;
  margin: 1.5em 0;
  color: #1b334e;
}

.certificate .details {
  color: rgb(64, 63, 63);
  margin: 0.5em 0;
  font-size: 1.2em;
}

.qr-code {
  margin: 0em 0;
}

.qr-code img {
  width: 80px;
  height: 80px;
}

.signature {
  display: flex;
  justify-content: space-between;
  margin-top: 3em;
  margin-bottom: 1em;
}

.signature .left,
.signature .right {
  width: 45%;
}

.signature .right .title {
  color: rgb(64, 63, 63);
}
</style>
