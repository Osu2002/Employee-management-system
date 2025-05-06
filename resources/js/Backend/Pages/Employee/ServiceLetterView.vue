<template>
    <AppLayout>
      <div class="row">
        <div class="col-md-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h5>Employee</h5>
              <p>Manage Employee Service Letter</p>
              <button
                type="button"
                class="btn btn-danger"
                @click="downloadCertificate"
              >
                Download
              </button>

              <Link
                    type="button"
                    :href="
                      route('employee.service_letter', form.employee_id)
                    "
                    class="btn btn-outline-danger mx-2"
                    style="margin-right: 10px"
                    >Edit</Link
                  >
              <form id="formAccountSettings" @submit.prevent="submit">
                <div
                  id="certificate"
                  class="container"
                  style="
                    background-image: url(/public/backend/assets/images/service-letter-background.png);" >
                  <!-- <div class="header">
                    <img src="/images/Weblook-300x65.png" alt="Company Logo" />
                    <h1>Weblook International Pvt Ltd</h1>
                    <p class="contact-info">
                      No 207/23, Dharmapala Mawatha, Colombo 7, Sri Lanka.
                    </p>
                  </div> -->
                  <div class="content">

                    <!-- <p>
                      Date : {{ formattedDate }}
                    </p>
                    <h1></h1>
                    <p>Dear {{ employee.name }},</p> -->

                    <!-- TinyMCE Editor -->
                    <div>
                      <p v-html="service_letter_data.letter"></p>
                    </div>
                  </div>
                    <div class="document-container">
    <div class="content">
      <!-- <div class="signature">
        <p>Sincerely,</p>
        <p>............................................</p>
        <p>Viraj Cabral<br>Managing Director</p>
      </div> -->
    </div>
    <div class="qr-code">
      <img :src="qrCodeImageUrl" alt="QR Code" />
      <!-- <p>More Details..</p> -->
    </div>
  </div>

                  <!-- <div class="footer"> -->
                    <!-- <p>
                      &copy; 2024 Weblook International Pvt Ltd. All rights
                      reserved.
                    </p> -->
                  <!-- </div> -->
                </div>

                <!-- <div class="mt-2"> -->
                  <!-- <Link
                    type="button"
                    :href="
                      route('employee.ServiceLetter.update', form.employee_id)
                    "
                    class="btn btn-outline-danger"
                    style="margin-right: 10px"
                    >Back</Link
                  > -->
                <!-- </div> -->
                <h1></h1>
              </form>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script>
  import { Link, useForm } from "@inertiajs/inertia-vue3";
  import AppLayout from "@/Layouts/AppLayout.vue";
  import html2pdf from "html2pdf.js";
  import Editor from "@tinymce/tinymce-vue";
  import QRCode from "qrcode";
  export default {
    components: {
      Link,
      AppLayout,
      Editor,
    },
    props: {
      errors: Object,
      employee: Object,
      employeeUrl: Object,
      service_letter_data: Object,
    },
    data() {
      return {
        form: useForm({
          id: "",
          editorContent: "",
          employee_id: this.employee.id,
        }),
        qrCodeData: "",
        qrCodeImageUrl: "",
      };
    },
    computed: {
      formattedDate() {
        const date = new Date();
        const options = { year: "numeric", month: "long", day: "numeric" };
        return date.toLocaleDateString("en-US", options);
      },
    },
    mounted() {
      if (this.service_letter_data) {
        this.form.id = this.employee.id;
        this.form.editorContent = this.service_letter_data.letter;
        if (this.employeeUrl) {
                  this.generateQRCode();
              } else {
                  console.error("Employee URL is missing, QR code cannot be generated.");
              }
      }
    },
    methods: {
      generateQRCode() {
          if (!this.employeeUrl) {
              console.error("Cannot generate QR code: employeeUrl is missing or invalid.");
              return;
          }

          try {

              const qrData = typeof this.employeeUrl === "object"
                  ? JSON.stringify(this.employeeUrl)
                  : this.employeeUrl;

              this.qrCodeData = qrData;

              // Generate the QR code
              QRCode.toDataURL(qrData, { width: 180, height: 150 })
                  .then((url) => {
                      this.qrCodeImageUrl = url;
                      console.log("QR Code generated successfully.");
                  })
                  .catch((err) => {
                      console.error("Error generating QR code:", err);
                  });
          } catch (error) {
              console.error("Error processing employeeUrl for QR code:", error);
          }
      },
      downloadCertificate() {
  const element = document.getElementById("certificate");
  const content = element.innerHTML;

  const header =
    `<html xmlns:o='urn:schemas-microsoft-com:office:office' ` +
    `xmlns:w='urn:schemas-microsoft-com:office:word' ` +
    `xmlns='http://www.w3.org/TR/REC-html40'><head><title>Document</title></head><body>` +
    `<style>
      .qr-code {
        position: absolute;
        top: 50px;
        bottom: 20px;
        left: 80%;
        width: 150px;
        text-align: right;
      }
      .qr-code img {
        width: 100%;
        max-width: 150px;
        height: auto;
      }
      .qr-code p {
        margin-top: 5px;
        font-size: 14px;
        color: #333;
      }
      .container {
        position: relative;
        width: 50%;
        height: 50%;
        padding: 20px;
        box-sizing: border-box;
      }
    </style>`;

  const footer = "</body></html>";
  const wordContent = header + content + footer;

  const blob = new Blob([wordContent], {
    type: "application/msword",
  });

  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = "service_agreement_letter.doc";
  link.click();


  URL.revokeObjectURL(link.href);
  console.log("Word document downloaded successfully");
},


      submit() {
        this.form.post(
          this.ServiceLetter
            ? route("employee.ServiceLetter.update")
            : route("employee.ServiceLetter.store"),
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
    },
  };
  </script>


  <style>
  body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
  }
  .container {
    width: 90%;
    height: 150%;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    margin-top: 40px;
    margin-bottom: 40px;
    /* border: 1px black solid; */
  }
  .qr-code {
    margin-top: 10px;
    margin-left: 80%;
    bottom: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    max-width: 180px;
    /* border: 1px red solid; */
  }

  .qr-code img {
    width: 100%;
    max-width: 150px;
    height: auto;
  }
  .content {
    margin-bottom: 10px;
  }
  .footer p {
    font-size: 0.9em;
    color: #777;
  }

  p {
    line-height: 1.8;
  }
  .qr-code p {
      margin-top: 5px;
      font-size: 14px;
      color: #333;
  }
  /* @media download {
    .qr-code {
      position: relative;
      left: 70%;
      bottom: 0;
      width: 100px;
      height: auto;
      margin: 0;
      z-index: 1000;
    }
  } */

  </style>
