<template>
    <AppLayout>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h5 style="font-size: 1.1rem; color: #495057">Contact HR Management</h5>
                        <p class="mb-0" style="font-size: 0.89rem">Manage Contact HR Messages.</p>
                    </div>
                    <div class="card-body">
                        <data-table
                            ref="datatable"
                            :id="'mytable'"
                            :url="route('contact.hr.getdata')"
                            :columns="columns"
                        >
                            <template #header>
                                <tr>
                                    <th width="10%">
                                        <div class="custom-control custom-checkbox">
                                            <input
                                                type="checkbox"
                                                class="form-check-input"
                                                id="selectAll"
                                                @click="selectAll($event)"
                                            />
                                            <label class="form-check-label" for="selectAll"></label>
                                        </div>
                                    </th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </template>
                        </data-table>
                    </div>
                </div>
            </div>

            <!-- Reply Modal -->
            <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="replyModalLabel">Chat with Employee</h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                                @click="closeModal"
                            ></button>
                        </div>
                        <div class="modal-body">
                            <!-- Chat History -->
                            <div class="chat-history">
                                <div v-for="(chat, index) in chatHistory" :key="index">
                                    <div :class="chat.from === 'admin' ? 'text-start' : 'text-end'">
                                        <span
                                            :class="chat.from === 'admin' ? 'badge bg-primary' : 'badge bg-secondary'"
                                        >
                                            {{ chat.from === 'admin' ? 'You' : 'Employee' }}
                                        </span>
                                        <p
                                            style="background: #f8f9fa; padding: 10px; border-radius: 8px;"
                                        >
                                            {{ chat.message }}
                                        </p>
                                        <small style="font-size: 0.8rem; color: gray;">
                                            {{ new Date(chat.timestamp).toLocaleString() }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Reply Form -->
                            <form @submit.prevent="sendReply">
                                <div class="form-group mt-3">
                                    <textarea
                                        v-model="replyMessage"
                                        class="form-control"
                                        rows="3"
                                        placeholder="Type your reply..."
                                        required
                                    ></textarea>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <button
                                        type="button"
                                        class="btn btn-secondary me-2"
                                        @click="closeModal"
                                    >
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">Send Reply</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import DataTable from "@/Components/DataTable.vue";
import axios from "axios";

export default {
    components: {
        AppLayout,
        DataTable,
    },
    data() {
        return {
            columns: [
                { data: "check", name: "check", orderable: false, searchable: false },
                { data: "name", name: "name", orderable: true, searchable: true },
                { data: "email", name: "email", orderable: false, searchable: false },
                { data: "subject", name: "subject", orderable: false, searchable: false },
                { data: "message", name: "message", orderable: false, searchable: false },
                { data: "date", name: "date", orderable: true, searchable: false },
                {
                    data: "action",
                    name: "action",
                    render(data, type, row) {
                        return `
                            <button class="btn btn-primary btn-sm action_reply" data-id="${row.id}">Reply</button>
                            <button class="btn btn-danger btn-sm action_delete" data-id="${row.id}">Delete</button>
                        `;
                    },
                    orderable: false,
                    searchable: false,
                },
            ],
            replyMessage: "",
            selectedMessageId: null,
            chatHistory: [],
        };
    },
    mounted() {
        const self = this;

        $("#mytable tbody").on("click", ".action_reply", function () {
            const messageId = $(this).data("id");
            self.openModal(messageId);
        });

        $("#mytable tbody").on("click", ".action_delete", function () {
            const messageId = $(this).data("id");
            if (confirm("Are you sure you want to delete this message?")) {
                self.deleteMessage(messageId);
            }
        });
    },
    methods: {
        openModal(messageId) {
            this.selectedMessageId = messageId;
            this.fetchChatHistory();
            $("#replyModal").modal("show");
        },
        closeModal() {
            $("#replyModal").modal("hide");
            this.replyMessage = "";
            this.chatHistory = [];
            this.selectedMessageId = null;
        },
        async fetchChatHistory() {
            try {
                const response = await axios.get(`/contact-hr/chat-history/${this.selectedMessageId}`);
                const { chat_history = [], employee_message, created_at } = response.data;

                this.chatHistory = [

                    ...chat_history.map((item) => ({
                        message: item.message,
                        timestamp: item.timestamp || new Date().toISOString(),
                        from: item.from || "admin",
                    })),
                ];
            } catch (error) {
                console.error("Failed to fetch chat history:", error);
                alert("Failed to load chat history.");
            }
        },
        async sendReply() {
            if (!this.replyMessage.trim()) {
                alert("Reply cannot be empty.");
                return;
            }
            try {
                await axios.post(route('contact.hr.reply', this.selectedMessageId), {
                    reply: this.replyMessage,
                });


                // Add reply to chatHistory
                this.chatHistory.push({
                    message: this.replyMessage,
                    timestamp: new Date().toISOString(),
                    from: "admin",
                });

                this.replyMessage = ""; // Clear the input
                alert("Reply sent successfully.");
            } catch (error) {
                console.error("Failed to send reply:", error);
                alert("Failed to send reply. Please try again.");
            }
        },
        async deleteMessage(messageId) {
            try {
                await axios.delete(`/contact-hr/${messageId}`);
                alert("Message deleted successfully.");
               this.location.reload();
            } catch (error) {
                console.error("Failed to delete message:", error);
                this.location.reload();
                // alert("Failed to delete message. Please try again.");
            }
        },
    },
};
</script>

<style scoped>
.card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
}

.card-header {
    padding: 15px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 8px 8px 0 0;
}

.chat-history {
    max-height: 300px;
    overflow-y: auto;
    padding: 10px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 15px;
    background-color: #f8f9fa;
}

.chat-message {
    margin: 0;
    background: #f8f9fa;
    padding: 5px;
    border-radius: 5px;
}
</style>
