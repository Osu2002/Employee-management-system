<template>
    <AppLayout>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h5>Customer history</h5>
                        <p>View customer activities.</p>
                    </div>
                    <div class="card-body">
                        <div class="container mt-5">
                            <ul class="nav nav-pills mb-3 border-bottom border-2 mb-5" id="pills-tab" role="tablist">
                                <li class="nav-item px-5" role="presentation">
                                    <button class="nav-link text-main fw-semibold active position-relative"
                                        id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                                        type="button" role="tab" aria-controls="pills-home"
                                        aria-selected="true">Bookings</button>
                                </li>
                                <!-- <li class="nav-item px-5" role="presentation">
                                    <button class="nav-link text-main fw-semibold position-relative"
                                        id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                                        type="button" role="tab" aria-controls="pills-profile"
                                        aria-selected="false">Rewards earnings</button>
                                </li>
                                <li class="nav-item px-5" role="presentation">
                                    <button class="nav-link text-main fw-semibold position-relative"
                                        id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact"
                                        type="button" role="tab" aria-controls="pills-contact"
                                        aria-selected="false">Points spend history</button>
                                </li> -->
                            </ul>
                            <div class="tab-content border rounded-3 border-main p-3" id="pills-tabContent">
                                <div class="tab-pane fade show active pb-5 px-2" id="pills-home" role="tabpanel"
                                    aria-labelledby="pills-home-tab">
                                    <data-table ref="datatable" :id="'mytable'" :url="route('customer.getBookings', {id:customer.id})"
                                        :columns="columns" :columnDefs="columnDefs">
                                        <template #header>
                                            <tr>
                                                <th width="10%">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="form-check-input" id="selectAll"
                                                            @click="selectAll($event)" />
                                                        <label class="form-check-label" for="selectAll"></label>
                                                    </div>
                                                </th>
                                                <th>Customer Name</th>
                                                <th>Mobile</th>
                                                <th>Services</th>
                                                <th>Date & Time</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </template>
                                    </data-table>
                                </div>
                                <!-- <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                    aria-labelledby="pills-profile-tab">
                                    <data-table ref="datatable" :id="'mytable2'" :url="route('customer.getEarnings', {id:customer.id})"
                                        :columns="columns2" :columnDefs="columnDefs2">
                                        <template #header>
                                            <tr>
                                                <th width="10%">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="form-check-input" id="selectAll"
                                                            @click="selectAll($event)" />
                                                        <label class="form-check-label" for="selectAll"></label>
                                                    </div>
                                                </th>
                                                <th>Reward Type</th>
                                                <th>Earned Points</th>
                                            </tr>
                                        </template>
                                    </data-table>
                                </div>
                                <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                    aria-labelledby="pills-contact-tab">
                                    <data-table ref="datatable" :id="'mytable3'" :url="route('customer.getEarnings', {id:customer.id})"
                                        :columns="columns3" :columnDefs="columnDefs3">
                                        <template #header>
                                            <tr>
                                                <th width="10%">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="form-check-input" id="selectAll"
                                                            @click="selectAll($event)" />
                                                        <label class="form-check-label" for="selectAll"></label>
                                                    </div>
                                                </th>
                                                <th>Spend Type</th>
                                                <th>Points</th>
                                            </tr>
                                        </template>
                                    </data-table>
                                </div> -->
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
import DataTable from "@/Components/DataTable.vue";

