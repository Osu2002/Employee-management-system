<template>
    <AppLayout>
       <div class="login-container">
           <form @submit.prevent="submit" class="login-form">
               <h4 class="form-signin-heading text-center mb-4">Employee Login</h4>

               <!-- Employee ID Input -->
               <div class="mb-3">
                   <label for="index" class="form-label">Employee ID</label>
                   <input
                       id="index"
                       type="text"
                       class="form-control"
                       v-model="form.index"
                       placeholder="e.g., EMP5002"
                       required
                   />
               </div>

               <!-- Password Input -->
               <div class="mb-3">
                   <label for="password" class="form-label">Password</label>
                   <input
                       id="password"
                       type="password"
                       class="form-control"
                       v-model="form.password"
                       placeholder="Enter your password"
                       required
                   />
               </div>

               <!-- Submit Button -->
               <button
                   class="btn btn-lg btn-danger btn-block w-100"
                   type="submit"
                   :disabled="form.processing"
               >
                   {{ form.processing ? "Logging In..." : "Log In" }}
               </button>
           </form>
       </div>
   </AppLayout>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@@/Layouts/AppLayout.vue";
import Swal from "sweetalert2";

export default {
   components: {
       AppLayout,
   },
   data() {
       return {
           form: useForm({
               index: "", // Employee ID
               password: "", // Password
           }),
       };
   },
   methods: {
       submit() {
           // Validate Employee ID Format
           const empIdRegex = /^EMP\d+$/; // Match "EMP" followed by digits
           if (!empIdRegex.test(this.form.index)) {
               Swal.fire({
                   icon: "error",
                   title: "Invalid Employee ID",
                   text: 'Employee ID must start with "EMP" followed by numbers.',
               });
               return;
           }

           // Submit Login Data to the backend
           this.form.post(route("employee.login"), {
               onSuccess: () => {
                   Swal.fire({
                       icon: "success",
                       title: "Login Successful",
                       text: "Redirecting...",
                       timer: 2000,
                       timerProgressBar: true,
                       showConfirmButton: false,
                   });
               },
               onError: (errors) => {
                   // Handle backend validation errors (e.g., invalid credentials)
                   const errorMessage = errors && errors.error
                       ? errors.error // Extract the error message from the response
                       : "Invalid credentials. Please try again."; // Fallback message

                   Swal.fire({
                       icon: "error",
                       title: "Login Failed",
                       text: errorMessage,
                   });
               },
           });
       },
   },
};
</script>

<style scoped>
body {
   background-color: #f4f6f9;
   font-family: Arial, sans-serif;
}

.login-container {
   max-width: 400px;
   margin: 50px auto;
   padding: 20px;
   background-color: #ffffff;
   border-radius: 8px;
   box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
   animation: fadeIn 0.5s;
}

@keyframes fadeIn {
   from {
       opacity: 0;
   }
   to {
       opacity: 1;
   }
}

.login-form {
   display: flex;
   flex-direction: column;
}

.form-signin-heading {
   font-size: 1.8rem;
   font-weight: bold;
   color: #333;
}

.form-label {
   font-size: 0.9rem;
   font-weight: bold;
   color: #555;
   margin-bottom: 5px;
}

.form-control {
   border: 1px solid #ccc;
   border-radius: 5px;
   padding: 10px;
   font-size: 0.9rem;
   transition: border-color 0.3s;
}

.form-control:focus {
   border-color: #dc3545;
   box-shadow: 0 0 5px rgba(220, 53, 69, 0.5);
}

.btn {
   background-color: #dc3545;
   border: none;
   color: white;
   padding: 10px;
   border-radius: 5px;
   font-weight: bold;
   font-size: 1rem;
   cursor: pointer;
   transition: background-color 0.3s;
}

.btn:hover {
   background-color: #c82333;
}

.btn:disabled {
   background-color: #e0aeb2;
   cursor: not-allowed;
}
</style>
