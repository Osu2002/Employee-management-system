<template>
    <AppLayout>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h5>
                            {{
                                jobRole ? "Update Job Role" : "Create Job Role"
                            }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <!-- Job Role Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label"
                                    >Role Name</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.name"
                                    required
                                />
                            </div>
                            <!-- Dynamic Leave Types -->
                            <div class="mb-3">
                                <label class="form-label"
                                    >Leave Types and Allowed Leaves</label
                                >
                                <div
                                    class="row mb-2"
                                    v-for="(leave, index) in form.leave_types"
                                    :key="index"
                                >
                                    <div class="col-md-5">
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Leave Type"
                                            v-model="leave.type"
                                        />
                                    </div>
                                    <div class="col-md-5">
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Allowed Leaves"
                                            v-model="leave.allowed_leaves"
                                        />
                                    </div>
                                    <div class="col-md-2">
                                        <button
                                            type="button"
                                            class="btn btn-danger"
                                            @click="removeLeave(index)"
                                        >
                                            Remove
                                        </button>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm"
                                    @click="addLeave"
                                >
                                    + Add Leave Type
                                </button>
                                <br /><br />
                                <SelectInputComponent
                                    id="status"
                                    label="Status"
                                    :error="form.errors.status"
                                    :options="[
                                        {
                                            id: '1',
                                            name: 'Active',
                                        },
                                        {
                                            id: '0',
                                            name: 'Inactive',
                                        },
                                    ]"
                                    v-model="form.status"
                                />
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-3">
                                <button type="submit" class="btn btn-success">
                                    Save Job Role
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import SelectInputComponent from "@/Components/SelectInputComponent.vue";

export default {
    components: {
        AppLayout,
        SelectInputComponent,
    },
    props: {
        jobRole: Object,
    },
    data() {
        return {
            form: useForm({
                id: "",
                name: "",
                status: "",
                // leave_types: this.jobRole?.leave_types
                //   ? JSON.parse(this.jobRole.leave_types)
                //   : [{ type: "", allowed_leaves: 0 }],
                leave_types: [{ type: "", allowed_leaves: 0 }],
            }),
        };
    },
    mounted() {
        if (this.jobRole) {
            this.form.id = this.jobRole.id,
                this.form.name = this.jobRole.name,
                this.form.status = this.jobRole.status,
                this.form.leave_types = JSON.parse(this.jobRole.leave_types);
        }
    },
    methods: {
        addLeave() {
            this.form.leave_types.push({ type: "", allowed_leaves: 0 });
        },
        removeLeave(index) {
            this.form.leave_types.splice(index, 1);
        },
        submit() {
            this.form.post(
                this.jobRole
                    ? route("job-roles.update")
                    : route("job-roles.store"),
                {
                    onSuccess: () => {
                        // this.form.reset();
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
                }
            );
        },
    },
};
</script>
