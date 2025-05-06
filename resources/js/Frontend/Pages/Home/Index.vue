<template>
    <AppLayout>
        <div
            class="container-fluid wrapper d-flex align-items-center justify-content-center"
        >
            <video autoplay muted loop id="bg-video">
                <source
                    src="images/Weblook Company Profile.mp4"
                    type="video/mp4"
                />
                Your browser does not support HTML5 video.
            </video>
            <div class="background-overlay"></div>
            <div class="background-image"></div>
            <div class="row w-100 content">
                <div
                    class="col-lg-7 col-md-12 text-container d-flex flex-column justify-content-center align-items-start p-5"
                >
                    <h2 class="text-white">
                        Welcome to
                        <span class="weblook-name"
                            >Weblook International Private Limited</span
                        >
                    </h2>
                    <p class="text-white">
                        Please enter your Employee ID to proceed.
                    </p>
                </div>
                <div
                    class="col-lg-5 col-md-12 d-flex justify-content-center align-items-center p-5"
                >
                    <!-- <div class="form-container">
                        <form
                            class="form-signin"
                            id="formAccountSettings"
                            @submit.prevent="submit"
                        >
                            <h4 class="form-signin-heading">
                                Enter Employee ID
                            </h4>
                            <input
                                type="text"
                                class="form-control mb-3"
                                name="index"
                                v-model="form.index"
                                placeholder="EMP5XXX"
                                required
                            />
                            <button
                                class="btn btn-lg btn-danger btn-block"
                                type="submit"
                                :disabled="form.processing"
                            >
                                Submit
                            </button>
                        </form>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="col-12 pt-3">
            <div class="row">
                <div class="col-lg-3 col-md-12 service-card p-4">
                    Web Design Services
                </div>
                <div class="col-lg-3 col-md-12 service-card p-4 border-left">
                    Email Services
                </div>
                <div class="col-lg-3 col-md-12 service-card p-4 border-left">
                    Mobile App Development
                </div>
                <div class="col-lg-3 col-md-12 service-card p-4 border-left">
                    Software Development
                </div>
            </div>
        </div>
        <div class="intro-back">
            <div class="col-12 pt-5 d-flex justify-content-center">
                <div class="col-lg-10 col-md-12">
                    <h3 class="intro-text">
                        <span class="year-test">Since 1997</span> We've Been
                        Transforming Ideas Into Digital Masterpieces, Where
                        Creativity Has No Limits
                    </h3>
                </div>
            </div>
            <div class="col-12 pt-3">
                <div class="row">
                    <div class="col-lg-3 col-md-12 service-card p-4">
                        Digital Solutions:
                        <br />Web Design to E-Commerce
                    </div>
                    <div
                        class="col-lg-3 col-md-12 service-card p-4 border-left"
                    >
                        Digital Services:
                        <br />Web Hosting to Email Providers
                    </div>
                    <div
                        class="col-lg-3 col-md-12 service-card p-4 border-left"
                    >
                        Digital Marketing:
                        <br />SEO to Social Media
                    </div>
                    <div
                        class="col-lg-3 col-md-12 service-card p-4 border-left"
                    >
                        Digital Products:
                        <br />POS to Reservation Systems
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 p-5">
            <h4 class="section-main-text pb-3">Digital Services</h4>
            <p>
                “Connect with confidence: Stable, secure solutions for your
                online presence.”
            </p>
            <p>
                Freedom to choose, power to grow. Control your digital space
                with our customizable options. Grow seamlessly, and thrive
                confidently. We empower your digital success with cutting-edge
                digital services.
            </p>
        </div>
        <div class="col-lg-12 patner px-5" style="overflow: hidden">
            <div class="row d-flex justify-content-center">
                <div class="drop"></div>
                <div class="drop2"></div>

                <a
                    href="https://weblook.com/email-service-providers/"
                    class="digital-card col-lg-4 border pt-3 pb-3"
                >
                    <div class="row p-4">
                        <div class="col-lg-12 pb-4">
                            <div class="row">
                                <div class="col-lg-3">
                                    <img
                                        class=""
                                        src="/images/download.png"
                                        alt
                                        width="60"
                                        height="37"
                                        style="object-fit: contain"
                                    />
                                </div>
                                <div class="col-lg-9">
                                    <h4 class="mt-2 mb-0">smardove</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 pb-2">
                            <h3 class="service-heading">
                                Email Service Providers
                            </h3>
                        </div>
                        <div class="col-lg-12">
                            <p class="service-txt">
                                Minimize cost and complexity with powerful
                                customized email solutions tailored to your
                                specific needs and requirements, and elevate
                                your communication efficiency and effectiveness.
                            </p>
                        </div>
                    </div>
                </a>

                <a
                    href="https://weblook.com/pbx-systems/"
                    class="digital-card col-lg-4 border pt-3 pb-3"
                >
                    <div class="row p-4">
                        <div class="col-lg-12 pb-4">
                            <div class="row">
                                <div class="col-lg-3">
                                    <img
                                        class=""
                                        src="/images/download (1).png"
                                        alt
                                        width="60"
                                        height="37"
                                        style="object-fit: contain"
                                    />
                                </div>
                                <div class="col-lg-9">
                                    <h4 class="mt-2 mb-0">voxinnova</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 pb-2">
                            <h3 class="service-heading">PBX Systems</h3>
                        </div>
                        <div class="col-lg-12">
                            <p class="service-txt">
                                PBX systems are commonly used in businesses to
                                connect individual phone lines to the public
                                phone network, allowing employees to make and
                                receive calls both within the organization and
                                externally.
                            </p>
                        </div>
                    </div>
                </a>

                <a
                    href="https://weblook.com/web-hosting/"
                    class="digital-card col-lg-4 border pt-3 pb-3"
                >
                    <div class="row p-4">
                        <div class="col-lg-12 pb-4">
                            <div class="row">
                                <div class="col-lg-3">
                                    <img
                                        class=""
                                        src="/images/download (2).png"
                                        alt
                                        width="60"
                                        height="37"
                                        style="object-fit: contain"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 pb-2">
                            <h3 class="service-heading">Web Hosting</h3>
                        </div>
                        <div class="col-lg-12">
                            <p class="service-txt">
                                We offer reliable website maintenance and
                                hosting along with web design packages with SEO,
                                security, updates, backups, support, and a peace
                                of mind!
                            </p>
                        </div>
                    </div>
                </a>

                <a
                    href="https://weblook.com/pbx-systems/"
                    class="digital-card col-lg-4 border pt-3 pb-3"
                >
                    <div class="row p-4">
                        <div class="col-lg-12 pb-4">
                            <div class="row">
                                <div class="col-lg-3">
                                    <img
                                        class=""
                                        src="/images/download.png"
                                        alt
                                        width="60"
                                        height="37"
                                        style="object-fit: contain"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 pb-2">
                            <h3 class="service-heading">
                                WordPress VPS Hosting
                            </h3>
                        </div>
                        <div class="col-lg-12">
                            <p class="service-txt">
                                Supercharge your WordPress Website. Eliminate
                                slowness and downtime with our secure,
                                customizable VPS Server. Build your dream
                                website, worry-free. Start now.
                            </p>
                        </div>
                    </div>
                </a>

                <a
                    href="https://weblook.com/cloud-hosting/"
                    class="digital-card col-lg-4 border pt-3 pb-3"
                >
                    <div class="row p-4">
                        <div class="col-lg-12 pb-4">
                            <div class="row">
                                <div class="col-lg-3">
                                    <img
                                        class=""
                                        src="/images/download.png"
                                        alt
                                        width="60"
                                        height="37"
                                        style="object-fit: contain"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 pb-2">
                            <h3 class="service-heading">Cloud Hosting</h3>
                        </div>
                        <div class="col-lg-12">
                            <p class="service-txt">
                                Choose our Cloud Servers for an affordable,
                                reliable, fast, and secure cloud hosting
                                experience that strategically avoids
                                overselling, ensuring optimal performance.
                            </p>
                        </div>
                    </div>
                </a>

                <a
                    href="https://weblook.com/vps-hosting/"
                    class="digital-card col-lg-4 border pt-3 pb-3"
                >
                    <div class="row p-4">
                        <div class="col-lg-12 pb-4">
                            <div class="row">
                                <div class="col-lg-3">
                                    <img
                                        class=""
                                        src="/images/download.png"
                                        alt
                                        width="60"
                                        height="37"
                                        style="object-fit: contain"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 pb-2">
                            <h3 class="service-heading">VPS Hosting</h3>
                        </div>
                        <div class="col-lg-12">
                            <p class="service-txt">
                                More control + extra flexibility. Dedicated
                                resources, full root access, & easy scaling.
                                It’s the virtual private server you’ve been
                                craving!
                            </p>
                        </div>
                    </div>
                </a>

                <a
                    href="https://weblook.com/web-hosting/"
                    class="digital-card col-lg-4 border pt-3 pb-3"
                >
                    <div class="row p-4">
                        <div class="col-lg-12 pb-4">
                            <div class="row">
                                <div class="col-lg-3">
                                    <img
                                        class=""
                                        src="/images/download.png"
                                        alt
                                        width="60"
                                        height="37"
                                        style="object-fit: contain"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 pb-2">
                            <h3 class="service-heading">Web Hosting</h3>
                        </div>
                        <div class="col-lg-12">
                            <p class="service-txt">
                                Explore our comprehensive web hosting services,
                                including dependable website maintenance, and
                                web design packages. Benefit from SEO, security,
                                updates, backups, and support for a worry-free
                                online experience!
                            </p>
                        </div>
                    </div>
                </a>

                <a
                    href="https://weblook.com/cloud-hosting/"
                    class="digital-card col-lg-4 border pt-3 pb-3"
                >
                    <div class="row p-4">
                        <div class="col-lg-12 pb-4">
                            <div class="row">
                                <div class="col-lg-3">
                                    <img
                                        class=""
                                        src="/images/download.png"
                                        alt
                                        width="60"
                                        height="37"
                                        style="object-fit: contain"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 pb-2">
                            <h3 class="service-heading">Cloud Based Backup</h3>
                        </div>
                        <div class="col-lg-12">
                            <p class="service-txt">
                                This method of backup serves as a robust
                                protection mechanism, shielding your data from
                                potential loss arising from disasters and cyber
                                attacks.
                            </p>
                        </div>
                    </div>
                </a>

                <a
                    href="https://weblook.com/dedicated-server/"
                    class="digital-card col-lg-4 border pt-3 pb-3"
                >
                    <div class="row">
                        <div class="col-lg-12 pb-4">
                            <div class="row">
                                <div class="col-lg-3">
                                    <img
                                        class=""
                                        src="/images/download.png"
                                        alt
                                        width="60"
                                        height="37"
                                        style="object-fit: contain"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 pb-2">
                            <h3 class="service-heading">Dedicated Server</h3>
                        </div>
                        <div class="col-lg-12">
                            <p class="service-txt">
                                Our dedicated servers offer effortless
                                protection of all your website. Rest assured
                                your data is safe with us!
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "@@/Layouts/AppLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";
import { gsap } from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";
import Swal from "sweetalert2";

