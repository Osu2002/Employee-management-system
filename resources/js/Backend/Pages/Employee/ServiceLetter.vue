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
            <h5>Employee Service Letter</h5>
            <p>Manage Employee Service Letter</p>
            <!-- <button
              type="button"
              class="btn btn-danger"
              @click="downloadCertificate"
            >
              Download
            </button>-->
            <form id="formAccountSettings" @submit.prevent="submit">
              <!-- <div
                id="certificate"
                class="container"
                style="
                  background-image: url(/public/backend/assets/images/service-letter-background.png);
                "
              ></div> -->
              <!-- <div class="header">
                  <img src="/images/Weblook-300x65.png" alt="Company Logo" />
                  <h1>Weblook International Pvt Ltd</h1>
                  <p class="contact-info">No 207/23, Dharmapala Mawatha, Colombo 7, Sri Lanka.</p>
                </div>
                <div class="content">
                  <p>Date : {{ formattedDate }}</p>
                  <h1></h1>
                  <p>Dear {{ employee.name }},</p> -->

              <!-- TinyMCE Editor -->
              <div class="content">
                <Editor v-model="form.editorContent" api-key="vw4ubmg1a3lka0446gbr2zl6hoyieci6cv2wpw8czi7giive" :init="{
                  plugins: [
                    'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                    'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
                    'table', 'emoticons', 'help'
                  ],
                  toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                    'forecolor backcolor emoticons | help',
                  menubar: 'favs file edit view insert format tools table help',
                }" />
              </div>
              <!-- <div class="document-container">
  <div class="content">
    <div class="signature">
      <p>Sincerely,</p>
      <p>............................................</p>
      <p>Viraj Cabral<br>Managing Director</p>
    </div>
  </div> -->
              <div class="qr-code">
                <img :src="qrCodeImageUrl" alt="QR Code" />
                <!-- <p class="mt-2">Scan Me....</p> -->
              </div>
              <!-- </div> -->
              <!-- </div> -->

              <!-- <div class="footer">
                  <p>
                    &copy; 2024 Weblook International Pvt Ltd. All rights
                    reserved.
                  </p>
                </div> -->
              <!-- </div> -->
              <!-- <p>{{ this.service_letter_data.employee_id }}</p> -->
              <div class="mt-2">
                <button type="submit" class="btn btn-main me-2" :disabled="form.processing">{{ service_letter_data ?
                  "Update" : "Save" }}</button>

                <Link type="button" :href="route('employee.edit', form.employee_id)" class="btn btn-outline-danger"
                  style="margin-right: 10px">Cancel</Link>

                <Link v-if="service_letter_data" type="button"
                  :href="route('employee.ServiceLetter.view', this.employee.id)" class="btn btn-outline-danger"
                  style="margin-right: 10px">View</Link>
                <div>
                  <!-- <button
            @click="toggleVisibility"
            :class="isVisible ? 'btn btn-danger' : 'btn btn-success'" >
            {{ isVisible ? 'Hide' : 'Display' }}
        </button> -->
                </div>
              </div>
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
import axios from 'axios';
import QRCode from "qrcode";

