<template>
    <AppLayout>
        <div class="bg-container py-5">

            <div class="container">
                <!-- Contact HR Form -->
                <div class="card form-card mx-auto mb-4">
                    <div class="card-header bg-gradient text-black">
                        <h3 class="mb-0">Contact HR</h3>
                        <p class="mb-0">Feel free to send us your inquiries.</p>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submitContactForm">
                            <div class="row mb-3">

                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="name" class="form-label">Your Name</label>
                                    <input type="text" id="name" v-model="form.name" class="form-control input-rounded"
                                        placeholder="Enter your name" readonly />
                                </div>


                                <div class="col-md-6">
                                    <label for="email" class="form-label">Your Weblook Email</label>
                                    <input type="email" id="email" v-model="form.email"
                                        class="form-control input-rounded" placeholder="Enter your email" readonly />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" id="subject" v-model="form.subject"
                                        class="form-control input-rounded" placeholder="Enter subject" required />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea id="message" v-model="form.message" class="form-control input-rounded"
                                        rows="4" placeholder="Enter your message" required></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary btn-rounded gradient-btn"
                                        :disabled="loading || !form.subject || !form.message">
                                        <span v-if="loading">
                                            <i class="spinner-border spinner-border-sm me-2"></i> Submitting...
                                        </span>
                                        <span v-else>Submit</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Submitted Messages Table -->
                <div class="card table-card mx-auto">
                    <div class="card-header bg-gradient-secondary text-black">
                        <h3 class="mb-0">Submitted Messages</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="message in allMessages" :key="message.id">
                                        <td>{{ message.name }}</td>
                                        <td>{{ message.email }}</td>
                                        <td>{{ message.subject }}</td>
                                        <td>{{ message.message }}</td>
                                        <td>{{ formatDate(message.created_at) }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" @click="openChat(message.id)"
                                                data-bs-toggle="modal" data-bs-target="#chatModal">Chat</button>
                                        </td>
                                    </tr>
                                    <tr v-if="allMessages.length === 0">
                                        <td colspan="6" class="text-center text-muted">No messages found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>




            <div v-if="selectedMessageId" class="chat-section-wrapper">
                <div class="card chat-card mx-auto mt-5">
                    <div class="card-header bg-gradient-info text-black d-flex justify-content-between">
                        <h3 class="mb-0">Chat with HR</h3>
                        <button @click="closeChat" class="btn btn-danger btn-sm">X</button>
                    </div>
                    <div class="card-body position-relative">
                        <!-- Loading Screen -->
                        <div v-if="loading" class="loading-overlay">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden"></span>
                            </div>
                        </div>

                        <!-- Chat History -->
                        <div class="chat-history" ref="chatContainer">
                            <div v-for="reply in formattedChat" :key="reply.timestamp"
                                class="d-flex align-items-start mb-3"
                                :class="reply.from === 'admin' ? 'admin-message' : 'employee-message'">
                                <div
                                    :class="reply.from === 'admin' ? 'bubble-wrapper admin-bubble' : 'bubble-wrapper employee-bubble'">
                                    <div class="tag">{{ reply.from === "admin" ? "HR" : "" }}</div>
                                    <div class="bubble">
                                        <p class="chat-message">{{ reply.message }}</p>
                                        <small class="chat-timestamp">{{ formatDate(reply.timestamp) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reply Form -->
                        <form @submit.prevent="sendReply">
                            <div class="mb-3">
                                <textarea v-model="replyMessage" class="form-control input-rounded" rows="3"
                                    placeholder="Type your reply..." required></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success btn-rounded gradient-btn"
                                    :disabled="loading">
                                    Send Reply
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
import AppLayout from "@@/Layouts/AppLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";
import axios from "axios";
import Swal from "sweetalert2";

export default {
    components: { AppLayout },
    props: {
      
    },
    data() {
        return {
            allMessages : "",
            showModal: true,
            form: useForm({
                name: this.$page.props.employee.name,
                email: this.$page.props.employee.email,
                subject: "",
                message: ""
            }),
            messages: [],
            selectedMessageId: null,
            selectedChat: "",
            formattedChat: [],
            replyMessage: "",
            loading: false,
        };
    },
    mounted() {
        this.fetchAllMessages();
        this.reloadChat();
    },


    methods: {
        async fetchAllMessages() {
            try {
                const response = await axios.get(
                    route('contact.hr')
                );
                console.log(response);
                this.allMessages = response.data;
            } catch (error) {
                console.error("Error fetching messages:", error);
            }
        },
        reloadChat() {

            this.interval = setInterval(() => {
                this.fetchAllMessages();
                if (this.selectedMessageId) {

                    this.fetchSelectedMessages();
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                }

            }, 5000); // 5000 milliseconds = 5 seconds


        },
        scrollToBottom() {
            const chatContainer = this.$refs.chatContainer;
            console.log(chatContainer);
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        },

        closeChat() {
            this.selectedMessageId = null;
        },


        async submitContactForm() {
            this.loading = true;
            this.form.post(route('contact-hr.store'), {
                onSuccess: () => {
                    this.loading = false;
                    this.form.reset();
                    Swal.fire({
                        icon: "success",
                        title: "Message Sent",
                        text: 'Your Message request has been submitted successfully!',
                        confirmButtonColor: "#3085d6"
                    })
                },
                onError: () => {
                    this.loading = false;
                    Swal.fire({
                        icon: "error",
                        title: "Failed",
                        text: "Failed to send your message. Please try again."
                    });
                }
            });

        },

        resetForm() {
            this.form = {
                name: "",
                email: "",
                subject: "",
                message: ""
            };
        },
        openChat(message) {
            console.log("Opening chat with message:", message);
            this.selectedMessageId = message;
            this.fetchSelectedMessages();
            this.$nextTick(() => {
                // Scroll to bottom when the modal opens
                this.scrollToBottom();
            });

            // console.log(bootstrap.Modal);
            try {
                const modal = new bootstrap.Modal(document.getElementById('chatModal'), {
                    keyboard: true
                });
                modal.show();
            } catch (error) {
                console.error("Error showing modal:", error);
            }
        },

        fetchSelectedMessages() {
            // console.error("Error showing modal:");
            const selectedMessage = this.allMessages.find(message => message.id == this.selectedMessageId);
            console.log(selectedMessage);
            this.selectedChat = selectedMessage;
            this.prepareChat();

        },
        prepareChat() {
            // console.log(message);
            const { replies = [], emp_replies = [] } = this.selectedChat;
            let validReplies = "";
            if (replies) {
                validReplies = replies.map(r => ({
                    ...r,
                    timestamp: r.timestamp || new Date().toISOString()
                }));
            }

            let validEmpReplies = emp_replies.map(r => ({
                ...r,
                timestamp: r.timestamp || new Date().toISOString()
            }));

            this.formattedChat = [...validReplies, ...validEmpReplies].sort(
                (a, b) => new Date(a.timestamp) - new Date(b.timestamp)
            );
        },
        async sendReply() {
            if (!this.replyMessage.trim()) {
                Swal.fire({
                    icon: "error",
                    title: "Empty Reply",
                    text: "Reply cannot be empty."
                });
                return;
            }


            this.loading = true;

            try {
                const response = await axios.post(
                    route('emplyee.reply', { id: this.selectedMessageId }), // Pass 'id' in the route function
                    {
                        reply: this.replyMessage, // Pass 'reply' in the request body
                    }
                );
                // console.log(response);
                this.replyMessage = "";
                Swal.fire({
                    icon: "success",
                    title: "Reply Sent",
                    text: "Your reply has been sent successfully."
                });
                this.loading = false;

                const newMessage = response.data.find(message => message.id == this.selectedMessageId);
                // console.log(this.selectedChat);
                Object.assign(this.selectedChat, newMessage);
                // console.log(this.selectedChat);
                this.prepareChat();
                this.$nextTick(() => {
                    this.scrollToBottom();
                });

            } catch (error) {
                console.error("Error sending message:", error);

            }






        },
        formatDate(date) {
            return new Date(date).toLocaleString();
        }
    },

};
</script>

<style scoped>
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10;
}

.bg-container {

    background-size: cover;
    background-position: center;
    padding: 50px 0;
    min-height: 100vh;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;

}

.blur-background {
    filter: blur(5px);
    pointer-events: none;
}


.bg-container::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: inherit;
    background-size: cover;
    background-position: center;
    filter: blur(8px);
    z-index: 0;
    pointer-events: none;

}

.container {
    max-width: 900px;
    margin: 0 auto;
}

.chat-section-wrapper {
    position: fixed;
    top: 50%;
    left: 79%;
    transform: translate(-50%, -50%);
    z-index: 1050;
    width: 80%;
    max-width: 600px;

}

.form-card,
.table-card,
.chat-card {
    border-radius: 12px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.card-header {
    padding: 15px;
    text-align: center;
}

.bg-gradient {
    background: linear-gradient(to right, #bccae4, #bccae4);
}

.bg-gradient-secondary {
    background: linear-gradient(to right, #bccae4, #bccae4);
}

.bg-gradient-info {
    background: linear-gradient(to right, #bccae4, #bccae4);
}

.input-rounded {
    border-radius: 20px;
    padding: 10px;
}

.btn-rounded {
    border-radius: 10px;
}

.chat-history {
    max-height: 300px;
    overflow-y: auto;
    padding: 15px;
    background-color: #f8f9fc;
    border-radius: 12px;
    margin-bottom: 20px;
}

.chat-message {
    background-color: #e2e6ea;
    padding: 5px;
    border-radius: 8px;
    margin-bottom: 1px;
}

.chat-timestamp {
    font-size: 12px;
    color: #6c757d;
}

.gradient-btn {
    background: linear-gradient(to right, #bccae4, #bccae4);
    color: black;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 10px;
    transition: transform 0.3s, box-shadow 0.3s;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.gradient-btn:hover {
    transform: scale(1.05);
    box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.2);
}

.gradient-btn:focus {
    outline: none;
    box-shadow: 0px 0px 5px #ff5202;
}

.tag {
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 5px;
    text-transform: uppercase;
    display: inline-block;
    padding: 2px 8px;
    border-radius: 10px;
    color: black;
}


.admin-bubble .tag {
    background-color: #d47142;
    color: white;

}

.bubble-wrapper {
    max-width: 50%;
    padding: 5px 10px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}



.employee-bubble .bubble {

    color: rgb(0, 0, 0);
    align-self: flex-end;
    text-align: center;

}

.admin-bubble .bubble {

    color: rgb(0, 0, 0);
    align-self: flex-start;
    text-align: center;
}

.chat-timestamp {
    font-size: 0.75rem;
    color: #000000;
    margin-top: 1px;
}

.employee-message {
    justify-content: flex-end;
}

.admin-message {
    justify-content: flex-start;
}

@media (max-width: 768px) {
    .chat-card {
        width: 70%;
        height: 65vh;
        margin: 0 !important;
        border-radius: 0;
    }

    .chat-card .card-header {
        border-radius: 0;
    }
}
</style>