gsap.registerPlugin(ScrollTrigger);

export default {
    components: {
        AppLayout,
    },
    props: {
        message: String,
    },
    data() {
        return {
            form: useForm({
                index: "",
            }),
        };
    },
    mounted() {
        if (this.message) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: this.message,
                confirmButtonText: "OK",
                confirmButtonColor: "#dc3545", // Custom button color
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    Inertia.visit(route("index"));
                    // Swal.fire("Saved!", "", "success");
                }
            });
        }
        this.setupLineAnimation();
    },
    methods: {
        setupLineAnimation() {
            const line = document.querySelector(".year-test");
            const sectionmaintext =
                document.querySelector(".section-main-text");

            gsap.set(line, { color: "black" });
            gsap.set(sectionmaintext, { color: "#2c2f32" });

            ScrollTrigger.create({
                trigger: line,
                markers: false,
                start: "-=50 center", // adjust as needed
                end: "+=280", // adjust as needed
                scrub: 1, // controls the smoothness of the animation
                onEnter: (self) => {
                    gsap.to(line, { color: "red", ease: "none" });
                },
                onEnterBack: (self) => {
                    gsap.to(line, { color: "red", ease: "none" });
                },
                onLeave: (self) => {
                    gsap.to(line, { color: "black", ease: "none" });
                },
                onLeaveBack: (self) => {
                    gsap.to(line, { color: "black", ease: "none" });
                },
            });
            ScrollTrigger.create({
                trigger: sectionmaintext,
                markers: false,
                start: "-=50 center", // adjust as needed
                end: "+=280", // adjust as needed
                scrub: 1, // controls the smoothness of the animation
                onEnter: (self) => {
                    gsap.to(sectionmaintext, { color: "red", ease: "none" });
                },
                onEnterBack: (self) => {
                    gsap.to(sectionmaintext, { color: "red", ease: "none" });
                },
                onLeave: (self) => {
                    gsap.to(sectionmaintext, { color: "black", ease: "none" });
                },
                onLeaveBack: (self) => {
                    gsap.to(sectionmaintext, { color: "black", ease: "none" });
                },
            });
        },
        submit() {
            const empIdRegex = /^EMP\d+$/;
            // Check if the employee ID starts with "EMP"
            if (!empIdRegex.test(this.form.index)) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: 'Employee ID must start with "EMP" and be followed by numbers',
                    confirmButtonColor: "#dc3545", // Custom button color
                });
                return;
            }
            console.log("Employee ID:", this.form.index);
            this.form.get(route("home-emp", { emp_id: this.form.index }));

            //   this.form.get(

            //        route("home-emp") // update route for created employee

            //         // onSuccess: (page) => {
            //         //   this.emailStatus = page.props.emailStatus; // Set email status from response
            //         // }
            //   );
        },
    },
};
</script>

<style scoped>
body {
    background: #eee !important;
    margin: 0;
    padding: 0;
}

.animated-line {
    position: absolute;
    top: 23%;
    left: 0;
    height: 2px;
    /* adjust thickness */
    background-color: #c80101;
    /* adjust color */
}

.wrapper {
    position: relative;
    min-height: 70vh;
    overflow: hidden;
}

#bg-video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: -1;
}

.background-image {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("/images/background-image.avif") no-repeat center center;
    background-size: cover;
    z-index: -1;
}

.intro-back {
    background-image: url("/images/nick-wessaert-JI01fn0U7Cg-unsplash-1-scaled-1.webp");
    background-size: cover;
    background-position: center;
}

.background-overlay {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.623);
    z-index: 0;
}

.content {
    position: relative;
    z-index: 1;
    width: 100%;
}

.form-container {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 380px;
}

.text-container {
    color: #fff;
}

@media (max-width: 768px) {
    #bg-video {
        display: none;
    }

    .background-image {
        display: block;
    }
}

.service-box {
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
}
.service-box img {
    height: 50px;
    margin-bottom: 20px;
}
</style>
