<template>
    <AppLayout>
        <div class="bg-container">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow-lg">
                            <div class="card-header text-center">
                                <h4 class="mb-0">Apply for Leave</h4>
                            </div>

                            <div class="card-body">
                                <form @submit.prevent="submitLeave">
                                    <!-- Leave Type -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label
                                                for="leave_type"
                                                class="form-label"
                                                >Leave Type</label
                                            >
                                            <select
                                                v-model="form.leave_type"
                                                id="leave_type"
                                                class="form-select"
                                                required
                                                @change="updateAvailableLeaves"
                                            >
                                                <option value="" disabled>
                                                    Select Leave Type
                                                </option>
                                                <option
                                                    v-for="(
                                                        leave, index
                                                    ) in user.leave_types"
                                                    :key="index"
                                                    :value="leave.type"
                                                >
                                                    {{ leave.type }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label"
                                                >Available Leaves</label
                                            >
                                            <input
                                                type="text"
                                                class="form-control"
                                                :value="form.available_leaves"
                                                readonly
                                            />
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label
                                                for="from_date"
                                                class="form-label"
                                                >From Date</label
                                            >
                                            <input
                                                type="date"
                                                v-model="form.from_date"
                                                id="from_date"
                                                class="form-control"
                                                @change="updateToDate"
                                                required
                                            />
                                        </div>

                                        <div class="col-md-6">
                                            <label
                                                for="to_date"
                                                class="form-label"
                                                >To Date</label
                                            >
                                            <input
                                                type="date"
                                                v-model="form.to_date"
                                                id="to_date"
                                                class="form-control"
                                                @change="calculateDuration"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div
                                        class="d-flex justify-content-between mb-4"
                                    >
                                        <div
                                            v-if="isMultipleDays"
                                            class="col-md-5"
                                        >
                                            <label
                                                for="partial_days"
                                                class="form-label"
                                                >Partial Days</label
                                            >
                                            <select
                                                v-model="form.partial_days"
                                                id="partial_days"
                                                class="form-select"
                                                required
                                            >
                                                <option value="" disabled>
                                                    -- Select --
                                                </option>
                                                <option value="All Days">
                                                    All Days
                                                </option>
                                                <option value="Start Day Only">
                                                    Start Day Only
                                                </option>
                                                <option value="End Day Only">
                                                    End Day Only
                                                </option>
                                                <option
                                                    value="Start and End Day"
                                                >
                                                    Start and End Day
                                                </option>
                                            </select>
                                        </div>

                                        <div
                                            v-if="showDurationSection"
                                            class="col-md-5"
                                        >
                                            <label
                                                for="duration"
                                                class="form-label"
                                                >Duration</label
                                            >
                                            <select
                                                v-model="form.duration"
                                                id="duration"
                                                class="form-select"
                                                required
                                            >
                                                <option value="Full Day">
                                                    Full Day
                                                </option>
                                                <option
                                                    value="Half Day - Morning"
                                                >
                                                    Half Day - Morning
                                                </option>
                                                <option
                                                    value="Half Day - Afternoon"
                                                >
                                                    Half Day - Afternoon
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="comments" class="form-label"
                                            >Comments</label
                                        >
                                        <textarea
                                            v-model="form.comments"
                                            id="comments"
                                            class="form-control"
                                            rows="3"
                                            placeholder="Add your comments here..."
                                        ></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="proof" class="form-label"
                                            >Proof (Optional)</label
                                        >
                                        <input
                                            type="file"
                                            @change="handleFileChange"
                                            id="proof"
                                            class="form-control"
                                        />
                                    </div>

                                    <!-- Loading Spinner -->
                                    <div
                                        v-if="loading"
                                        class="text-center my-3"
                                    >
                                        <div
                                            class="spinner-border text-primary"
                                            role="status"
                                        >
                                            <span
                                                class="visually-hidden"
                                            ></span>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <button
                                        type="submit"
                                        class="btn gradient-btn btn-rounded"
                                        :disabled="loading"
                                    >
                                        Apply
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "@@/Layouts/AppLayout.vue";
import Swal from "sweetalert2";
import { Link, useForm } from "@inertiajs/inertia-vue3";

export default {
    components: {
        AppLayout,
    },
    data() {
        return {
            loading: false,
            form: useForm({
                employee_id: this.$page.props.employee_id || "",
                leave_type: "",
                available_leaves: null,
                from_date: "",
                to_date: "",
                partial_days: "",
                duration: "",
                start_time: "",
                end_time: "",
                comments: "",
                proof: null,
            }),
        };
    },
    props: {
        user: Object,
        employee_id: Number,
    },
    computed: {
        isMultipleDays() {
            return (
                this.form.from_date &&
                this.form.to_date &&
                this.form.from_date !== this.form.to_date
            );
        },
        showDurationSection() {
            // Show duration section if "From Date" and "To Date" are the same or "Partial Days" is "All Days".
            return (
                (this.form.from_date === this.form.to_date &&
                    this.form.from_date !== "") ||
                this.form.partial_days === "All Days"
            );
        },
    },
    methods: {
        updateAvailableLeaves() {
            const selectedLeave = this.user.leave_types.find(
                (leave) => leave.type === this.form.leave_type
            );
            if (selectedLeave) {
                this.form.available_leaves = selectedLeave.available_leaves;
            } else {
                this.form.available_leaves = null;
            }
        },
        updateToDate() {
            this.form.to_date = this.form.from_date;
            this.calculateDuration();
        },
        calculateDuration() {
            // if (!this.form.from_date || !this.form.to_date) return 0;

            // const fromDate = new Date(this.form.from_date);
            // const toDate = new Date(this.form.to_date);
            // let totalDays = Math.ceil(
            //     (toDate - fromDate) / (1000 * 60 * 60 * 24) + 1
            // );

            // if (this.form.partial_days === "Start Day Only") totalDays -= 0.5;
            // if (this.form.partial_days === "End Day Only") totalDays -= 0.5;
            // if (this.form.partial_days === "Start and End Day")
            //     totalDays -= 1;

            // if (
            //     this.form.duration === "Half Day - Morning" ||
            //     this.form.duration === "Half Day - Afternoon"
            // )
            //     totalDays = 0.5;

            return totalDays;
        },
        handleFileChange(event) {
            this.form.proof = event.target.files[0];
        },
        submitLeave() {
            this.loading = true; // Start loading

            this.form.post(route("leave.store"), {
                onSuccess: () => {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: "Your leave request has been submitted successfully!",
                        confirmButtonText: "OK",
                    });
                },
                onError: () => {
                    this.loading = false;

                    Swal.fire({
                        icon: "error",
                        title: "Error",

                        confirmButtonText: "Try Again",
                    });
                },
            });
        },
    },
};
</script>

