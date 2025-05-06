<template>
    <AppLayout>
        <div class="col-md-12">
            <!-- Employee Leave Management Header -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <div>
                        <h3
                            class="pb-0"
                            style="font-size: 1.1rem; color: #495057"
                        >
                            Employee Leave
                        </h3>
                        <p class="mb-0" style="font-size: 0.89rem">
                            Manage all employee leave applications efficiently.
                        </p>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            <span class="me-2" style="font-size: 14px"
                                >Show</span
                            >
                            <select
                                v-model="pageSize"
                                class="form-select form-select-sm"
                                style="
                                    width: 90px;
                                    font-size: 13px;
                                    padding: 4px 8px;
                                "
                            >
                                <option value="25">25 rows</option>
                                <option value="50">50 rows</option>
                                <option value="100">100 rows</option>
                                <option value="500">500 rows</option>
                            </select>
                            <span class="ms-2" style="font-size: 14px"
                                >entries</span
                            >
                        </div>

                        <!-- Status Dropdown -->
                        <div class="d-flex align-items-center">
                            <label
                                for="statusFilter"
                                class="me-2 text-muted fw-bold"
                                style="font-size: 14px"
                                >Status:</label
                            >
                            <select
                                id="statusFilter"
                                class="form-select form-select-sm"
                                v-model="selectedStatus"
                                style="width: 150px; font-size: 13px"
                            >
                                <option value="">All Statuses</option>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>

                        <!-- Search Bar -->
                        <div
                            class="d-flex align-items-center justify-content-end"
                        >
                            <label
                                for="search"
                                class="me-2"
                                style="font-size: 14px"
                                >Search:</label
                            >
                            <input
                                id="search"
                                type="text"
                                class="form-control form-control-sm"
                                placeholder="Search by Employee Name"
                                v-model="searchQuery"
                                style="width: 200px; font-size: 13px"
                            />
                        </div>
                    </div>

                    <!-- Leave Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Designation</th>
                                    <th>Leave Type</th>
                                    <th>Proof</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="leave in paginatedLeaves"
                                    :key="leave.id"
                                >
                                    <td>EMP500{{ leave.employee.id }}</td>
                                    <td>{{ leave.employee.name }}</td>
                                    <td>
                                        {{
                                            leave.employee.designation_name ||
                                            leave.employee.designation
                                        }}
                                    </td>
                                    <td>{{ leave.leave_type }}</td>
                                    <td>
                                        <a
                                            v-if="leave.proof"
                                            :href="`/storage/${leave.proof}`"
                                            target="_blank"
                                            class="btn btn-outline-primary btn-sm"
                                        >
                                            View Proof
                                        </a>

                                        <span v-else class="text-muted"
                                            >No Proof</span
                                        >
                                    </td>
                                    <td>
                                        <span
                                            :class="{
                                                'badge bg-warning text-dark':
                                                    leave.status === 'Pending',
                                                'badge bg-success':
                                                    leave.status === 'Approved',
                                                'badge bg-danger':
                                                    leave.status === 'Rejected',
                                            }"
                                        >
                                            {{ leave.status }}
                                        </span>
                                    </td>
                                    <td>
                                        <button
                                            class="btn btn-info btn-sm"
                                            @click="openLeaveForm(leave)"
                                        >
                                            View/Edit
                                        </button>
                                        <button
                                            class="btn btn-danger btn-sm ms-2"
                                            @click="deleteLeave(leave.id)"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="paginatedLeaves.length === 0">
                                    <td
                                        colspan="7"
                                        class="text-center text-muted"
                                    >
                                        No leave records found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br />
                    <!-- Pagination -->
                    <div
                        class="d-flex justify-content-between align-items-center"
                    >
                        <p class="text-muted">
                            Showing {{ currentPage }} of {{ totalPages }} pages
                        </p>
                        <nav>
                            <ul class="pagination mb-0">
                                <li
                                    class="page-item"
                                    :class="{ disabled: currentPage === 1 }"
                                >
                                    <button class="page-link" @click="prevPage">
                                        Previous
                                    </button>
                                </li>
                                <li
                                    class="page-item"
                                    :class="{
                                        disabled: currentPage === totalPages,
                                    }"
                                >
                                    <button class="page-link" @click="nextPage">
                                        Next
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- Leave Modal -->
            <div
                class="modal fade"
                id="leaveModal"
                tabindex="-1"
                aria-labelledby="leaveModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="leaveModalLabel">
                                Edit Leave Status
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            ></button>
                        </div>

                        <!-- Leave Details -->
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Employee ID</th>
                                        <td>EMP500{{ form.employee_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Employee Name</th>
                                        <td>{{ form.employee_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Designation</th>
                                        <td>{{ form.designation }}</td>
                                    </tr>
                                    <tr>
                                        <th>Leave Type</th>
                                        <td>{{ form.leave_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>From Date</th>
                                        <td>{{ form.from_date }}</td>
                                    </tr>
                                    <tr>
                                        <th>To Date</th>
                                        <td>{{ form.to_date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Partial Days</th>
                                        <td>{{ form.partial_days }}</td>
                                    </tr>
                                    <tr>
                                        <th>Duration</th>
                                        <td>{{ form.duration }}</td>
                                    </tr>
                                    <!-- <tr>
                                        <th>Start Time</th>
                                        <td>{{ form.start_time }}</td>
                                    </tr>
                                    <tr>
                                        <th>End Time</th>
                                        <td>{{ form.end_time }}</td>
                                    </tr> -->
                                    <tr>
                                        <th>Comments</th>
                                        <td>{{ form.comments }}</td>
                                    </tr>
                                    <tr>
                                        <th>Proof</th>
                                        <td>
                                            <a
                                                v-if="form.proof"
                                                :href="`/storage/${form.proof}`"
                                                target="_blank"
                                                class="btn btn-outline-primary btn-sm"
                                            >
                                                View Proof
                                            </a>
                                            <span v-else class="text-muted"
                                                >No Proof Uploaded</span
                                            >
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Status Section -->
                        <div
                            class="d-flex flex-column gap-3 p-3"
                            style="
                                background-color: #f8f9fa;
                                border: 1px solid #dee2e6;
                                border-radius: 8px;
                            "
                        >
                            <!-- STATUS Section -->
                            <div class="d-flex align-items-center gap-3">
                                <label
                                    for="leave_status"
                                    class="fw-bold text-secondary mb-0"
                                    style="font-size: 15px; min-width: 80px"
                                >
                                    Status:
                                </label>
                                <select
                                    id="leave_status"
                                    class="form-select form-select-sm"
                                    v-model="form.status"
                                    style="
                                        width: 200px;
                                        font-size: 14px;
                                        padding: 6px 8px;
                                        border-radius: 6px;
                                    "
                                >
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>

                            <!-- INSTRUCTIONS Section -->
                            <div class="d-flex flex-column gap-2">
                                <label
                                    for="admin_instructions"
                                    class="fw-bold text-secondary mb-0"
                                    style="font-size: 15px"
                                >
                                    Instructions:
                                </label>
                                <textarea
                                    id="admin_instructions"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Enter instructions here..."
                                    v-model="form.instructions"
                                    style="
                                        resize: none;
                                        font-size: 14px;
                                        padding: 8px 10px;
                                        border-radius: 6px;
                                        border: 1px solid #ced4da;
                                    "
                                ></textarea>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer d-flex justify-content-end">
                            <button
                                type="button"
                                class="btn btn-outline-secondary btn-sm px-3"
                                data-bs-dismiss="modal"
                            >
                                Close
                            </button>
                            <button
                                type="button"
                                class="btn btn-success btn-sm px-3"
                                @click="updateStatus"
                            >
                                Update Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";

export default {
    components: { AppLayout },
    props: {
        leaves: Array,
    },
    data() {
        return {
            searchQuery: "",
            selectedStatus: "",
            pageSize: 25,
            currentPage: 1,
            form: {
                employee_id: "",
                employee_name: "",
                designation: "",
                leave_type: "",
                from_date: "",
                to_date: "",
                partial_days: "",
                duration: "",
                start_time: "",
                end_time: "",
                comments: "",
                proof: "",
                instructions: "",
                status: "Pending",
            },
        };
    },
    computed: {
        filteredLeaves() {
            return this.leaves.filter((leave) => {
                return (
                    leave.employee.name
                        .toLowerCase()
                        .includes(this.searchQuery.toLowerCase()) &&
                    (this.selectedStatus === "" ||
                        leave.status === this.selectedStatus)
                );
            });
        },
        paginatedLeaves() {
            const start = (this.currentPage - 1) * this.pageSize;
            return this.filteredLeaves.slice(start, start + this.pageSize);
        },
        totalPages() {
            return Math.ceil(this.filteredLeaves.length / this.pageSize);
        },
    },
    methods: {
        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },
        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },
        openLeaveForm(leave) {
            this.form = {
                leave_id: leave.id,
                employee_id: leave.employee.id,
                employee_name: leave.employee.name,
                designation: leave.employee.designation_name,
                leave_type: leave.leave_type,
                from_date: leave.from_date,
                to_date: leave.to_date,
                partial_days: leave.partial_days,
                duration: leave.duration,
                start_time: leave.start_time,
                end_time: leave.end_time,
                comments: leave.comments,
                proof: leave.proof,
                status: leave.status,
                instructions: leave.instructions,
            };
            new bootstrap.Modal(document.getElementById("leaveModal")).show();
        },
        deleteLeave(leaveId) {
            Swal.fire({
                title: "Are you sure?",
                text: "This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.delete(
                        route("employee.leaves.destroy", leaveId),
                        {
                            onSuccess: () => {
                                Swal.fire(
                                    "Deleted!",
                                    "The leave record has been deleted.",
                                    "success"
                                );
                            },
                            onError: () => {
                                Swal.fire(
                                    "Error!",
                                    "An error occurred while deleting the leave record.",
                                    "error"
                                );
                            },
                        }
                    );
                }
            });
        },
        updateStatus() {
            this.$inertia.post(
                route("employee.leaves.update_status"),
                {
                    leave_id: this.form.leave_id,
                    status: this.form.status,
                    instructions: this.form.instructions,
                },
                {
                    preserveState: true,
                    onSuccess: () => {
                        Swal.fire(
                            "Success",
                            "Leave status updated successfully.",
                            "success"
                        );

                        const modalElement =
                            document.getElementById("leaveModal");
                        const modalInstance =
                            bootstrap.Modal.getInstance(modalElement);
                        modalInstance.hide();
                    },
                    onError: () => {
                        Swal.fire(
                            "Error",
                            "An error occurred while updating the status.",
                            "error"
                        );
                    },
                }
            );
        },
    },
};
</script>

<style scoped>
.table {
    margin-top: 10px;
}
.status-wrapper {
    background-color: #f8f9fa;
    border: 1px solid #e0e4e7;
}

.form-select-sm {
    padding: 6px 10px;
    font-size: 14px;
    border-radius: 4px;
}
</style>
