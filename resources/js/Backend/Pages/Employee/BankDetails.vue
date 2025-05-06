

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
            <h5>Employee Bank Details</h5>
              <p>Manage Employee Bank Details</p>
            </div>
          
            <div class="card-body">
              <form id="formAccountSettings" @submit.prevent="submit">
                <div class="row">
                  <div class="mb-3 col-md-6">
                    <label for="account_holders_name" class="form-label">Account Holder's Name :</label>
                    <input class="form-control" type="text" id="account_holders_name" name="account_holders_name" v-model="form.account_holders_name" />
                    <div class="text-danger">{{ form.errors.account_holders_name }}</div>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="bank_name" class="form-label">Bank Name :</label>
                    <input class="form-control" type="text" id="bank_name" name="bank_name" v-model="form.bank_name" />
                    <div class="text-danger">{{ form.errors.bank_name }}</div>
                  </div>
  
                  <div class="mb-3 col-md-6">
                    <label for="bank_code" class="form-label">Bank Code:</label>
                    <input
                      class="form-control"
                      type="text"
                      id="bank_code"
                      name="bank_code"
                      v-model="form.bank_code"
                    />
                    <div class="text-danger">{{ form.errors.bank_code }}</div>
                  </div>
  
                  <div class="mb-3 col-md-6">
                    <label for="bank_account_number" class="form-label">Bank Account Number :</label>
                    <input
                      class="form-control"
                      type="text"
                      id="bank_account_number"
                      name="bank_account_number"
                      v-model="form.bank_account_number"
                    />
                    <div class="text-danger">{{ form.errors.bank_account_number}}</div>
                  </div>
                  
  
                  <div class="mb-3 col-md-6">
                    <label for="branch_name" class="form-label">Branch Name :</label>
                    <input
                      class="form-control"
                      type="text"
                      id="branch_name"
                      name="branch_name"
                      v-model="form.branch_name"
                    />
                    <div class="text-danger">{{ form.errors.branch_name}}</div>
                  </div>
                  
                  
  
                  <div class="mb-3 col-md-6" action="/action_page.php">
                    <label for="branch_code">Branch Code :</label>
                    <input
                      class="form-control"
                      type="text"
                      id="branch_code"
                      name="branch_code"
                      v-model="form.branch_code"
                    />
                    <div class="text-danger">{{ form.errors.branch_code}}</div>
                  </div>
  
                  <div class="mb-3 col-md-6" action="/action_page.php">
                    <label for="linked_phone_number">Phone Number linked to the bank account:</label>
                    <input
                      class="form-control"
                      type="text"
                      id="linked_phone_number"
                      name="linked_phone_number"
                      v-model="form.linked_phone_number"
                    />
                    <div class="text-danger">{{ form.errors.linked_phone_number}}</div>
                  </div>
                  
                  </div>
  
                <div class="mt-2">
                  <button
                    type="submit"
                    class="btn btn-main me-2"
                    :disabled="form.processing"
                  >{{ bank_data ? "Update" : "Save" }}</button>
                  <Link class="btn btn-outline-danger" :href="route('employee.edit', form.employee_id)" style="margin-right: 10px;">Cancel</Link>
                  <!-- need to wrrite cancel rout -->
                  
                  
                </div>
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
  import FileInputComponent from "@/Components/FileInputComponent.vue";
  import SelectInputComponent from "@/Components/SelectInputComponent.vue";
  import InputComponent from "../../Components/InputComponent.vue";

  
  export default {
    components: {
      Link,
      AppLayout,
      FileInputComponent,
      SelectInputComponent,
      InputComponent
    },
  
    props: {
      errors: Object,
      employee: Object,
      bank_data: Object
    
     
    },
  
    data() {
      return {
        form: useForm({
          id: "",
            employee_id: this.employee.id,
            account_holders_name: "",
            bank_name: "",
            bank_code: "",
            bank_account_number: "",
            branch_name: "",
            branch_code: "",
            linked_phone_number: ""
          
        }),
        
      };
    },
    mounted() {
       
  
      let self = this;
      // this is use for display filed data
      if (this.bank_data) {
        this.form.id = this.bank_data.id ;
        this.form.employee_id = this.employee.id ;
        this.form.account_holders_name = this.bank_data.account_holders_name;
        this.form.bank_name = this.bank_data.bank_name;
        this.form.bank_code = this.bank_data.bank_code;
        this.form.bank_account_number = this.bank_data.bank_account_number;
        this.form.branch_name = this.bank_data.branch_name;
        this.form.branch_code = this.bank_data.branch_code;
        this.form.linked_phone_number = this.bank_data. linked_phone_number;
        
      }
    },
  
    methods: {
        submit() {
        this.form.post(
          this.bank_data
             // update route for 
            ?route("employee.bank.update")
            :route("employee.bank.store"), // store route
          {
            onSuccess: () => {
              // this.form.reset();
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
      },
      
    }
  };
  </script>
    