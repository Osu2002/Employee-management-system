<template>
    <AppLayout>
        <!-- User information section start -->
        <div>
            <div class="container">
                <div class="main-body">
                    <div class="row gutters-sm">
                        <!-- Cover Photo Section -->
                        <div class="cover-photo-container mb-3">
                            <img
                                :src="coverPhoto || defaultCoverPhoto"
                                alt="Cover Photo"
                                class="cover-photo"
                            />
                            <!-- <button
                                class="edit-cover-photo-btn"
                                @click="triggerFileInput"
                            >
                                <i class="fas fa-pen"></i>
                            </button> -->
                            <input
                                type="file"
                                ref="fileInput"
                                accept="image/*"
                                @change="uploadCoverPhoto"
                                style="display: none"
                            />

                            <!-- Profile Info Section in the Corner -->
                            <div class="profile-info-corner">
                                <div class="profile-photo-container">
                                    <img
                                        :src="
                                            employee_data?.media &&
                                            employee_data?.media.length > 0
                                                ? employee_data.media[0]
                                                      .original_url
                                                : '/images/blank-profile-picture.webp'
                                        "
                                        alt="Profile Image"
                                        class="profile-photo"
                                    />
                                </div>
                                <div class="profile-details">
                                    <h4 class="profile-name">
                                        {{ employee_data?.name || "N/A" }}
                                    </h4>
                                    <p class="profile-email">
                                        {{ employee_data?.email || "N/A" }}
                                    </p>
                                    <p class="profile-role">
                                        {{
                                            employee_data?.job_role?.name ||
                                            "Employee"
                                        }}
                                    </p>
                                </div>
                            </div>

                            <!-- Hamburger Menu -->
                            <!-- <div
                                class="menu-container mt-3 position-absolute"
                                style="top: 10px; right: 15px"
                            >
                                <div
                                    class="hamburger-circle position-relative"
                                    @click="toggleMenu"
                                >
                                    <div class="hamburger">
                                        <div
                                            class="bar1"
                                            :class="{
                                                change: menuOpen,
                                            }"
                                        ></div>
                                        <div
                                            class="bar2"
                                            :class="{
                                                change: menuOpen,
                                            }"
                                        ></div>
                                        <div
                                            class="bar3"
                                            :class="{
                                                change: menuOpen,
                                            }"
                                        ></div>
                                    </div>
                                </div>
                                <div
                                    class="menu-items"
                                    v-show="menuOpen"
                                    style="
                                        position: absolute;
                                        top: 50px;
                                        right: 0;
                                    "
                                >
                                    <ul class="list-unstyled">
                                        <li
                                            @click="applyLeave"
                                            class="menu-item"
                                        >
                                            Apply for Leave
                                        </li>
                                        <li
                                            @click="contactHR"
                                            class="menu-item"
                                        >
                                            Contact HR
                                        </li>
                                    </ul>
                                </div>
                            </div> -->
                        </div>

                        <!-- Profile Details -->
                        <div v-if="employee_data" class="col-md-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="mb-3">Employee Profile</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Field</th>
                                                    <th>Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(
                                                        value, key
                                                    ) in profileDetails"
                                                    :key="key"
                                                >
                                                    <td>
                                                        <strong>{{
                                                            key
                                                        }}</strong>
                                                    </td>
                                                    <td>{{ value }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documents Table -->
                        <div
                            class="col-12"
                            v-if="document_data && document_data.length > 0"
                        >
                            <div class="mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="mb-3">Documents</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Upload Date</th>
                                                        <th>Document Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        v-for="document in document_data"
                                                        :key="document.id"
                                                    >
                                                        <td>
                                                            {{
                                                                document.upload_date
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                document.document_name
                                                            }}
                                                        </td>
                                                        <td>
                                                            <a
                                                                :href="
                                                                    document
                                                                        .media?.[0]
                                                                        ?.original_url
                                                                "
                                                                class="btn btn-secondary btn-sm"
                                                                target="_blank"
                                                            >
                                                                View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Leaves Table -->
                        <div
                            class="col-12"
                            v-if="leave_data && leave_data.length > 0"
                        >
                            <div class="mb-3">
                                <div class="card shadow-sm rounded-3">
                                    <div
                                        class="card-header bg-light d-flex justify-content-between align-items-center"
                                    >
                                        <h5 class="mb-0 text-dark">
                                            Leave Applications
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-hover align-middle custom-table"
                                            >
                                                <thead class="custom-thead">
                                                    <tr>
                                                        <th class="text-center">
                                                            From
                                                        </th>
                                                        <th class="text-center">
                                                            To
                                                        </th>
                                                        <th class="text-center">
                                                            Leave Type
                                                        </th>
                                                        <th class="text-center">
                                                            Duration
                                                        </th>
                                                        <th class="text-center">
                                                            Status
                                                        </th>
                                                        <th class="text-center">
                                                            Instructions
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        v-for="leave in leave_data"
                                                        :key="leave.id"
                                                    >
                                                        <td class="text-center">
                                                            {{
                                                                leave.from_date
                                                            }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ leave.to_date }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{
                                                                leave.leave_type
                                                            }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ leave.duration }}
                                                        </td>
                                                        <td class="text-center">
                                                            <span
                                                                :class="{
                                                                    'badge bg-warning text-dark':
                                                                        leave.status ===
                                                                        'Pending',
                                                                    'badge bg-success text-white':
                                                                        leave.status ===
                                                                        'Approved',
                                                                    'badge bg-danger text-white':
                                                                        leave.status ===
                                                                        'Rejected',
                                                                }"
                                                            >
                                                                {{
                                                                    leave.status
                                                                }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div
                                                                v-if="
                                                                    leave.instructions
                                                                "
                                                                class="bg-light p-2 rounded"
                                                            >
                                                                {{
                                                                    leave.instructions
                                                                }}
                                                            </div>
                                                            <div
                                                                v-else
                                                                class="text-muted fst-italic"
                                                            >
                                                                No instructions
                                                                provided
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- User information section end -->
    </AppLayout>
</template>

<script>
import AppLayout from "@@/Layouts/AppLayout.vue";
import Swal from "sweetalert2";

export default {
    components: {
        AppLayout,
    },
    props: {
        employee_id: Number, // Pass employee ID as a prop
        errors: Object,
        employee_data: Object,
        leave_data: Array,
        document_data: Array,
    },
    data() {
        return {
            employee_id: this.employee_data?.id || null,
            localLeaveData: this.leave_data ? [...this.leave_data] : [],
            menuOpen: false, // Track hamburger menu state
            coverPhoto: "", // Custom cover photo
            defaultCoverPhoto: "/images/background6.png", // Default cover photo
        };
    },

    computed: {
        employee_id() {
            return this.employee_data?.id || null;
        },
        profileDetails() {
            return {
                "Full Name": this.employee_data?.name || "N/A",
                Email: this.employee_data?.email || "N/A",
                "Personal Email": this.employee_data?.personal_email || "N/A",
                Designation: this.employee_data?.job_role?.name || "N/A",
                NIC: this.employee_data?.nic || "N/A",
                "Home Telephone Line":
                    this.employee_data?.home_telephone || "N/A",
                Birthday: this.employee_data?.birthday || "N/A",
                Address: this.employee_data?.address || "N/A",
                Phone: this.employee_data?.phone || "N/A",
                "Join Date": this.employee_data?.join_date || "N/A",
                "End Date": this.employee_data?.end_date || "N/A",
            };
        },
    },
    mounted() {
        this.fetchCoverPhoto(); // Fetch cover photo when component is mounted
    },
    methods: {
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        async uploadCoverPhoto(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validate file type
            const validTypes = [
                "image/jpeg",
                "image/png",
                "image/jpg",
                "image/webp",
            ];
            if (!validTypes.includes(file.type)) {
                alert("Invalid file type. Please upload a JPEG or PNG image.");
                return;
            }

            // Validate file size (e.g., limit to 2MB)
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxSize) {
                alert(
                    "File size exceeds the 2MB limit. Please upload a smaller file."
                );
                return;
            }

            const reader = new FileReader();
            reader.onload = async (e) => {
                const img = new Image();
                img.src = e.target.result;
                img.onload = async () => {
                    const maxWidth = 1140;
                    const maxHeight = 300;
                    if (img.width > maxWidth || img.height > maxHeight) {
                        alert(
                            `Image dimensions exceed the allowed size (${maxWidth}x${maxHeight}px). Please resize your image.`
                        );
                        return;
                    }

                    const formData = new FormData();
                    formData.append("employee_id", this.employee_data.id);
                    formData.append("cover_photo", file);

                    try {
                        const response = await axios.post(
                            "/upload-cover-photo",
                            formData,
                            {
                                headers: {
                                    "Content-Type": "multipart/form-data",
                                },
                            }
                        );
                        this.coverPhoto = response.data.cover_photo;

                        location.reload();
                    } catch (error) {
                        console.error(
                            "Upload failed",
                            error.response?.data || error.message
                        );
                    }
                };
            };
            reader.readAsDataURL(file);
        },
        async fetchCoverPhoto() {
            try {
                const response = await axios.get(
                    `/employee/${this.employee_id}`
                );
                this.coverPhoto = response.data.cover_photo_url;
            } catch (error) {
                console.error(
                    error.response.data.error || "Failed to fetch data"
                );
            }
        },
        toggleMenu() {
            this.menuOpen = !this.menuOpen;
        },
        handleSelection() {
            if (this.selectedOption === "apply") {
                this.applyLeave();
            } else if (this.selectedOption === "contact") {
                this.contactHR();
            }
            this.selectedOption = ""; // Reset the selection
        },
        applyLeave() {
            Swal.fire({
                title: "Apply for Leave",
                text: "Would you like to proceed with leave application?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, Apply",
                cancelButtonText: "No, Cancel",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.visit("/employee/leaves");
                }
            });
        },
        contactHR() {
            Swal.fire({
                title: "Contact HR",
                text: "Would you like to contact HR?",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Yes, Contact",
                cancelButtonText: "No, Cancel",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.visit("/contact-hr"); // Replace with actual route
                }
            });
        },
        editLeave(leave) {
            this.$inertia.visit(`/employee/leaves/${leave.id}/edit`);
        },
        cancelLeave(leave) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to recover this leave request!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, keep it",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.delete(
                        route("employee.leaves.cancel", leave.id),
                        {
                            onSuccess: () => {
                                this.localLeaveData =
                                    this.localLeaveData.filter(
                                        (l) => l.id !== leave.id
                                    );
                                Swal.fire(
                                    "Canceled!",
                                    "Your leave request has been canceled.",
                                    "success"
                                );
                            },
                            onError: (errors) => {
                                Swal.fire(
                                    "Error!",
                                    errors.error ||
                                        "An error occurred while canceling the leave.",
                                    "error"
                                );
                            },
                        }
                    );
                }
            });
        },
    },
};
</script>

