<template>
  <AppLayout>
    <!--Leave recode div start-->
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header pb-0">
            <div class="col-12 mb-4">
              <div class="row">
                <div class="col-1">
                  <Link class="back-button" :href="route('employee.leaves.index', form.employee_id)">
                    <i class="back-icon fas fa-arrow-left p-2"></i>
                  </Link>
                </div>
                <div class="col-11 d-flex justify-content-end">
                  <h5 class="m-0 text-center">{{ employee.name }}</h5>
                </div>

              </div>
            </div>
            <h5>Employee Leaves</h5>
            <p>Manage Employee Leaves Details</p>
          </div>

          <div class="card-body">
            <form id="formAccountSettings" @submit.prevent="submit">
              <div class="row">
                <div class="mb-3 col-md-6" action="/action_page.php">
                  <label class="form-label" for="from_date">From Date:</label>
                  <input class="form-control" type="date" id="from_date" name="from_date" v-model="form.from_date" />
                  <div class="text-danger">{{ form.errors.from_date }}</div>
                </div>

                <div class="mb-3 col-md-6" action="/action_page.php">
                  <label class="form-label" for="to">To:</label>
                  <input class="form-control" type="date" id="to" name="to" v-model="form.to" />
                  <div class="text-danger">{{ form.errors.to }}</div>
                </div>

                <SelectInputComponent id="leave_type" label="Leave Type" :error="form.errors.leave_type" :options="[
                  {
                    id: 'Casual Leave',
                    name: 'Casual Leave',
                  },
                  {
                    id: 'Annual Leave',
                    name: 'Annual Leave',
                  },
                  {
                    id: 'Short Leave',
                    name: 'Short Leave',
                  },
                  {
                    id: 'Study Leave',
                    name: 'Study Leave',
                  },
                  {
                    id: 'Medical Leave',
                    name: 'Medical Leave',
                  },
                ]" v-model="form.leave_type" />

                <!-- <SelectInputComponent v-if="form.leave_type != 'Short Leave'" id="type" label="Duration" :error="form.errors.type" :options="[
                  {
                    id: 'Half day',
                    name: 'Half day',
                  },
                  {
                    id: 'Full day',
                    name: 'Full day',
                  },
                  {
                    id: 'Start date to End date',
                    name: 'Start date to End date',
                  },

                ]" v-model="form.type" /> -->




                  <SelectInputComponent id="type" label="Duration" :error="form.errors.type" :options="[
                    { id: 'Half day', name: 'Half day' },
                    { id: 'Full day', name: 'Full day' },
                    { id: 'Start date to End date', name: 'Start date to End date' },
                  ]" v-model="form.type" :isDisabled="form.leave_type == 'Short Leave'" />



                  <div class="mb-3 col-md-6" v-if="form.leave_type == 'Short Leave'">
                    <label class="form-label" for="start_time">Start Time</label>
                    <input class="form-control" type="time" id="start_time" v-model="form.start_time"/>
                  </div>
                  <div class="mb-3 col-md-6" v-if="form.leave_type == 'Short Leave'">
                    <label class="form-label" for="end_time">End Time</label>
                    <input class="form-control" type="time" id="end_time" v-model="form.end_time" />
                  </div>


                <div class="mb-3 col-md-6">
                  <label for="reason" class="form-label">Reason</label>
                  <textarea class="form-control" type="text" id="reason" name="reason" v-model="form.reason"></textarea>
                  <div class="text-danger">{{ form.errors.reason }}</div>
                </div>
                <SelectInputComponent id="leave_status" label="Leave Status" :error="form.errors.leave_status" :options="[
                  {
                    id: 'Rejected',
                    name: 'Rejected',
                  },
                  {
                    id: 'Approved',
                    name: 'Approved',
                  },
                  {
                    id: 'Pending',
                    name: 'Pending',
                  },
                ]" v-model="form.leave_status" />

                <div class="mb-3 col-md-2">
                  <label for="proof_image" class="form-label me-3">Proof Image:</label>
                  <br />
                  <FileInputComponent :isRequired="false" id="proof_image" :prvImage="profileImage"
                    v-model="form.proof_image" />
                  <div class="text-danger">{{ form.errors.proof_image }}</div>
                  <a v-if="leave_data && leave_data.media.length > 0"
                    :href="leave_data ? leave_data.media.length > 0 ? leave_data.media[0].original_url : null : null"
                    class target="_blank">View Proof Image</a>
                </div>
                <!-- <div v-if="leave_data.media.length > 0" class="mb-3 col-md-3 d-flex align-items-center ">
                  <a :href="leave_data.media.length > 0 ? leave_data.media[0].original_url : null" class="" target="_blank">View Proof Image</a>
                </div>-->
              </div>

              <div class="mt-2">
                <button type="submit" class="btn btn-main me-2" :disabled="form.processing">
                  {{ leave_data ? "Update" :
                    "Save" }}
                </button>
                <Link class="btn btn-outline-danger" :href="route('employee.leaves.index', form.employee_id)"
                  style="margin-right: 10px;">Cancel</Link>
                <!-- need to wrrite cancel rout -->
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!--Leave recode end-->
  </AppLayout>
