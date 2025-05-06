<template>
    <section class="footer pt-5">
        <div class="news-letter col-12 p-5" style="height: 350px;">
            <div class="video-background">
                <video autoplay muted loop>
                    <source
                        src="https://weblook.com/wp-content/uploads/2024/08/5210179_Empty_No-People_1280x720.mov"
                        type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="row justify-content-around" style="padding: 40px;">
                <div class="col-lg-5 col-md-12">
                    <h2 class="news-main-text p-0 m-0">BE FINDABLE</h2>
                    <div class="animated-line"></div>

                    <h2 class="news-sec-text pt-2">Get Found. Get Customers. Get Growing.</h2>
                </div>
                <div class="col-lg-5 col-md-12">
                    <h2 class="news-label">Sign up for our newsletter</h2>
                    <div class="row">
                        <div class="col-lg-8">
                            <input class="form-control p-2 news-input" type="text" placeholder="Email">
                        </div>
                        <div class="col-lg-4">
                            <button class="btn btn-lg btn-danger btn-block">Send</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="bootom-footer col-lg-12 px-5 pt-1">
            <div class="row">
                <div class="col-lg-4 col-md-12 text-left">
                    <p class="p-0 m-0">No 207/23, Dharmapala Mawatha, Colombo 07.</p>
                    <p>+94 11 2 202 000 | +94 71 2 555 333</p>
                </div>
                <div class="col-lg-4 col-md-12 d-flex justify-content-center align-items-center">
                    <a href="https://facebook.com/weblook" target="_blank">
                        <i class="fab fa-facebook-f px-2" aria-hidden="true"></i>
                    </a>
                    <a href="https://youtube.com/@weblookinternationalpvtltd6141" target="_blank">
                        <i class="fab fa-youtube px-2" aria-hidden="true"></i>
                    </a>
                    <a href="https://www.linkedin.com/company/weblook-international-pvt-limited/"  target="_blank">
                        <i class="fab fa-linkedin-in px-2" aria-hidden="true"></i>
                    </a>
                    <a href="https://instagram.com/weblookinternational/" target="_blank">
                        <i class="fab fa-instagram px-2" aria-hidden="true"></i>
                    </a>





                </div>
                <div class="col-lg-4 col-md-12 text-right">
                    <p class="p-0 m-0">19 Mclochlan Street, Mt Waverley , Vic 3149.</p>
                    <p>+61 (3) 9999 7450</p>
                </div>
            </div>
        </div>
        <div class="bootom-footer col-lg-12 px-5 pt-1 custom-border-top">
            <div class="row">
                <div class="col-lg-4 text-left">
                    <!-- <p>No 207/23, Dharmapala Mawatha, Colombo 07.</p>
                    <p>+94 11 2 202 000 | +94 71 2 555 333</p> -->
                </div>
                <div class="col-lg-4 d-flex justify-content-center align-items-center">
                    
                </div>
                <div class="col-lg-4 text-right">
                    <p>Copyright © {{ currentYear }} - Weblook</p>
                </div>
            </div>
        </div>


    </section>

</template>

<script>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import SelectInputComponent from "@/Components/SelectInputComponent.vue";
import { Inertia } from '@inertiajs/inertia';
import { gsap } from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';


gsap.registerPlugin(ScrollTrigger);

export default {
    components: {
        Link,
        SelectInputComponent,
    },
    props: {},
    data() {
        return {
            currentYear: new Date().getFullYear()
        };
    },
    created() { },
    mounted() {
        this.LineAnimation();
    },
    computed: {},
    methods: {
        LineAnimation() {
            const line = document.querySelector('.animated-line');


            gsap.set(line, { width: 0 });


            ScrollTrigger.create({
                trigger: line,
                markers: false,
                start: '-=50 center', // adjust as needed
                end: '+=280', // adjust as needed
                scrub: 1, // controls the smoothness of the animation
                onEnter: (self) => {
                    gsap.to(line, { width: `${50}%`, ease: 'none' });
                },
                onEnterBack: (self) => {
                    gsap.to(line, { width: `${50}%`, ease: 'none' });
                },
                onLeave: (self) => {
                    gsap.to(line, { width: `${0}%`, ease: 'none' });
                },
                onLeaveBack: (self) => {
                    gsap.to(line, { width: `${0}%`, ease: 'none' });
                },
            });
        },
        logout() {
            this.$inertia.post(route("logout"));
        },
    },
};
</script>

<style scoped></style>