<style scoped>
/* Cover Photo Styles */
.cover-photo-container {
    position: relative;
    width: 100%;
    height: 250px;
    background-color: #f0f0f0;
    border-radius: 10px;
    overflow: hidden;
}

.cover-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.edit-cover-photo-btn {
    position: absolute;
    bottom: 15px;
    right: 15px;
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.edit-cover-photo-btn:hover {
    background-color: rgba(0, 0, 0, 0.9);
}

/* Profile Info Section */
.profile-info-corner {
    position: absolute;
    bottom: 20px;
    left: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    background-color: rgba(255, 255, 255, 0.8);
    padding: 10px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.profile-photo-container {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.profile-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-details {
    color: #333;
}

.profile-name {
    font-size: 16px;
    font-weight: bold;
    margin: 0;
}

.profile-email,
.profile-role {
    font-size: 14px;
    margin: 0;
    color: #555;
}

/* Button Section */
.button-section {
    margin-top: 20px;
}

.btn {
    padding: 10px 20px;
    font-size: 14px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-primary {
    background-color: #007bff;
    color: white;
    border: none;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
    border: none;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-info-corner {
        flex-direction: column;
        align-items: flex-start;
        padding: 15px;
        gap: 10px;
    }

    .profile-photo-container {
        width: 50px;
        height: 50px;
    }

    .profile-name {
        font-size: 14px;
    }

    .profile-email,
    .profile-role {
        font-size: 12px;
    }
}
/* Add the styling for the component */
.body {
    color: #1a202c;
    text-align: left;
    background-color: #e2e8f0;
}
.menu-container {
    position: relative;
}

.hamburger {
    cursor: pointer;
}

.bar1,
.bar2,
.bar3 {
    width: 25px;
    height: 4px;
    background-color: #000000;
    margin: 4px 0;
    transition: 0.4s;
}
.hamburger-circle {
    width: 50px;
    height: 50px;
    background-color: white;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
}
.bar1.change {
    transform: translateY(8px) rotate(-45deg);
}

.bar2.change {
    opacity: 0;
}

.bar3.change {
    transform: translateY(-8px) rotate(45deg);
}

.menu-items {
    background-color: rgb(245, 239, 233);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    padding: 10px;
    width: 150px;
    text-align: left;
}

.menu-item {
    padding: 8px 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.menu-item:hover {
    background-color: #c5e0ec;
}
.main-body {
    padding: 15px;
}

.card {
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.card-body {
    padding: 1rem;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
}

.table th {
    background-color: #f1f1f1;
}
.btn-primary {
    background-color: #0f73df;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #071420;
}
.table {
    margin-top: 15px;
}
.table th {
    background-color: #f8f9fa;
    font-weight: bold;
}
.table td {
    vertical-align: middle;
}
/* General Table Styles */
.custom-table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    margin-bottom: 1rem;
    font-size: 14px;
    color: #495057;
}

.custom-table th,
.custom-table td {
    padding: 12px;
    vertical-align: middle;
    text-align: center;
}

.custom-table thead th {
    font-weight: 600;
    background-color: #f1f3f5;
    border-bottom: 2px solid #dee2e6;
    color: #495057;
}

.custom-table tbody tr:hover {
    background-color: #f9f9f9;
}

.custom-table tbody td {
    border-top: 1px solid #dee2e6;
}

/* Badge Styles */
.badge {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 12px;
    display: inline-block;
    min-width: 80px;
    text-align: center;
}

/* Instructions Styling */
.bg-light {
    background-color: #f8f9fa !important;
}

.text-muted {
    color: #6c757d !important;
}

.text-secondary {
    color: #495057 !important;
}

/* Table Header Customization */
.custom-thead th {
    background-color: #e9ecef;
    color: #343a40;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .custom-table th,
    .custom-table td {
        font-size: 12px;
    }

    .badge {
        font-size: 12px;
    }
}

.ui-dropdown {
    width: 200px;
    padding: 10px;
    font-size: 14px;
    color: #333;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    appearance: none;
}

.ui-dropdown:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 4px rgba(0, 123, 255, 0.5);
}

.ui-dropdown option {
    color: #333;
    background-color: #fff;
    padding: 10px;
    .profile-card {
        position: relative;
        text-align: center;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .profile-header {
        position: relative;
    }

    .profile-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
    }

    .profile-info {
        margin-top: 10px;
    }

    .dropdown-container {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .ui-dropdown {
        width: 150px;
        padding: 8px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #fff;
        color: #333;
        appearance: none;
    }

    .ui-dropdown:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 4px rgba(0, 123, 255, 0.5);
    }

    .apply-button {
        margin-top: 15px;
    }

    .btn-primary {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }
}
</style>
