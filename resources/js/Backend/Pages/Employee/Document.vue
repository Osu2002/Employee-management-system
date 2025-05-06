<template>
  <AppLayout>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header pb-0">
            <div class="col-12 mb-4">
              <div class="row">
                <div class="col-1">
                  <Link class="back-button" :href="route('employee.edit', form.employee_id)">
                    <i class="back-icon fas fa-arrow-left p-2"></i>
                  </Link>
                </div>
                <div class="col-11 d-flex justify-content-end">
                  <h5 class="m-0 text-center">{{ employee.name }}</h5>
                </div>

              </div>
            </div>

            <h5>Employee Documents</h5>
            <p>Upload your all documents here.</p>
          </div>

          <div class="card-body">
            <form id="formAccountSettings" @submit.prevent="submit">
              <div class="row">
                <!--start-->

                <div class="mb-3 col-md-3" action="/action_page.php">
                  <label for="upload_date">Upload Date:</label>
                  <input class="form-control" type="date" id="upload_date" name="upload_date"
                    v-model="form.upload_date" />
                  <div class="text-danger">{{ form.errors.upload_date }}</div>
                </div>

                <div class="mb-3 col-md-3" action="/action_page.php">
                  <label for="document_name">Document Name:</label>
                  <input class="form-control" type="text" id="document_name" name="document_name"
                    v-model="form.document_name" />
                  <div class="text-danger">{{ form.errors.document_name }}</div>
                </div>

                <div class="mb-3 col-md-4" action="/action_page.php">
                  <label for="upload_document">Upload Document:</label>
                  <div class="mb-3">
                    <input class="form-control" type="file" @change="onFileChanged($event)" id="FileOne" />
                  </div>
                  <div class="text-danger">
                    {{ form.errors.upload_document }}
                  </div>
                </div>

                <!--end-->
              </div>
              <div class="mt-2">
                <button type="submit" class="btn btn-main me-2" :disabled="form.processing">
                  Save
                </button>

                <Link type="button" :href="route('employee.edit', form.employee_id)" class="btn btn-outline-danger"
                  style="margin-right: 10px">Cancel</Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!--Display leave recode div start-->
    <div class="row">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h5>Employee documents</h5>
        </div>

        <div class="card-body">
          <div class="records-found">
            <table>
              <thead>
                <tr>
                  <th></th>
                  <th>Upload Date</th>
                  <th>Employee Name</th>
                  <th>Document Name</th>
                  <th>Document</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody v-for="document in document_data" :key="document.id">
                <tr>
                  <td><input type="checkbox" /></td>
                  <td>{{ document.upload_date }}</td>
                  <td>{{ employee.name }}</td>
                  <td>{{ document.document_name }}</td>
                  <td>
                    <a :href="document.media[0].original_url" target="_blank">{{
                      document.media[0].original_url
                    }}</a>
                    <div class="form-check form-switch">
                      <!-- <p>{{ document.display_to_employee }}</p> -->
                      <!-- <input class="form-check-input" type="checkbox" v-model="form.display_to_employee" @change="toggleDisplay(document)" /> -->
                      <!-- <label class="form-check-label" for="flexSwitchCheckDefault">Display to employee</label> -->
                    </div>
                  </td>

                  <td>
                    <button class="btn btn-success btn-sm" @click="
                      updateDocumentDisplayStatus(document.id, employee.id)
                      ">
                      {{ document.display_to_employee == 1 ? "Hide" : "Show" }}
                    </button>
                    <button class="cancel" @click="deleteDocument(document.id, employee.id)">
                      Delete
                    </button>
                    <a class="btn btn-secondary btn-sm" :href="document.media[0].original_url" target="_blank">View</a>
                    <!-- <button class="cancel" @click="viewdoc(document.id)">View</button> -->
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>




<script>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import FileInputComponent from "@/Components/FileInputComponent.vue";
import SelectInputComponent from "@/Components/SelectInputComponent.vue";

export default {
  components: {
    Link,
    AppLayout,
    FileInputComponent,
    SelectInputComponent,
  },

  props: {
    errors: Object,
    employee: Object,
    document: Object,
    document_data: Object,
  },

  data() {
    return {
      form: useForm({
        doc_id: "",
        employee_id: this.employee.id,
        upload_date: "",
        document_name: "",
        file: "",
      }),
    };
  },
  mounted() {
    let self = this;
    // this is use for display filed data
    if (this.employee) {
      this.form.id = this.employee.id;
    }
  },
  computed: {
    document_file() {
      return this.employee
        ? this.employee.media.length > 0
          ? this.employee.media[0].original_url
          : ""
        : "";
    },
  },
  methods: {
    // Handle checkbox change
    // toggleDisplay(document) {
    //   document.display_to_employee = !document.display_to_employee;
    //   // Optionally, send the change to the backend immediately
    //   this.updateDocumentDisplayStatus(document);
    // },
    updateDocumentDisplayStatus(id, employee_id) {

      this.form.doc_id = id;
      this.form.employee_id = employee_id;
      this.form.put(route("employee.document.update_display_status", id), {
        preserveScroll: true,
        onSuccess: () => {

          this.$root.showMessage(
            "success",
            '<span class="text-success">Success</span><br/>',
            "Staus Updated Successfully! "
          );
        },
        onError: () => {
          this.$root.showMessage(
            "error",
            '<span class="text-danger">Error</span><br>',
            "Something went wrong! "
          );
        },
      });
    },
    onFileChanged($event) {
      const target = $event.target;
      if (target && target.files) {
        this.form.file = target.files[0];
        // console.log(this.form.file);
      }
    },
    submit() {
      this.form.post(
        this.leaves
          ? route("employee.document.update") // update route for leaves
          : route("employee.document.store"), // store route for leaves
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
    // delete function
    deleteDocument(id, employee_id) {
      // console.log(employee_id);
      if (confirm("Are you sure you want to delete this record?")) {
        this.form.delete(
          route("employee.document.destroy", { id, employee_id }),
          {
            onSuccess: () => {
              this.$root.showMessage(
                "success",
                '<span class="text-success">Deleted</span><br/>',
                "Record was deleted successfully!"
              );
              // Optionally refresh the data or remove the deleted record from the `document_data` array
              this.document_data = this.document_data.filter(
                (document) => document.id !== id
              );
            },
            onError: () => {
              this.$root.showMessage(
                "error",
                '<span class="text-danger">Error</span><br>',
                "Failed to delete the record!"
              );
            },
          }
        );
      }
    },
  },
};
</script>


<style>
.records-found {
  margin-top: 20px;
}

.records-found p {
  margin-bottom: 10px;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

/* table th, table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
} */

table th {
  background-color: #f2f2f2;
}

table td button.cancel {
  padding: 5px 10px;
  background-color: #ff4d4d;
  border: none;
  border-radius: 4px;
  color: white;
  cursor: pointer;
}
</style>