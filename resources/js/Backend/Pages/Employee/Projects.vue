<template>
    <AppLayout>
      <div class="container">
        <!-- Processing Overlay -->
        <div v-if="isLoading" class="processing-overlay">
          <div class="spinner"></div>
          <p>Processing your request...</p>
        </div>

        <h1>Employee Projects Management</h1>
        <div v-if="projects.length > 0">
          <div
            v-for="project in projects"
            :key="project.id"
            class="project-card"
          >
            <div class="project-header">
              <h3>{{ project.project_name }}</h3>
              <div>
                <button
                  v-if="project.status === 0"
                  @click="approveProject(project.id)"
                  class="btn btn-success"
                  :disabled="isLoading"
                >
                  Approve
                </button>
              </div>
            </div>
            <p class="description">{{ project.project_description }}</p>
            <p>
              <strong>Skills:</strong> {{ parseJSON(project.skills).join(", ") }}
            </p>
            <p>
              <strong>Contributors:</strong>
              {{ parseJSON(project.contributors).join(", ") }}
            </p>
            <p>
              <strong>Status:</strong>
              <span
                :class="{
                  statusPending: project.status === 0,
                  statusApproved: project.status === 1,
                }"
              >
                {{ project.status === 0 ? "Pending" : "Approved" }}
              </span>
            </p>
          </div>
        </div>
        <div v-else>
          <p class="no-projects">No projects available for management.</p>
        </div>
      </div>
    </AppLayout>
  </template>
<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Swal from "sweetalert2";
import axios from "axios";

export default {
  components: {
    AppLayout,
  },
  props: {
    projects: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      projects: [...this.projects], // Initialize projects
      isLoading: false, // State to track loading
    };
  },
  methods: {
    parseJSON(jsonString) {
      try {
        return JSON.parse(jsonString || "[]");
      } catch (e) {
        console.error("Error parsing JSON:", e);
        return [];
      }
    },
    async approveProject(projectId) {
      this.isLoading = true; // Start loading
      try {
        await axios.patch(`/admin/projects/${projectId}/approve`);
        this.projects = this.projects.filter(
          (project) => project.id !== projectId
        );
        Swal.fire({
          title: "Project Approved!",
          text: "The project has been successfully approved.",
          icon: "success",
          confirmButtonText: "OK",
          confirmButtonColor: "#28a745",
        });
      } catch (error) {
        console.error(
          "Error approving project:",
          error.response?.data || error.message
        );
        Swal.fire({
          title: "Approval Error",
          text: "An error occurred while approving the project. Please try again.",
          icon: "error",
          confirmButtonText: "OK",
          confirmButtonColor: "#dc3545",
        });
      } finally {
        this.isLoading = false; // Stop loading
      }
    },
  },
};
</script>
<style scoped>
/* Processing Overlay */
.processing-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  color: white;
  font-size: 1.2rem;
}

/* Spinner Styles */
.spinner {
  border: 6px solid rgba(255, 255, 255, 0.3);
  border-top: 6px solid white;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
}

/* Spinner Animation */
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

/* Container Styles */
.container {
  padding: 20px;
  max-width: 900px;
  margin: auto;
}

h1 {
  font-size: 2.2rem;
  font-weight: bold;
  margin-bottom: 20px;
  color: #333;
  text-align: center;
}

.project-card {
  border: 1px solid #ddd;
  padding: 20px;
  margin-bottom: 20px;
  border-radius: 8px;
  background: #f9f9f9;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.project-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

h3 {
  font-size: 1.5rem;
  color: #333;
  margin: 0;
}

.description {
  font-size: 1rem;
  color: #555;
  margin-bottom: 10px;
}

.no-projects {
  font-size: 1.2rem;
  text-align: center;
  color: #777;
  margin-top: 30px;
}

.btn-success {
  background-color: #28a745;
  color: #fff;
  border: none;
  padding: 8px 12px;
  border-radius: 5px;
  font-size: 0.9rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-success:hover {
  background-color: #218838;
}

.statusPending {
  color: #ffa500;
  font-weight: bold;
}

.statusApproved {
  color: #28a745;
  font-weight: bold;
}
</style>