export default {
  components: {
    Link,
    AppLayout,
    Editor
  },
  props: {
    errors: Object,
    employee: Object,
    employeeUrl: Object,
    service_letter_data: Object

  },
  data() {
    return {
      form: useForm({
        id: "",
        editorContent: this.service_letter_data
          ? this.service_letter_data.letter
          : "",
        employee_id: this.employee.id
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
    }
  },
  mounted() {
    // Fetch initial visibility status
    // axios.post('/backend/get-service-letter-visibility').then((response) => {
    //   this.isVisible = response.data.status;
    // });


    if (this.service_letter_data) {
      this.form.id = this.service_letter_data.id;
      this.form.editorContent = this.service_letter_data.letter;

    }
    if (this.employeeUrl) {
        this.generateQRCode();
      } else {
        console.error("Employee URL is missing, QR code cannot be generated.");
      }
  },
  methods: {
    generateQRCode() {
            const qrData = JSON.stringify(this.employeeUrl);
            this.qrCodeData = qrData;

            QRCode.toDataURL(qrData, { width: 180, height: 150 })
                .then((url) => {
                    this.qrCodeImageUrl = url;
                })
                .catch((err) => {
                    console.error("Error generating QR code:", err);
                });
        },

    toggleVisibility() {
      // Make the POST request to update visibility
      axios
        .post('/backend/get-service-letter-visibility', {
          status: !this.isVisible, // Send the updated status
        })
        .then((response) => {
          // Handle successful response
          console.log('Visibility updated successfully:', response.data);

          // Update the visibility state
          this.isVisible = !this.isVisible;

          // Notify the user with the response message
          alert(response.data.message || 'Visibility updated successfully.');
        })
        .catch((error) => {
          // Handle error responses

          // Check if response was received
          if (error.response) {
            // If response exists, log detailed error
            console.error('Error response:', {
              data: error.response.data,
              status: error.response.status,
              headers: error.response.headers,
            });

            // You can display specific error messages based on status code
            if (error.response.status === 404) {
              alert('Endpoint not found. Please check the route configuration.');
            } else if (error.response.status === 500) {
              alert('Internal server error. Please try again later.');
            } else {
              alert('An error occurred. Please try again later.');
            }
          } else if (error.request) {
            // No response received
            console.error('No response received:', error.request);
            alert('No response from the server. Please check your connection.');
          } else {
            // Error setting up the request
            console.error('Error in setting up request:', error.message);
            alert('Failed to send the request. Please try again later.');
          }
        })
        .finally(() => {
          // This block is executed after the request completes (whether success or failure)
          console.log('toggleVisibility request completed');
        });
    }


    ,

    updateDocumentDisplayStatus(id, employee_id) {
      console.log("updateDocumentDisplayStatus called with:", {
        doc_id: id,
        employee_id: employee_id,
      });

      // Assign values to form data
      this.form.doc_id = id;
      this.form.employee_id = employee_id;

      // Log the updated form data
      console.log("Form data before request:", this.form);

      this.form.put(route("employee.document.service_letter_visible", id), {
        preserveScroll: true,
        onSuccess: (response) => {
          console.log("Success response:", response);

          this.$root.showMessage(
            "success",
            '<span class="text-success">Success</span><br/>',
            "Status Updated Successfully!"
          );
        },
        onError: (error) => {
          console.error("Error response:", error);

          this.$root.showMessage(
            "error",
            '<span class="text-danger">Error</span><br>',
            "Something went wrong!"
          );
        },
      });
    },


    downloadCertificate() {
      const element = document.getElementById("certificate");
      const opt = {
        margin: 0,
        filename: "service_agreement_letter.pdf",
        image: { type: "jpeg", quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: "in", format: "a4", orientation: "portrait" }
      };
      html2pdf()
        .from(element)
        .set(opt)
        .save()
        .then(() => {
          console.log("PDF downloaded successfully");
        })
        .catch(err => {
          console.error("Error downloading PDF:", err);
        });
    },

    submit() {
      this.form.post(
        this.service_letter_data
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
          }
        }
      );
    }
  }
};
</script>


<style>
.document-container {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 20px;
  /* border: 1px greenyellow solid; */
}

body {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
  margin: 0;
  padding: 0;
  background-color: #f4f4f9;
}

.container {
  width: 80%;
  margin: 0 auto;
  padding: 20px;
  background-color: #fff;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  margin-top: 40px;
  margin-bottom: 10px;
}

.header,
/* .footer {
  text-align: center;
  margin-bottom: 20px;
} */

/* .header img {
  width: 150px;
  margin-bottom: 10px;
} */

.content {
  flex: 1;
  margin-bottom: 10px;
}

/* .signature {
  margin-top: 50px;
} */

/* .footer p {
  font-size: 0.9em;
  color: #777;
} */

.qr-code {
  margin-top: 10px;
  margin-left: 80%;
  display: flex;
  justify-content: center;
  align-items: center;
  max-width: 180px;
  /* Limit the QR code's size */
}

.qr-code img {
  width: 100%;
  /* Make the QR code responsive */
  max-width: 150px;
  /* Limit maximum size */
  height: auto;
}

p {
  line-height: 1.8;
}

/* .contact-info {
  margin-top: 10px;
  font-size: 0.9em;
  color: #555;
} */

.qr-code p {
  margin-top: 8px;
  font-size: 16px;
}
</style>
