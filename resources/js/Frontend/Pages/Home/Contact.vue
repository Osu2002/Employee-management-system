<template>
  <AppLayout>
    <!-- contact section -->
    <section class="contact_section layout_padding">
      <div class="container">
        <div class="heading_container">
          <h2>Contact Us</h2>
        </div>
        <div class="row">
          <div class="col-md-6">
            <form @submit.prevent="sendEmail">
              <div>
                
                <input type="text" placeholder="Name" v-model="name">
              </div>
              <div>
                <input type="text" placeholder="Phone Number" v-model="phone" />
              </div>
              <div>
                <input type="email" placeholder="Email" v-model="email" />
              </div>
              <div>
                <input type="text" class="message-box" placeholder="Message" v-model="message" />
              </div>
              <div class="d-flex ">
                <button type="submit">SEND</button>
              </div>
            </form>
          </div>
          <div class="col-md-6">
            <div class="map_container">
              <div class="map">
                <div id="googleMap" style="width:100%;height:100%;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- end contact section -->
  </AppLayout>
</template>
<script>

import AppLayout from "@@/Layouts/AppLayout.vue";


export default {
  components: {

    AppLayout
  
  },}
</script>