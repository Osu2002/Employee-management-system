
<template>
    <div class="modal-overlay" v-if="visible">
      <div class="modal">
        <div class="modal-header">
          <h3>{{ title }}</h3>
          <button class="close-button" @click="closeModal">×</button>
        </div>
        <div class="modal-body">
          <div class="chat-history">
            <!-- Replies -->
            <div v-for="reply in formattedChat" :key="reply.timestamp" class="d-flex align-items-start mb-3"
                :class="reply.from === 'admin' ? 'admin-message' : 'employee-message'">
              <div
                :class="reply.from === 'admin' ? 'bubble-wrapper admin-bubble' : 'bubble-wrapper employee-bubble'">
                <div class="tag">{{ reply.from === "admin" ? "HR" : "You" }}</div>
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
              <button type="submit" class="btn btn-success btn-rounded gradient-btn">Send Reply</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button @click="closeModal" class="btn btn-secondary">Close</button>
        </div>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      visible: {
        type: Boolean,
        required: true,
      },
      title: {
        type: String,
        default: "Chat with HR",
      },
      selectedMessage: {
        type: Object,
        required: true,
      },
      formattedChat: {
        type: Array,
        default: () => [],
      },
    },
    data() {
      return {
        replyMessage: "",
      };
    },
    methods: {
      closeModal() {
        this.$emit("close");
      },
      sendReply() {
        // Emit the reply message to parent
        this.$emit("send-reply", this.replyMessage);
        this.replyMessage = ""; // Clear the input
      },
      formatDate(date) {
        const options = { year: "numeric", month: "short", day: "numeric" };
        return new Date(date).toLocaleDateString(undefined, options);
      },
    },
  };
  </script>
  
  <style>
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .modal {
    background: white;
    padding: 1rem;
    border-radius: 5px;
    max-width: 500px;
    width: 100%;
  }

  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .modal-body {
    margin: 1rem 0;
  }

  .modal-footer {
    display: flex;
    justify-content: flex-end;
  }

  .close-button {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
  }
  </style>
