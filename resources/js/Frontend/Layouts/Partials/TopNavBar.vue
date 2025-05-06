
<template>
    <header class="header_section">
        <!-- Most top info -->
        <div class="header_top">
            <div class="container-fluid">
                <div class="contact_nav">
                    <div class="row">
                        <div class="col-lg-8">
                            <a href="#">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span class="p-2">+94 11 2 202 000 | +94 71 2 555 333</span>
                            </a>
                        </div>
                        <div class="col-lg-4 d-flex justify-content-end">
                            <a
                                href="https://facebook.com/weblook"
                                target="_blank"
                            >
                                <i class="fab fa-facebook-f px-2"></i>
                            </a>
                            <a
                                href="https://youtube.com/@weblookinternationalpvtltd6141"
                                target="_blank"
                            >
                                <i class="fab fa-youtube px-2"></i>
                            </a>
                            <a
                                href="https://www.linkedin.com/company/weblook-international-pvt-limited/"
                                target="_blank"
                            >
                                <i class="fab fa-linkedin-in px-2"></i>
                            </a>
                            <a
                                href="https://instagram.com/weblookinternational/"
                                target="_blank"
                            >
                                <i class="fab fa-instagram px-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End most top info -->

       <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light top-navbar header_bottom" data-toggle="sticky-onscroll">
    <div class="container">
        <div>

        <a class="navbar-brand" :href="route('index')">
            <img src="/images/Weblook-300x65.png" alt="Logo" width="170" />
        </a></div>


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarContent"
            aria-controls="navbarContent"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

         <!-- Collapsible Content -->
         <div class="collapse navbar-collapse"  id="navbarContent">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item" :class="{ active: isActive('Home/Index') }">
                    <a class="nav-link" :href="route('index')">Home</a>
                </li>
                <li class="nav-item" :class="{ active: isActive('Home/About') }">
                    <a class="nav-link" href="https://weblook.com/weblook-profile/">About Us</a>
                </li>
                <li class="nav-item" :class="{ active: isActive('Home/Contact') }">
                    <a class="nav-link" href="https://weblook.com/contact-us/">Contact Us</a>
                </li>

                <!-- Authenticated User Dropdown -->
                <li v-if="$page.props.employee" class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle d-flex align-items-center"
                        href="#"
                        id="userDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <img
                            :src="userProfileImage"
                            alt="Profile"
                            class="rounded-circle"
                            width="30"
                            height="30"
                            style="object-fit: cover"
                        />
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a
                                class="dropdown-item"
                                :href="route('profiledetails')">
                                Profile
                            </a>
                        </li>
                        <li>
                            <button
                                class="dropdown-item"
                                @click.prevent="employeelogout"
                            >
                                Logout
                            </button>
                        </li>
                    </ul>
                </li>

                <!-- Login Button (Visible if not authenticated) -->
                <li v-else class="nav-item">
                    <a class="btn btn-login" :href="route('frontlogin')">Login</a>
                </li>
            </ul>
    </div>
    </div>
</nav>

</header>
</template>
<script>
import Swal from "sweetalert2";
import axios from "axios";

export default {
    data() {
        return {
            localLeaveData: this.leave_data ? [...this.leave_data] : [],
            menuOpen: true,
        };
    },

    computed: {
        authenticated() {
            return this.$page.props.authenticated || false;
        },
        userProfileImage() {
            return "/images/blank-profile-picture-973460_960_720.webp";
        },
        encryptedEmployeeId() {
            return this.$page.props.user ? this.$page.props.user.id : null;
        },
        currentRoute() {
            return this.$page.component;
        },
    },
    methods: {
        toggleMenu() {
            this.menuOpen = !this.menuOpen;
        },
        handleSelection() {
            if (this.selectedOption === "apply") {
                this.applyLeave();
            } else if (this.selectedOption === "contact") {
                this.contactHR();
            }
            this.selectedOption = "";
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
                    this.$inertia.visit("/contact-hr");
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
        isActive(routeName) {
            return this.currentRoute === routeName;
        },
        employeelogout() {
            // Confirm logout action
            Swal.fire({
                title: "Are you sure?",
                text: "You will be logged out.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, logout!",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .post(route("employee.logout"))
                        .then(() => {
                            window.location.href = route("index");
                        })
                        .catch(() => {
                            Swal.fire(
                                "Error",
                                "Something went wrong during logout.",
                                "error"
                            );
                        });
                }
            });
        },
    },
    mounted() {
        // console.log("authenticated:", this.$page.props.authenticated);
        // console.log("user:", this.$page.props.user);
        // console.log("employee_id:", this.$page.props.employee_id);
    },
};

</script>
<style scoped>

.sticky.is-sticky {
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    z-index: 1000;
    width: 100%;
}

body {
    min-height: 1200px;
}

nav {
    background: #eaebec;
}

.btn-login {
    background-color: #d9534f;
    color: #fff;
    border: none;
    padding: 8px 20px;
    font-size: 14px;
    border-radius: 7px;
    text-decoration: none;
    transition: background-color 0.3s ease;
    margin-left: 10px;
    align-self: center;
}

.btn-login:hover {
    background-color: #c9302c;
    text-decoration: none;
    color: #fff;
}

.navbar-nav {
    align-items: center;
}

.navbar-collapse {
    display: flex;
    justify-content: flex-end;
}

.navbar-nav .nav-item {
    margin-right: 15;
}


.navbar-collapse {
    display: none;
    transition: all 0.3s ease;
}


.navbar-collapse.show {
    display: block;
}


.navbar-toggler:focus {
    outline: none;
    box-shadow: none;
}


.navbar-nav .nav-item .nav-link {
    padding: 10px 15px;
    transition: color 0.3s ease;
}


.navbar-nav .nav-item .nav-link:hover {
    color: #007bff;
}
</style>