</template>




<script>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import FileInputComponent from "@/Components/FileInputComponent.vue";
import SelectInputComponent from "@/Components/SelectInputComponent.vue";
import SelectInputComponent2 from "@/Components/SelectInputComponent2.vue";
import InputComponent from "../../Components/InputComponent.vue";

export default {
  components: {
    Link,
    AppLayout,
    FileInputComponent,
    SelectInputComponent,
    SelectInputComponent2,
    InputComponent
  },

  props: {
    errors: Object,
    employee: Object,
    leave_data: Object
  },

  data() {
    return {
      form: useForm({
        employee_id: this.employee.id,
        leaeve_id: "",
        from_date: "",
        to: "",
        reason: "",
        type: "",
        leave_type: "",
        leave_status: "",
        proof_image: "",
        start_time: "",
        end_time: "",
      }),
      durationKey: 0,
    };
  },
  mounted() {
    let self = this;
    // this is use for display filed data
    if (this.leave_data) {
      this.form.leaeve_id = this.leave_data.id;
      this.form.from_date = this.leave_data.from_date;
      this.form.to = this.leave_data.to;
      this.form.reason = this.leave_data.reason;
      this.form.type = this.leave_data.type;
      this.form.leave_type = this.leave_data.leave_type;
      this.form.leave_status = this.leave_data.leave_status;

      if (this.leave_data.leave_type === "Short Leave" && this.leave_data.type) {
        const times = this.leave_data.type.split(" - ");
        if (times.length === 2) {
          this.form.start_time = times[0];
          this.form.end_time = times[1];
        }
      }
    }
  },
  watch: {
    'form.leave_type'(newVal) {
      if (newVal === 'Short Leave') {
        this.form.type = `${this.form.start_time} - ${this.form.end_time}`;
      } else {
        this.form.start_time = '';
        this.form.end_time = '';
        this.durationKey += 1;
      }
    },
    'form.start_time'(newVal) {
      if (this.form.leave_type === 'Short Leave') {
        this.form.type = `${newVal} - ${this.form.end_time}`;
      }
    },
    'form.end_time'(newVal) {
      if (this.form.leave_type === 'Short Leave') {
        this.form.type = `${this.form.start_time} - ${newVal}`;
      }
    },
  },
  computed: {
    profileImage() {
      return this.leave_data
        ? this.leave_data.media.length > 0
          ? this.leave_data.media[0].original_url
          : ""
        : "";
    }
  },
  methods: {
    submit() {
      this.form.post(
        this.leave_data
          ? route("employee.leaves.update") // update route for leaves
          : route("employee.leaves_store"), // store route for leaves
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
            this.form.reset();
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