export default {
    components: {
        Link,
        AppLayout,
        DataTable,
    },
    data() {
    return {
      columns: [
        {
          data: "check",
          name: "check",
          orderable: false,
          searchable: false,
        },
        {
          data: "customer",
          name: "customer",
          orderable: true,
          searchable: true,
        },
        {
          data: "mobile",
          name: "mobile",
          orderable: false,
          searchable: true,
        },
        {
          data: "services",
          name: "services",
          orderable: false,
          searchable: true,
        },
        {
          data: "date_time",
          name: "date_time",
          orderable: false,
          searchable: true,
        },
        { data: "total", name: "total", orderable: true },
        { data: "status", name: "status", orderable: true },
        { data: "action", name: "action", orderable: true },
      ],
      columnDefs: [{ className: "text-center", targets: [] }],
      order: [[1, "desc"]],

      form: useForm({
        id: "",
        status: "",
      }),
    };
  },
    props: {
        customer: Object
    },
    mounted() {
    $("#mytable tbody").on("click", "tr .action_delete", (evt) => {
      const data = $(evt.target).data("item-id");
      this.selectedRows = [];
      this.getSelectedItems(data);
    });
    $("#mytable tbody").on("click", "tr .action_edit", (evt) => {
      const id = $(evt.target).data("item-id");
      this.$inertia.visit(route("booking.edit", id));
    });

    $("#mytable tbody").on("click", "tr .action_status_change", (evt) => {
      const id = $(evt.target).data("item-id");
      const status = $(evt.target).data("status");
      this.updateStatus(id, status);
    });

    $("#mytable tbody").on(
      "click",
      ".item-check input[type=checkbox]",
      (evt) => {
        const checkedVal = $(evt.target).val();
        if (
          $(evt.target).prop("checked") &&
          !this.selectedRows.includes(checkedVal)
        ) {
          this.getSelectedItems(checkedVal);
        } else {
          this.removeUnselectedItem(checkedVal);
        }
      }
    );
  },
  methods: {
    reloadTable() {
      this.$refs.datatable.reloadDatatable();
    },
    getSelectedItems(value) {
      this.selectedRows.push(value);
      if (this.selectedRows.length > 0) {
        this.$data.isDisabled = false;
      }
    },
    selectAll(evt) {
      var self = this;
      if ($(evt.target).is(":checked")) {
        $(".item-check input[type=checkbox]").prop("checked", true);
        $(".item-check input[type=checkbox]:checked").each(function () {
          if (!self.selectedRows.includes(this.value)) {
            self.getSelectedItems(this.value);
          }
        });
        this.$data.isDisabled = false;
      } else {
        $(".item-check input[type=checkbox]").prop("checked", false);
        $(".item-check input[type=checkbox]").each(function () {
          if (self.selectedRows.includes(this.value)) {
            self.removeUnselectedItem(this.value);
          }
        });
        this.$data.isDisabled = true;
      }
    },
    removeUnselectedItem(value) {
      this.selectedRows = this.selectedRows.filter(function (val) {
        return val != value;
      });
      if (this.selectedRows.length <= 0) {
        $("#selectAll").prop("checked", false);
        this.$data.isDisabled = true;
      }
    },
    updateStatus(id, status) {
      this.form.id = id;
      this.form.status = status;
      this.form.put(route("booking.change.status"), {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset();
          this.reloadTable();
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
    deleteSelectedItems() {
      this.form
        .transform((data) => ({
          ...data,
          ids: [...this.selectedRows],
        }))
        .post(route("booking.delete"), {
          onSuccess: () => {
            this.resetForm();
            this.reloadTable();
            this.$root.showMessage(
              "success",
              '<span class="text-success">Success</span><br/>',
              "A Record Was Created Successfully! "
            );
          },
          onError: () => {
            this.$root.showMessage(
              "error",
              '<span class="text-danger">Error</span><br>',
              "Something went wrong! "
            );
          },
          onFinish: () => {
            $("#deleteConfirm").modal("hide");
            this.resetForm();
          },
        });
    },
    resetForm() {
      this.form.reset("id", "status");
    },
  },
};
</script>
<style>
.nav .nav-item button.active {
    background-color: transparent;
    color: #ff4d6d !important;
}

.nav .nav-item button.active::after {
    content: "";
    border-bottom: 4px solid #ff4d6d;
    width: 100%;
    position: absolute;
    left: 0;
    bottom: -1px;
    border-radius: 5px 5px 0 0;
}
</style>