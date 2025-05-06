<template>
  <AppLayout>
    <!-- Total active Container -->
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h6 class="card-title m-0 me-2">Active Employees</h6>

            </div>
            <div class="card-body text-center">
              <div class="avatar avatar-md border-5 border-light-info rounded-circle mx-auto mb-4">
                <span class="avatar-initial rounded-circle bg-label-info">
                  <i class="bx bx-user bx-sm"></i>
                </span>
              </div>

              <h3 class="card-title mb-1 me-2">
                {{ activeEmployeeCount }}

              </h3>

              <small class="d-block mb-2">{{activeEmployeeCount > 1 ? 'Employees' : 'Employee'}}</small>
            </div>
          </div>
        </div>
        <!-- Total inactive Container -->
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h6 class="card-title m-0 me-2">Inactive Employees</h6>

            </div>
            <div class="card-body text-center">
              <div class="avatar avatar-md border-5 border-light-warning rounded-circle mx-auto mb-4">
                <span class="avatar-initial rounded-circle bg-label-warning">
                  <i class="bx bx-user bx-sm"></i>
                </span>
              </div>

              <h3 class="card-title mb-1 me-2">
                {{ inactiveEmployeeCount }}

              </h3>

              <small class="d-block mb-2">{{inactiveEmployeeCount > 1 ? 'Employees' : 'Employee' }}</small>
            </div>
          </div>
        </div>
      </div>
    </div>

  </AppLayout>
</template>
<script>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import DataTable from "@/Components/DataTable.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Jetstream/Welcome.vue";

export default {
  components: {
    AppLayout,
    Link
  },
  props: {
    activeEmployeeCount: Number,
    inactiveEmployeeCount: Number
  },
  data() {
    return {};
  },
  mounted() { }
};
</script>