<style scoped>
.bg-container {
    background-size: cover;
    background-position: center;
    padding: 50px 0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-container::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: inherit;
    /* Inherit the background image from the parent */
    background-size: cover;
    background-position: center;
    filter: blur(8px);
    /* Apply the blur effect */

    /* Ensure the blurred image is behind the content */
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.card-header {
    background-color: #bccae4;
    color: black;
    font-weight: bold;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    padding: 15px;
}

.form-select,
.form-control {
    border-radius: 8px;
    margin-left: 12px;
    border: 1px solid #ced4da;
    transition: all 0.3s ease;
    display: block;
}

.form-select:focus,
.form-control:focus {
    border-color: #ff0000;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

textarea {
    resize: none;
}

.btn-primary {
    background-color: #c79375;
    border: none;
    font-weight: bold;
    border-radius: 0px;
    padding: 10px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-primary:hover {
    background-color: #8c98a5;
    transform: translateY(-2px);
}

.gradient-btn {
    background-color: #bccae4;
    color: black;
    border: none;
    font-weight: bold;
    border-radius: 0px;
    padding: 8px 30px;
    width: 150px;
    font-size: 19px;
    text-align: center;
    transition: transform 0.2s ease, box-shadow 0.3s ease;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    display: block;
    margin: 20px auto;
}

.gradient-btn:hover {
    transform: scale(1.05);
    box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.2);
}

.gradient-btn:focus {
    outline: none;
    box-shadow: 0px 0px 5px #ff5202;
}

.btn-rounded {
    border-radius: 10px;
}
</style>
