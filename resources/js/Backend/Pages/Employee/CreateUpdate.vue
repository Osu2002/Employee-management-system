<template>
    <AppLayout>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="col-12 mb-4">
                            <div class="row">
                                <div class="col-1">
                                    <Link class="back-button" :href="route('employee', form.id)">
                                    <i class="back-icon fas fa-arrow-left p-2"></i>
                                    </Link>
                                </div>
                                <div v-if="employee" class="col-11 d-flex justify-content-end">
                                    <h5 class="m-0 text-center">
                                        {{ employee.name }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <h5>Employee</h5>
                        <p>Manage Employee Details</p>
                    </div>

                    <div class="card-body">
                        <form id="formAccountSettings" @submit.prevent="submit">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Employee Index:</label>
                                    <input class="form-control" type="text" id="emp_id" name="emp_id"
                                        v-model="form.emp_id" disabled />
                                    <div class="text-danger">
                                        {{ form.errors.name }}
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Full Name:</label>
                                    <input class="form-control" type="text" id="name" name="name" v-model="form.name" />
                                    <div class="text-danger">
                                        {{ form.errors.name }}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">Weblook Email:</label>
                                    <input class="form-control" type="email" id="email" name="email"
                                        v-model="form.email" />
                                    <div class="text-danger">
                                        {{ form.errors.email }}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="personal_email" class="form-label">Personal Email:</label>
                                    <input class="form-control" type="email" id="personal_email" name="personal_email"
                                        v-model="form.personal_email" />
                                    <div class="text-danger">
                                        {{ form.errors.personal_email }}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="designation" class="form-label">Designation:</label>
                                    <select class="form-control" id="designation" name="designation"
                                        v-model="form.designation" @change="onDesignationChange">
                                        <option value>
                                            -- Select Designation --
                                        </option>
                                        <option v-for="role in jobRoles" :key="role.id" :value="role.id">
                                            {{ role.name }}
                                        </option>
                                    </select>
                                    <div class="text-danger">
                                        {{ form.errors.designation }}
                                    </div>
                                </div>

                                <SelectInputComponent id="status" label="Status" :error="form.errors.status" :options="[
                                    {
                                        id: '1',
                                        name: 'Active',
                                    },
                                    {
                                        id: '0',
                                        name: 'Inactive',
                                    },
                                ]" v-model="form.status" />

                                <div class="mb-3 col-md-6" action="/action_page.php">
                                    <label for="join_date">Join Date:</label>
                                    <input class="form-control" type="date" id="join_date" name="join_date"
                                        v-model="form.join_date" />
                                    <div class="text-danger">
                                        {{ form.errors.join_date }}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6" action="/action_page.php">
                                    <label for="end _date">End Date:</label>
                                    <input class="form-control" type="date" id="end _date" name="end _date"
                                        v-model="form.end_date" />
                                    <div class="text-danger">
                                        {{ form.errors.end_date }}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="nic" class="form-label">NIC:</label>
                                    <input class="form-control" type="text" id="text" name="nic" v-model="form.nic" />
                                    <div class="text-danger">
                                        {{ form.errors.nic }}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="phone" class="form-label">Phone Number:</label>
                                    <input class="form-control" type="text" id="phone" name="phone"
                                        v-model="form.phone" />
                                    <div class="text-danger">
                                        {{ form.errors.phone }}
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="home_telephone" class="form-label">Home Telephone Line:</label>
                                    <input class="form-control" type="text" id="home_telephone" name="home_telephone"
                                        v-model="form.home_telephone" />
                                    <div class="text-danger">
                                        {{ form.errors.home_telephone }}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="birthday" class="form-label">Birthday:</label>
                                    <input class="form-control" type="date" id="birthday" name="birthday"
                                        v-model="form.birthday" />
                                    <div class="text-danger">
                                        {{ form.errors.birthday }}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="address" class="form-label">Address:</label>
                                    <input class="form-control" type="text" id="address" name="address"
                                        v-model="form.address" />
                                    <div class="text-danger">
                                        {{ form.errors.address }}
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="leave_types" class="form-label">Leave Types and Count:</label>
                                    <div v-if="
                                        form.leave_types &&
                                        form.leave_types.length > 0
                                    ">
                                        <ul class="list-group">
                                            <li v-for="(
                                                    leave, index
                                                ) in form.leave_types" :key="index"
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <div class="d-flex flex-column">
                                                    <span>
                                                        <strong>Type:</strong>
                                                        {{ leave.type }}
                                                    </span>
                                                    <span>
                                                        <strong>
                                                            Default Allowed:
                                                        </strong>
                                                        {{
                                                            leave.allowed_leaves
                                                        }}
                                                    </span>
                                                    <span>
                                                        <strong>
                                                            Allowed Leaves:
                                                        </strong>
                                                        {{
                                                            leave.user_allowed_leaves
                                                        }}
                                                    </span>
                                                    <span>
                                                        <strong>
                                                            Used Leaves:
                                                        </strong>
                                                        {{ leave.used_leaves }}
                                                    </span>
                                                    <span>
                                                        <strong>
                                                            Available Leaves:
                                                        </strong>
                                                        {{
                                                            leave.available_leaves
                                                        }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <input type="text" v-model="form.leave_types[
                                                        index
                                                    ]
                                                        .user_allowed_leaves
                                                        " class="form-control form-control-sm w-auto"
                                                        placeholder="Allowed Days" />
                                                    <div v-if="
                                                        errors[
                                                        `leave_types.${index}.user_allowed_leaves`
                                                        ]
                                                    " class="text-danger">
                                                        Please enter valid number
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div v-else>
                                        <span class="text-muted">
                                            No leave types available for this
                                            designation.
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="profile_image" class="form-label me-3">Profile Image:</label>
                                    <br />
                                    <FileInputComponent :isRequired="false" id="profile_image" :prvImage="profileImage"
                                        v-model="form.profile_image" />
                                    <div class="text-danger">
                                        {{ form.errors.profile_image }}
                                    </div>
                                </div>
                                <br />

                                <h5>Authentication</h5>
                                <div class="mb-3 col-md-6">
                                    <label for="password" class="form-label">Password:</label>
                                    <div class="input-group">
                                        <!-- Bind type to showPassword -->
                                        <input :type="showPassword
                                            ? 'text'
                                            : 'password'
                                            " class="form-control" id="password" name="password"
                                            v-model="form.password" />
                                        <!-- Button to toggle visibility -->
                                        <button type="button" class="btn btn-outline-secondary"
                                            @click="togglePasswordVisibility">
                                            <i :class="showPassword
                                                ? 'fas fa-eye-slash'
                                                : 'fas fa-eye'
                                                "></i>
                                        </button>
                                    </div>
                                    <div class="text-danger">
                                        {{ form.errors.password }}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password:</label>
                                    <div class="input-group">
                                        <input :type="showPasswordConfirm
                                            ? 'text'
                                            : 'password'
                                            " class="form-control" id="password_confirmation"
                                            name="password_confirmation" v-model="form.password_confirmation" />
                                        <button type="button" class="btn btn-outline-secondary" @click="togglePasswordConfirmVisibility
                                            ">
                                            <i :class="showPasswordConfirm
                                                ? 'fas fa-eye-slash'
                                                : 'fas fa-eye'
                                                "></i>
                                        </button>
                                    </div>
                                    <div class="text-danger">
                                        {{ form.errors.password_confirmation }}
                                    </div>
                                </div>

                                <!-- Display QR code image -->
                                <div v-if="employee">
                                    <img :src="qrCodeImageUrl" alt="QR Code" />
                                </div>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-main me-2" :disabled="form.processing">
                                    {{ employee ? "Update" : "Save" }}
                                </button>
                                <Link class="btn btn-outline-danger" :href="route('employee', form.id)"
                                    style="margin-right: 10px">Cancel</Link>
                                <!-- need to wrrite cancel rout -->
                                <Link type="button" v-if="employee" :href="route('employee.document', form.id)"
                                    class="btn btn-outline-danger" style="margin-right: 10px">Document</Link>

                                <Link type="button" v-if="employee" :href="route('employee.leaves.index', form.id)
                                    " class="btn btn-outline-danger" style="margin-right: 10px">Leaves</Link>

                                <Link type="button" v-if="employee" :href="route('employee.certificate', form.id)
                                    " class="btn btn-outline-danger" style="margin-right: 10px">Certificate</Link>

                                <Link type="button" v-if="employee" :href="route('employee.bank_details', form.id)
                                    " class="btn btn-outline-danger" style="margin-right: 10px">Bank Details</Link>

                                <Link type="button" v-if="employee" :href="route(
                                    'employee.service_letter',
                                    form.id
                                )
                                    " class="btn btn-outline-danger" style="margin-right: 10px">Service Letter</Link>
                                <Link type="button" v-if="employee" :href="route(
                                    'admin.projects',
                                    form.id
                                )
                                    " class="btn btn-outline-danger" style="margin-right: 10px">Projects</Link>
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
import QRCode from "qrcode";

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
        employeeUrl: String,
        isMultiple: {
            type: Boolean,
            default: false,
        },
        jobRoles: Array, // Pass job roles as a prop
    },

    data() {
        return {
            form: useForm({
                id: "",
                name: "",
                email: "",
                personal_email: "",
                designation: "",
                join_date: "",
                end_date: "",
                status: "",
                nic: "",
                phone: "",
                home_telephone: "",
                birthday: "",
                address: "",
                leave_types: [], // Holds leave details
                profile_image: "",
                password: "",
                password_confirmation: "",
            }),
            qrCodeData: "", // Store QR code data here
            qrCodeImageUrl: "", // Store QR code image URL here
            showPassword: false,
            showPasswordConfirm: false,
        };
    },

    mounted() {
        if (this.employee) {
            this.generateQRCode();

            this.form.id = this.employee.id;
            this.form.emp_id = "EMP" + (this.employee.id + 5000);
            this.form.name = this.employee.name;
            this.form.email = this.employee.email;
            this.form.designation = this.employee.designation;
            this.form.join_date = this.employee.join_date;
            this.form.end_date = this.employee.end_date;
            this.form.status = this.employee.status;
            this.form.personal_email = this.employee.personal_email;
            this.form.nic = this.employee.nic;
            this.form.home_telephone = this.employee.home_telephone;
            this.form.birthday = this.employee.birthday;
            this.form.address = this.employee.address;
            this.form.phone = this.employee.phone;
            // this.form.password = this.employee.password;
            // this.form.password_confirmation = this.employee.password;

            const selectedJobRole = this.jobRoles.find(
                (role) => role.id === this.form.designation
            );
            this.form.leave_types = this.employee.leave_types;
            // if (selectedJobRole) {
            //     this.form.leave_types = this.employee.leave_types.map(
            //         (leave) => ({
            //             type: leave.type,
            //             default_allowed_leaves:
            //                 leave.default_allowed_leaves || 0,
            //             user_allowed_leaves:
            //                 leave.user_allowed_leaves ||
            //                 leave.default_allowed_leaves ||
            //                 0,
            //             used_leaves: leave.used_leaves || 0,
            //             available_leaves:
            //                 (leave.user_allowed_leaves ||
            //                     leave.default_allowed_leaves ||
            //                     0) - (leave.used_leaves || 0),
            //         })
            //     );
            // } else {
            //     this.form.leave_types = [];
            // }
        }
    },
    computed: {
    profileImage() {
        return this.employee?.media.find(m => 
            ['employee_profil_image', 'employee_profile_image'].includes(m.collection_name)
        )?.original_url || "";
    }
},
    watch: {
        "form.leave_types": {
            handler(newLeaveTypes) {
                if (newLeaveTypes && Array.isArray(newLeaveTypes)) {
                    newLeaveTypes.forEach((leave) => {
                        leave.available_leaves =
                            leave.user_allowed_leaves - leave.used_leaves;
                    });
                }
            },
            deep: true,
        },
    },

    methods: {
        togglePasswordVisibility() {
            this.showPassword = !this.showPassword;
        },
        togglePasswordConfirmVisibility() {
            this.showPasswordConfirm = !this.showPasswordConfirm;
        },
        onDesignationChange() {
            const selectedRole = this.jobRoles.find(
                (role) => role.id === this.form.designation
            );

            if (selectedRole && selectedRole.leave_types) {
                this.form.leave_types = selectedRole.leave_types.map(
                    (leave) => {
                        let existingLeave = 0;
                        if (this.form.leave_types) {
                            existingLeave = this.form.leave_types.find(
                                (lt) => lt.type === leave.type
                            );
                        }

                        return {
                            type: leave.type,
                            default_allowed_leaves:
                                leave.default_allowed_leaves || 0,
                            allowed_leaves:
                                existingLeave?.allowed_leaves ||
                                leave.allowed_leaves ||
                                leave.default_allowed_leaves ||
                                0,
                            used_leaves:
                                existingLeave?.used_leaves ||
                                leave.used_leaves ||
                                0,
                            available_leaves:
                                (existingLeave?.allowed_leaves ||
                                    leave.allowed_leaves ||
                                    leave.default_allowed_leaves ||
                                    0) -
                                (existingLeave?.used_leaves ||
                                    leave.used_leaves ||
                                    0),
                        };
                    }
                );
            } else {
                this.form.leave_types = [];
            }
        },

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

        submit() {
            const payload = {
                ...this.form,
                leave_types: this.form.leave_types.map((leave) => ({
                    type: leave.type,
                    default_allowed_leaves: leave.default_allowed_leaves,
                    allowed_leaves: leave.allowed_leaves,
                    used_leaves: leave.used_leaves,
                    available_leaves: leave.available_leaves,
                })),
            };

            console.log("Submitting payload:", payload);

            this.form.post(
                this.employee
                    ? route("employee.update", payload)
                    : route("employee.store", payload)
            );
        },
    },
};
</script>
