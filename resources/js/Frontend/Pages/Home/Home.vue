<template>
    <AppLayout>
        <!-- User information section start -->
        <div>
            <div class="container">
                <div class="main-body">
                    <div class="row gutters-sm">
                        <!-- Cover Photo Section -->
                        <div class="cover-photo-container mb-3">
                            <img :src="coverPhoto || defaultCoverPhoto" alt="Cover Photo" class="cover-photo" />
                            <button v-if="$page.props.employee" class="edit-cover-photo-btn" @click="triggerFileInput">
                                <i class="fas fa-pen"></i>
                            </button>
                            <input type="file" ref="fileInput" accept="image/*" @change="uploadCoverPhoto"
                                style="display: none" />

                            <!-- Profile Info Section in the Corner -->
                            <div class="profile-info-corner">
                                <div class="profile-photo-container">
                                    <img :src="employee_data?.media &&
                                        employee_data?.media.length > 0
                                        ? employee_data.media[0]
                                            .original_url
                                        : '/images/blank-profile-picture.webp'
                                        " alt="Profile Image" class="profile-photo" />
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

                            <div v-if="$page.props.employee" class="menu-container mt-3 position-absolute"
                                style="top: 10px; right: 15px">
                                <div class="hamburger-circle position-relative" @click="toggleMenu">
                                    <div class="hamburger">
                                        <div class="bar1" :class="{
                                            change: menuOpen,
                                        }"></div>
                                        <div class="bar2" :class="{
                                            change: menuOpen,
                                        }"></div>
                                        <div class="bar3" :class="{
                                            change: menuOpen,
                                        }"></div>
                                    </div>
                                </div>
                                <div class="menu-items" v-show="menuOpen" style="
                                        position: absolute;
                                        top: 50px;
                                        right: 0;
                                    ">
                                    <ul class="list-unstyled m-0">
                                        <li @click="applyLeave" class="menu-item">
                                            Apply for Leave
                                        </li>
                                        <li @click="contactHR" class="menu-item">
                                            Contact HR
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Details -->
                        <div class="col-12">
                            <div class="mb-3">
                                <div class="card shadow-sm rounded-3">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 text-dark">
                                            Employee Details
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr v-for="(
                                                            value, key
                                                        ) in profileDetails" :key="key">
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
                        </div>
                        <!-- Processing Overlay -->
                        <div v-if="isLoading" class="processing-overlay">
                            <div class="spinner"></div>
                            <p>Processing your request...</p>
                        </div>
                        <!-- Projects -->
                        <div class="col-md-12" v-if="$page.props.employee">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <!-- Header Section -->
                                    <div style="
                                            display: flex;
                                            justify-content: space-between;
                                            align-items: center;
                                            padding: 20px;
                                            border-bottom: 1px solid #f0f0f0;
                                        ">
                                        <h5 class="mb-3">Projects</h5>

                                        <button v-if="$page.props.employee" class="btn btn-primary"
                                            @click="showAddProjectModal" style="
                                                display: flex;
                                                align-items: center;
                                                gap: 8px;
                                                background-color: #007bff;
                                                color: #fff;
                                                border: none;
                                                border-radius: 4px;
                                                padding: 10px 20px;
                                                font-size: 1rem;
                                                font-weight: bold;
                                                cursor: pointer;
                                                transition: background-color
                                                    0.3s ease;
                                            ">
                                            <i class="fas fa-plus" style="font-size: 1.2rem"></i>
                                            Add Project
                                        </button>
                                    </div>

                                    <!-- Projects List -->
                                    <div v-if="filteredProjects.length > 0" style="
                                            padding: 20px;
                                            display: flex;
                                            flex-direction: column;
                                            gap: 20px;
                                        ">
                                        <div v-for="project in filteredProjects" :key="project.id" style="
                                                background-color: #fdfdfd;
                                                border: 1px solid #e3e3e3;
                                                border-radius: 12px;
                                                box-shadow: 0 4px 8px
                                                    rgba(0, 0, 0, 0.05);
                                                padding: 25px;
                                                display: flex;
                                                flex-direction: column;
                                                gap: 15px;
                                                position: relative;
                                            ">
                                            <!-- Project Header -->
                                            <div style="
                                                    display: flex;
                                                    justify-content: space-between;
                                                    align-items: center;
                                                ">
                                                <h6 style="
                                                        font-size: 1.6rem;
                                                        font-weight: bold;
                                                        color: #333;
                                                        margin: 0;
                                                    ">
                                                    {{ project.project_name }}
                                                </h6>
                                                <span :style="{
                                                    color:
                                                        project.status === 0
                                                            ? '#ff9800'
                                                            : '#4caf50',
                                                    fontWeight: 'bold',
                                                    fontSize: '1rem',
                                                }">
                                                    {{
                                                        project.status === 0
                                                            ? "Waiting for Approval"
                                                            : "Approved"
                                                    }}
                                                </span>
                                            </div>

                                            <!-- Project Description -->
                                            <p style="
                                                    font-size: 1rem;
                                                    color: #666;
                                                    line-height: 1.6;
                                                    margin-bottom: 15px;
                                                ">
                                                {{
                                                    project.project_description
                                                }}
                                            </p>

                                            <!-- Project Skills -->
                                            <div v-if="project.skills" style="margin-bottom: 15px">
                                                <h6 style="
                                                        font-size: 1rem;
                                                        font-weight: bold;
                                                        margin-bottom: 8px;
                                                    ">
                                                    Skills:
                                                </h6>
                                                <div style="
                                                        display: flex;
                                                        flex-wrap: wrap;
                                                        gap: 10px;
                                                    ">
                                                    <span v-for="skill in JSON.parse(
                                                        project.skills
                                                    )" :key="skill" style="
                                                            background-color: #3f51b5;
                                                            color: white;
                                                            font-size: 0.85rem;
                                                            padding: 8px 14px;
                                                            border-radius: 20px;
                                                            font-weight: 600;
                                                            text-transform: capitalize;
                                                            cursor: pointer;
                                                            transition: background-color
                                                                0.3s ease;
                                                        " @mouseover="(event) =>
                                                        (event.target.style.backgroundColor =
                                                            '#303f9f')
                                                            " @mouseout="(event) =>
                                                            (event.target.style.backgroundColor =
                                                                '#3f51b5')
                                                                ">
                                                        {{ skill }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Project Contributors -->
                                            <div v-if="project.contributors" style="margin-bottom: 15px">
                                                <h6 style="
                                                        font-size: 1rem;
                                                        font-weight: bold;
                                                        margin-bottom: 8px;
                                                    ">
                                                    Contributors:
                                                </h6>
                                                <p style="
                                                        font-size: 0.9rem;
                                                        color: #555;
                                                        margin: 0;
                                                    ">
                                                    {{
                                                        JSON.parse(
                                                            project.contributors
                                                        ).join(", ")
                                                    }}
                                                </p>
                                            </div>

                                            <!-- Project Media -->
                                            <div v-if="project.media" style="margin-bottom: 15px">
                                                <h6 style="
                                                        font-size: 1rem;
                                                        font-weight: bold;
                                                        margin-bottom: 8px;
                                                    ">
                                                    Media:
                                                </h6>
                                                <div style="
                                                        display: flex;
                                                        flex-wrap: wrap;
                                                        gap: 10px;
                                                    ">
                                                    <img v-for="media in project.media" :src="media.original_url
                                                        " alt="Media" style="
                                                            width: 100px;
                                                            height: 100px;
                                                            border-radius: 10px;
                                                            object-fit: cover;
                                                            border: 1px solid
                                                                #ddd;
                                                        " />
                                                </div>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div style="
                                                    display: flex;
                                                    justify-content: flex-end;
                                                    gap: 10px;
                                                ">
                                                <button @click="
                                                    editProject(project)
                                                    " style="
                                                        display: flex;
                                                        align-items: center;
                                                        gap: 5px;
                                                        background-color: #2196f3;
                                                        color: white;
                                                        padding: 8px 14px;
                                                        font-size: 0.9rem;
                                                        border: none;
                                                        border-radius: 6px;
                                                        cursor: pointer;
                                                        transition: background-color
                                                            0.3s ease;
                                                    " @mouseover="(event) =>
                                                    (event.target.style.backgroundColor =
                                                        '#1976d2')
                                                        " @mouseout="(event) =>
                                                        (event.target.style.backgroundColor =
                                                            '#2196f3')
                                                            ">
                                                    <i class="fas fa-edit"></i>
                                                    Edit
                                                </button>
                                                <button @click="
                                                    deleteProject(
                                                        project.id
                                                    )
                                                    " style="
                                                        display: flex;
                                                        align-items: center;
                                                        gap: 5px;
                                                        background-color: #f44336;
                                                        color: white;
                                                        padding: 8px 14px;
                                                        font-size: 0.9rem;
                                                        border: none;
                                                        border-radius: 6px;
                                                        cursor: pointer;
                                                        transition: background-color
                                                            0.3s ease;
                                                    " @mouseover="(event) =>
                                                    (event.target.style.backgroundColor =
                                                        '#d32f2f')
                                                        " @mouseout="(event) =>
                                                        (event.target.style.backgroundColor =
                                                            '#f44336')
                                                            ">
                                                    <i class="fas fa-trash"></i>
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- No Projects Available -->
                                    <div v-else style="
                                            padding: 20px;
                                            text-align: center;
                                            color: #777;
                                            font-size: 1rem;
                                        ">
                                        No projects available
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Add Project Modal -->
                        <div v-if="addProjectModal" class="modal fade show d-block" style="
                                background: rgba(0, 0, 0, 0.5);
                                position: fixed;
                                top: 0;
                                left: 0;
                                width: 100%;
                                height: 100%;
                                z-index: 1050;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            ">
                            <div class="modal-dialog modal-lg" style="
                                    max-width: 60%;
                                    max-height: 90%;
                                    overflow-y: auto;
                                    border-radius: 8px;
                                ">
                                <div class="modal-content" style="border-radius: 8px; overflow: hidden">
                                    <div class="modal-header" style="
                                            display: flex;
                                            justify-content: space-between;
                                            align-items: center;
                                            padding: 1rem;
                                            border-bottom: 1px solid #ddd;
                                        ">
                                        <h5 style="
                                                margin: 0;
                                                font-size: 1.25rem;
                                            ">
                                            Add Project
                                        </h5>
                                        <button type="button" class="btn-close" @click="hideAddProjectModal" style="
                                                background: none;
                                                border: none;
                                                font-size: 1.25rem;
                                                cursor: pointer;
                                            ">
                                            &times;
                                        </button>
                                    </div>
                                    <div class="modal-body" style="
                                            padding: 1rem;
                                            overflow-y: auto;
                                            max-height: calc(100vh - 160px);
                                        ">
                                        <form @submit.prevent="addProject">
                                            <!-- Project Name -->
                                            <div class="mb-3" style="margin-bottom: 1rem">
                                                <label style="
                                                        display: block;
                                                        font-weight: 600;
                                                        margin-bottom: 0.5rem;
                                                    ">Project Name
                                                    <span style="color: red">*</span></label>
                                                <input type="text" v-model="projectName" style="
                                                        width: 80%;
                                                        padding: 1rem;
                                                        border: 1px solid #ddd;
                                                        border-radius: 4px;
                                                    " required />
                                            </div>

                                            <!-- Description -->
                                            <div class="mb-3" style="margin-bottom: 1rem">
                                                <label style="
                                                        display: block;
                                                        font-weight: 600;
                                                        margin-bottom: 0.5rem;
                                                    ">Description</label>
                                                <textarea v-model="projectDescription" style="
                                                        width: 80%;
                                                        padding: 0.5rem;
                                                        border: 1px solid #ddd;
                                                        border-radius: 4px;
                                                        height: 100px;
                                                    " maxlength="2000"></textarea>
                                                <small style="
                                                        display: block;
                                                        color: #666;
                                                    ">{{
                                                        projectDescription.length
                                                    }}/2000</small>
                                            </div>

                                            <!-- Skills Section -->
                                            <div class="mb-3" style="margin-bottom: 1rem">
                                                <label style="
                                                        display: block;
                                                        font-weight: 600;
                                                        margin-bottom: 0.5rem;
                                                    ">Skills</label>
                                                <div>
                                                    <input type="text" v-model="newSkill" placeholder="Enter skill"
                                                        style="
                                                            padding: 0.5rem;
                                                            border: 1px solid
                                                                #ddd;
                                                            border-radius: 4px;
                                                            margin-bottom: 0.5rem;
                                                            width: 50%;
                                                            margin-right: 0.5rem;
                                                        " />
                                                    <button type="button" @click="addSkill" style="
                                                            background: #007bff;
                                                            color: white;
                                                            padding: 0.5rem
                                                                0.5rem;
                                                            border: none;
                                                            border-radius: 4px;
                                                            cursor: pointer;
                                                        ">
                                                        Add Skill
                                                    </button>
                                                </div>
                                                <ul style="
                                                        margin-top: 1rem;
                                                        list-style: none;
                                                        padding: 0;
                                                    ">
                                                    <li v-for="(
                                                            skill, index
                                                        ) in skills" :key="index" style="
                                                            background: #f0f0f0;
                                                            padding: 0.5rem;
                                                            margin-bottom: 0.5rem;
                                                            border-radius: 4px;
                                                            display: flex;
                                                            justify-content: space-between;
                                                            align-items: center;
                                                        ">
                                                        {{ skill }}
                                                        <button type="button" @click="
                                                            removeSkill(
                                                                index
                                                            )
                                                            " style="
                                                                background: #dc3545;
                                                                color: white;
                                                                border: none;
                                                                border-radius: 50%;
                                                                width: 24px;
                                                                height: 24px;
                                                                font-size: 1rem;
                                                                display: flex;
                                                                align-items: center;
                                                                justify-content: center;
                                                                cursor: pointer;
                                                            ">
                                                            &times;
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- Media Section -->
                                            <div class="mb-3" style="margin-bottom: 1rem">
                                                <label style="
                                                        display: block;
                                                        font-weight: 600;
                                                        margin-bottom: 0.5rem;
                                                    ">Media</label>
                                                <div>
                                                    <button type="button" @click="addMedia" style="
                                                            background: #007bff;
                                                            color: white;
                                                            padding: 0.5rem 1rem;
                                                            border: none;
                                                            border-radius: 4px;
                                                            cursor: pointer;
                                                            margin-right: 0.5rem;
                                                        ">
                                                        Add Media
                                                    </button>
                                                    <input type="file" ref="mediaInput" @change="handleMediaUpload
                                                        " style="display: none"
                                                        accept="image/*,video/*,application/pdf" />
                                                </div>
                                                <ul style="
                                                        margin-top: 1rem;
                                                        list-style: none;
                                                        padding: 0;
                                                    ">
                                                    <li v-for="(
                                                            media, index
                                                        ) in mediaList" :key="index" style="
                                                            background: #f0f0f0;
                                                            padding: 0.5rem;
                                                            margin-bottom: 0.5rem;
                                                            border-radius: 4px;
                                                            display: flex;
                                                            justify-content: space-between;
                                                            align-items: center;
                                                        ">
                                                        {{ media }}
                                                        <button type="button" @click="
                                                            removeMedia(
                                                                index
                                                            )
                                                            " style="
                                                                background: #dc3545;
                                                                color: white;
                                                                border: none;
                                                                border-radius: 50%;
                                                                width: 24px;
                                                                height: 24px;
                                                                font-size: 1rem;
                                                                display: flex;
                                                                align-items: center;
                                                                justify-content: center;
                                                                cursor: pointer;
                                                            ">
                                                            &times;
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- Contributors Section -->
                                            <div class="mb-3" style="margin-bottom: 1rem">
                                                <label style="
                                                        display: block;
                                                        font-weight: 600;
                                                        margin-bottom: 0.5rem;
                                                    ">Contributors</label>
                                                <div>
                                                    <input type="text" v-model="newContributor"
                                                        placeholder="Enter contributor name" style="
                                                            padding: 0.5rem;
                                                            border: 1px solid
                                                                #ddd;
                                                            border-radius: 4px;
                                                            margin-bottom: 0.5rem;
                                                            width: 80%;
                                                            margin-right: 0.5rem;
                                                        " />
                                                    <button type="button" @click="addContributor" style="
                                                            background: #007bff;
                                                            color: white;
                                                            padding: 0.5rem
                                                                0.6rem;
                                                            border: none;
                                                            border-radius: 4px;
                                                            cursor: pointer;
                                                        ">
                                                        Add Contributor
                                                    </button>
                                                </div>
                                                <ul style="
                                                        margin-top: 1rem;
                                                        list-style: none;
                                                        padding: 0;
                                                    ">
                                                    <li v-for="(
                                                            contributor, index
                                                        ) in contributors" :key="index" style="
                                                            background: #f0f0f0;
                                                            padding: 0.5rem;
                                                            margin-bottom: 0.5rem;
                                                            border-radius: 4px;
                                                            display: flex;
                                                            justify-content: space-between;
                                                            align-items: center;
                                                        ">
                                                        {{ contributor }}
                                                        <button type="button" @click="
                                                            removeContributor(
                                                                index
                                                            )
                                                            " style="
                                                                background: #dc3545;
                                                                color: white;
                                                                border: none;
                                                                border-radius: 50%;
                                                                width: 24px;
                                                                height: 24px;
                                                                font-size: 1rem;
                                                                display: flex;
                                                                align-items: center;
                                                                justify-content: center;
                                                                cursor: pointer;
                                                            ">
                                                            &times;
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- Save & Cancel -->
                                            <div class="text-end" style="margin-top: 1rem">
                                                <button type="button" @click="hideAddProjectModal" style="
                                                        background: #6c757d;
                                                        color: white;
                                                        padding: 0.5rem 1rem;
                                                        border: none;
                                                        border-radius: 4px;
                                                        cursor: pointer;
                                                        margin-right: 0.5rem;
                                                    ">
                                                    Cancel
                                                </button>
                                                <button type="submit" @click.prevent="saveProject" style="
                                                        background: #007bff;
                                                        color: white;
                                                        padding: 0.5rem 1rem;
                                                        border: none;
                                                        border-radius: 4px;
                                                        cursor: pointer;
                                                    ">
                                                    Save
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Edit Project Modal -->
                        <div v-if="editProjectModal" class="modal fade show d-block" style="
                                background: rgba(0, 0, 0, 0.5);
                                position: fixed;
                                top: 0;
                                left: 0;
                                width: 100%;
                                height: 100%;
                                z-index: 1050;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            ">
                            <div class="modal-dialog modal-lg" style="
                                    max-width: 60%;
                                    max-height: 90%;
                                    overflow-y: auto;
                                    border-radius: 8px;
                                ">
                                <div class="modal-content" style="border-radius: 8px; overflow: hidden">
                                    <div class="modal-header" style="
                                            display: flex;
                                            justify-content: space-between;
                                            align-items: center;
                                            padding: 1rem;
                                            border-bottom: 1px solid #ddd;
                                        ">
                                        <h5 style="
                                                margin: 0;
                                                font-size: 1.25rem;
                                            ">
                                            Edit Project
                                        </h5>
                                        <button type="button" class="btn-close" @click="hideEditProjectModal" style="
                                                background: none;
                                                border: none;
                                                font-size: 1.25rem;
                                                cursor: pointer;
                                            ">
                                            &times;
                                        </button>
                                    </div>
                                    <div class="modal-body" style="
                                            padding: 1rem;
                                            overflow-y: auto;
                                            max-height: calc(100vh - 160px);
                                        ">
                                        <form @submit.prevent="updateProject">
                                            <!-- Project Name -->
                                            <div class="mb-3" style="margin-bottom: 1rem">
                                                <label style="
                                                        display: block;
                                                        font-weight: 600;
                                                        margin-bottom: 0.5rem;
                                                    ">Project Name
                                                    <span style="color: red">*</span></label>
                                                <input type="text" v-model="editProjectData.project_name
                                                    " style="
                                                        width: 80%;
                                                        padding: 1rem;
                                                        border: 1px solid #ddd;
                                                        border-radius: 4px;
                                                    " required />
                                            </div>

                                            <!-- Description -->
                                            <div class="mb-3" style="margin-bottom: 1rem">
                                                <label style="
                                                        display: block;
                                                        font-weight: 600;
                                                        margin-bottom: 0.5rem;
                                                    ">Description</label>
                                                <textarea v-model="editProjectData.project_description
                                                    " style="
                                                        width: 80%;
                                                        padding: 0.5rem;
                                                        border: 1px solid #ddd;
                                                        border-radius: 4px;
                                                        height: 100px;
                                                    " maxlength="2000"></textarea>
                                                <small style="
                                                        display: block;
                                                        color: #666;
                                                    ">{{
                                                        editProjectData
                                                            .project_description
                                                            .length
                                                    }}/2000</small>
                                            </div>

                                            <!-- Skills -->
                                            <div class="mb-3" style="margin-bottom: 1rem">
                                                <label style="
                                                        display: block;
                                                        font-weight: 600;
                                                        margin-bottom: 0.5rem;
                                                    ">Skills</label>
                                                <div>
                                                    <input type="text" v-model="newEditSkill" placeholder="Enter skill"
                                                        style="
                                                            padding: 0.5rem;
                                                            border: 1px solid
                                                                #ddd;
                                                            border-radius: 4px;
                                                            margin-bottom: 0.5rem;
                                                            width: 50%;
                                                            margin-right: 0.5rem;
                                                        " />
                                                    <button type="button" @click="addEditSkill" style="
                                                            background: #007bff;
                                                            color: white;
                                                            padding: 0.5rem
                                                                0.5rem;
                                                            border: none;
                                                            border-radius: 4px;
                                                            cursor: pointer;
                                                        ">
                                                        Add Skill
                                                    </button>
                                                </div>
                                                <ul style="
                                                        margin-top: 1rem;
                                                        list-style: none;
                                                        padding: 0;
                                                    ">
                                                    <li v-for="(
                                                            skill, index
                                                        ) in editProjectData.skills" :key="index" style="
                                                            background: #f0f0f0;
                                                            padding: 0.5rem;
                                                            margin-bottom: 0.5rem;
                                                            border-radius: 4px;
                                                            display: flex;
                                                            justify-content: space-between;
                                                            align-items: center;
                                                        ">
                                                        {{ skill }}
                                                        <button type="button" @click="
                                                            removeEditSkill(
                                                                index
                                                            )
                                                            " style="
                                                                background: #dc3545;
                                                                color: white;
                                                                border: none;
                                                                border-radius: 50%;
                                                                width: 24px;
                                                                height: 24px;
                                                                font-size: 1rem;
                                                                display: flex;
                                                                align-items: center;
                                                                justify-content: center;
                                                                cursor: pointer;
                                                            ">
                                                            &times;
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- Save Changes -->
                                            <div class="text-end" style="margin-top: 1rem">
                                                <button type="button" @click="hideEditProjectModal
                                                    " style="
                                                        background: #6c757d;
                                                        color: white;
                                                        padding: 0.5rem 1rem;
                                                        border: none;
                                                        border-radius: 4px;
                                                        cursor: pointer;
                                                        margin-right: 0.5rem;
                                                    ">
                                                    Cancel
                                                </button>
                                                <button type="submit" style="
                                                        background: #007bff;
                                                        color: white;
                                                        padding: 0.5rem 1rem;
                                                        border: none;
                                                        border-radius: 4px;
                                                        cursor: pointer;
                                                    ">
                                                    Save Changes
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documents Table -->
                        <div class="col-12" v-if="document_data && document_data.length > 0">
                            <div class="mb-3">
                                <div class="card shadow-sm rounded-3">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 text-dark">
                                            Employee Documents
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
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
                                                    <tr v-for="document in document_data" :key="document.id">
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
                                                            <a :href="document
                                                                .media?.[0]
                                                                ?.original_url
                                                                " class="btn btn-secondary btn-sm" target="_blank">
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
                        <div class="col-12" v-if="leave_data && leave_data.length > 0">
                            <div class="mb-3">
                                <div class="card shadow-sm rounded-3">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 text-dark">
                                            Leave Applications
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle custom-table">
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
                                                    <tr v-for="leave in leave_data" :key="leave.id">
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
                                                            <span :class="{
                                                                'badge bg-warning text-dark':
                                                                    leave.status ===
                                                                    'Pending',
                                                                'badge bg-success text-white':
                                                                    leave.status ===
                                                                    'Approved',
                                                                'badge bg-danger text-white':
                                                                    leave.status ===
                                                                    'Rejected',
                                                            }">
                                                                {{
                                                                    leave.status
                                                                }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div v-if="
                                                                leave.instructions
                                                            " class="bg-light p-2 rounded">
                                                                {{
                                                                    leave.instructions
                                                                }}
                                                            </div>
                                                            <div v-else class="text-muted fst-italic">
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

                        <!-- Old Leaves Table -->
                        <div
                            class="col-12"
                            v-if="leave_data_old && leave_data_old.length > 0"
                        >
                            <div class="mb-3">
                                <div class="card shadow-sm rounded-3">
                                    <div
                                        class="card-header bg-light d-flex justify-content-between align-items-center"
                                    >
                                        <h5 class="mb-0 text-dark">
                                            Before 2025 Leave Applications
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
                                                            Reason
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        v-for="leave in leave_data_old"
                                                        :key="leave.id"
                                                    >
                                                        <td class="text-center">
                                                            {{
                                                                leave.from_date
                                                            }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ leave.to }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{
                                                                leave.leave_type
                                                            }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ leave.type }}
                                                        </td>
                                                        <td class="text-center">
                                                            <span
                                                                :class="{
                                                                    'badge bg-warning text-dark':
                                                                        leave.leave_status ===
                                                                        'Pending',
                                                                    'badge bg-success text-white':
                                                                        leave.leave_status ===
                                                                        'Approved',
                                                                    'badge bg-danger text-white':
                                                                        leave.leave_status ===
                                                                        'Rejected',
                                                                }"
                                                            >
                                                                {{
                                                                    leave.leave_status
                                                                }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div
                                                                v-if="
                                                                    leave.reason
                                                                "
                                                                class="bg-light p-2 rounded"
                                                            >
                                                                {{
                                                                    leave.reason
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
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";
import { Link } from "@inertiajs/inertia-vue3";

export default {
    components: {
        Link,
        AppLayout,
    },
    props: {
        employee_id: Number, // Pass employee ID as a prop
        errors: Object,
        employee_data: Object,
        leave_data: Array,
        document_data: Array,
        projects: {
            type: Array,
            required: true,
        },
        leave_data_old:Array,
        service_letter_data: Object,
    },
    data() {
        return {
            employee_id: this.employee_data?.id || null,
            localLeaveData: this.leave_data ? [...this.leave_data] : [],
            menuOpen: false, // Track hamburger menu state
            coverPhoto: "", // Custom cover photo
            defaultCoverPhoto: "/images/background6.png", // Default cover photo
            addProjectModal: false,
            projectName: "",
            projectDescription: "",
            startDate: "",
            endDate: "",
            currentlyWorking: false,
            newSkill: "",
            skills: [],
            newContributor: "",
            contributors: [],
            mediaList: [],
            projects: [], // Store fetched project data here
            editProjectModal: false, // To control the edit modal
            isLoading: false, // Flag for loading state

            editProjectData: {
                id: null,
                project_name: "",
                project_description: "",
                skills: [],
            },
            newEditSkill: "", // New skill for editing
        };
    },

    computed: {
        filteredProjects() {
            // Filter projects by status === 1
            return this.projects.filter((project) => project.status === 1);
        },
        employee_id() {
            return this.employee_data?.id || null;
        },
        formattedDate() {
            const date = new Date();
            const options = { year: "numeric", month: "long", day: "numeric" };
            return date.toLocaleDateString("en-US", options);
        },
        serviceLetterData() {
            return this.service_letter_data;
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
        if (this.$page.props.employee) {

            const coverPhotoItem = this.$page.props.employee.media.find(
                mediaItem => mediaItem.collection_name == 'cover_photos'
            );
            this.coverPhoto = coverPhotoItem ? coverPhotoItem.original_url : null;
        }


        this.fetchProjects(); // Fetch projects
    },
    methods: {
        editProject(project) {
            this.editProjectData = {
                id: project.id,
                project_name: project.project_name,
                project_description: project.project_description,
                skills: JSON.parse(project.skills),
            };
            this.editProjectModal = true; // Open the edit modal
        },
        hideEditProjectModal() {
            this.editProjectModal = false; // Close the edit modal
        },
        async updateProject() {
            try {
                const response = await axios.put(
                    `/projects/${this.editProjectData.id}`,
                    {
                        project_name: this.editProjectData.project_name,
                        project_description:
                            this.editProjectData.project_description,
                        skills: JSON.stringify(this.editProjectData.skills),
                    }
                );
                Swal.fire({
                    title: "Project Updated!",
                    text: "The project has been successfully updated.",
                    icon: "success",
                });
                this.editProjectModal = false; // Close the modal
                this.fetchProjects(); // Refresh the projects list
            } catch (error) {
                Swal.fire({
                    title: "Error Updating Project",
                    text: "An error occurred while updating the project.",
                    icon: "error",
                });
            }
        },
        addEditSkill() {
            if (this.newEditSkill.trim()) {
                this.editProjectData.skills.push(this.newEditSkill.trim());
                this.newEditSkill = "";
            }
        },
        removeEditSkill(index) {
            this.editProjectData.skills.splice(index, 1);
        },
        deleteProject(projectId) {
            // Confirm before deleting
            Swal.fire({
                title: "Are you sure?",
                text: "This action cannot be undone!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make an API call to delete the project
                    axios
                        .delete(`/projects/${projectId}`)
                        .then((response) => {
                            // console.log("Project deleted:", response.data);
                            // Remove the project from the list
                            this.projects = this.projects.filter(
                                (project) => project.id !== projectId
                            );
                            Swal.fire(
                                "Deleted!",
                                "The project has been deleted.",
                                "success"
                            );
                        })
                        .catch((error) => {
                            // console.error("Error deleting project:", error);
                            Swal.fire(
                                "Error!",
                                "Failed to delete the project.",
                                "error"
                            );
                        });
                }
            });
        },
        async fetchProjects() {
            try {
                const response = await axios.get(
                    `/projects/${this.employee_id}`
                );
                this.projects = response.data; // Assign fetched data
                // console.log(response.data);
            } catch (error) {
                // console.error(
                //     "Error fetching projects:",
                //     error.response?.data || error.message
                // );
            }
        },
        addSkill() {
            if (this.newSkill.trim()) {
                this.skills.push(this.newSkill.trim());
                this.newSkill = "";
            }
        },
        removeSkill(index) {
            this.skills.splice(index, 1);
        },
        addContributor() {
            if (this.newContributor.trim()) {
                this.contributors.push(this.newContributor.trim());
                this.newContributor = "";
            }
        },
        removeContributor(index) {
            this.contributors.splice(index, 1);
        },
        addMedia() {
            this.$refs.mediaInput.click();
        },
        handleMediaUpload(event) {
            const files = event.target.files;
            for (const file of files) {
                this.mediaList.push(file);
            }
            event.target.value = ""; // Clear input
        },
        removeMedia(index) {
            this.mediaList.splice(index, 1);
        },
        hideAddProjectModal() {
            this.addProjectModal = false;
        },
        addProject() {
            console.log({
                projectName: this.projectName,
                projectDescription: this.projectDescription,
                skills: this.skills,
                contributors: this.contributors,
                mediaList: this.mediaList,
            });
            this.hideAddProjectModal();
        },
        saveProject() {
            // Create a FormData object for file uploads
            const formData = new FormData();
            formData.append("employee_id", this.employee_data.id);
            formData.append("project_name", this.projectName);
            formData.append(
                "project_description",
                this.projectDescription || ""
            ); // Ensure no null values
            this.skills.forEach((skill) => formData.append("skills[]", skill));
            this.contributors.forEach((contributor) =>
                formData.append("contributors[]", contributor)
            );
            this.mediaList.forEach((file) => formData.append("media[]", file));

            this.isLoading = true; // Start loading indicator

            Inertia.post(
                route("user.data"),
                formData,

                {
                    onSuccess: () => {
                        this.isLoading = false;
                        Swal.fire({
                            title: "Project Added Successfully!",
                            text: "Your project has been submitted and is awaiting approval.",
                            icon: "success",
                            confirmButtonText: "OK",
                            confirmButtonColor: "#0d6efd",
                        });
                    },
                    onError: () => {
                        this.isLoading = false;
                        Swal.fire({
                            title: "Error Adding Project",
                            text:
                                error.response?.data?.message ||
                                "An error occurred while saving the project. Please try again.",
                            icon: "error",
                            confirmButtonText: "OK",
                            confirmButtonColor: "#dc3545",
                        });
                    },
                }
            );
        },

        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        showAddProjectModal() {
            this.addProjectModal = true;
        },
        hideAddProjectModal() {
            this.addProjectModal = false;
        },
        addProject() {
            console.log("Adding project", {
                projectName: this.projectName,
                projectDescription: this.projectDescription,
                startDate: this.startDate,
                endDate: this.endDate,
                currentlyWorking: this.currentlyWorking,
            });
            this.hideAddProjectModal();
        },
        uploadCoverPhoto(event) {
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

                    this.$inertia
                        .post(route("upload.cover"), formData, {
                            onSuccess: () => {
                                if (this.$page.props.employee) {

                                    const coverPhotoItem = this.$page.props.employee.media.find(
                                        mediaItem => mediaItem.collection_name == 'cover_photos'
                                    );
                                    this.coverPhoto = coverPhotoItem ? coverPhotoItem.original_url : null;
                                }
                            },
                            onError: () => { }
                        })

                };
            };
            reader.readAsDataURL(file);
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
                    this.$inertia.visit(route('employee.leaves.indexform'));
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

    .modal-content {
        border-radius: 8px;
        overflow: hidden;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 4px;
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
    width: 200px;
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


.badge {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 12px;
    display: inline-block;
    min-width: 80px;
    text-align: center;
}


.bg-light {
    background-color: #f8f9fa !important;
}

.text-muted {
    color: #6c757d !important;
}

.text-secondary {
    color: #495057 !important;
}

.custom-thead th {
    background-color: #e9ecef;
    color: #343a40;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
}


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
