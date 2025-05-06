<template>
    <AppLayout>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Leave Request</h4>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="updateLeave">
                                <!-- Leave Type -->
                                <div class="mb-3">
                                    <label for="leave_type" class="form-label">Leave Type</label>
                                    <select v-model="form.leave_type" id="leave_type" class="form-control" required>
                                        <option value="">Select Leave Type</option>
                                        <option value="Casual Leave">Casual Leave</option>
                                        <option value="Sick Leave">Sick Leave</option>
                                        <option value="Annual Leave">Annual Leave</option>
                                    </select>
                                </div>

                                <!-- From Date -->
                                <div class="mb-3">
                                    <label for="from_date" class="form-label">From Date</label>
                                    <input
                                        type="date"
                                        v-model="form.from_date"
                                        id="from_date"
                                        class="form-control"
                                        @change="updateToDate"
                                        required
                                    />
                                </div>

                                <!-- To Date -->
                                <div class="mb-3">
                                    <label for="to_date" class="form-label">To Date</label>
                                    <input
                                        type="date"
                                        v-model="form.to_date"
                                        id="to_date"
                                        class="form-control"
                                        required
                                    />
                                </div>

                                <!-- Partial Days -->
                                <div v-if="isMultipleDays" class="mb-3">
                                    <label for="partial_days" class="form-label">Partial Days</label>
                                    <select v-model="form.partial_days" id="partial_days" class="form-control" required>
                                        <option value="All Days">All Days</option>
                                        <option value="Start Day Only">Start Day Only</option>
                                        <option value="End Day Only">End Day Only</option>
                                        <option value="Start and End Day">Start and End Day</option>
                                    </select>
                                </div>

                                <!-- Duration -->
                                <div class="mb-3">
                                    <label for="duration" class="form-label">Duration</label>
                                    <select v-model="form.duration" id="duration" class="form-control" required>
                                        <option value="Full Day">Full Day</option>
                                        <option value="Half Day - Morning">Half Day - Morning</option>
                                        <option value="Half Day - Afternoon">Half Day - Afternoon</option>
                                        <!-- <option value="Specify Time">Specify Time</option> -->
                                    </select>
                                </div>

                                <!-- Specify Time -->
                                <div v-if="form.duration === 'Specify Time'" class="row">
                                    <div class="col-md-6">
                                        <label for="start_time" class="form-label">From Time</label>
                                        <input type="time" v-model="form.start_time" id="start_time" class="form-control" />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end_time" class="form-label">To Time</label>
                                        <input type="time" v-model="form.end_time" id="end_time" class="form-control" />
                                    </div>
                                </div>

                                <!-- Comments -->
                                <div class="mb-3">
                                    <label for="comments" class="form-label">Comments</label>
                                    <textarea v-model="form.comments" id="comments" class="form-control" rows="3"></textarea>
                                </div>

                                <!-- Proof -->
                                <div class="mb-3">
                                    <label for="proof" class="form-label">Proof (Optional)</label>
                                    <input type="file" @change="handleFileChange" id="proof" class="form-control" />
                                </div>

                                <button type="submit" class="btn btn-success">Update Leave</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "@@/Layouts/AppLayout.vue";
import axios from "axios";
import Swal from "sweetalert2";

export default {
    components: { AppLayout },
    props: {
        leave: Object,
    },
    data() {
        return {
            form: {
                leave_type: this.leave.leave_type,
                from_date: this.leave.from_date,
                to_date: this.leave.to_date,
                partial_days: this.leave.partial_days,
                duration: this.leave.duration,
                start_time: this.leave.start_time,
                end_time: this.leave.end_time,
                comments: this.leave.comments,
                proof: null, // Allow new proof file upload
            },
        };
    },
    computed: {
        isMultipleDays() {
            return this.form.from_date && this.form.to_date && this.form.from_date !== this.form.to_date;
        },
    },
    methods: {
        updateToDate() {
            this.form.to_date = this.form.from_date;
        },
        handleFileChange(event) {
            this.form.proof = event.target.files[0];
        },
        async updateLeave() {
            const formData = new FormData();
            for (const key in this.form) {
                formData.append(key, this.form[key]);
            }
            try {
                const response = await axios.post(`/employee/leaves/${this.leave.id}/update`, formData);
                Swal.fire({
                    icon: "success",
                    title: "Leave Updated",
                    text: "Your leave request has been updated successfully!",
                });
                this.$inertia.visit("/employee/leaves");
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error.response?.data?.message || "An error occurred while updating the leave.",
                });
            }
        },
    },
};
</script>

<style scoped>
.card {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}
.card-header {
    background-color: #007bff;
    color: white;
    padding: 10px;
}
.btn-success {
    background-color: #28a745;
    border: none;
}
</style>
