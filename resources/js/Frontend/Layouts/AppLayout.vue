<template>
    <!-- Layout wrapper -->
    <div class="">
        <TopNavBar />

        <slot />


        <FooterBar />
    </div>
    <!-- / Layout wrapper -->
</template>

<script>
import TopNavBar from "@@/Layouts/Partials/TopNavBar.vue";
import SideNavBar from "@/Layouts/Partials/SideNavBar.vue";
import FooterBar from "@@/Layouts/Partials/FooterBar.vue";

export default {
    components: {
        TopNavBar,
        SideNavBar,
        FooterBar,
    },
    mounted() {
        $(document).ready(function () {
            // Default
            if ($(".select2").length) {
                $(".select2").each(function () {
                    var $this = $(this);
                    $this
                        .wrap('<div class="position-relative"></div>')
                        .select2({
                            placeholder: "-- Select --",
                            dropdownParent: $this.parent(),
                        });
                });
            }

            const selectPicker = $(".selectpicker");
            if (selectPicker.length) {
                selectPicker.selectpicker();
            }
        });
        const popoverTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="popover"]')
        );
        const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            // added { html: true, sanitize: false } option to render button in content area of popover
            return new bootstrap.Popover(popoverTriggerEl, {
                html: false,
                sanitize: false,
            });
        });

        $("body").on("hidden.bs.modal", ".modal", function () {
            console.log("ssss");
            if ($("body").hasClass("modal-open")) {
                $("body").removeClass("modal-open").attr("style", "");
            }
        });

        // handle side menu bar
        $("body").on("click", ".layout-menu-toggle", function () {
            $("#layout-menu").toggleClass("left-menu-open");
            $("#layout-page").toggleClass("layout-page-menu-open");
        });

        $(".menu-link.menu-toggle").on("click", function (e) {
            let parentEl = $(this).parent();
            $(parentEl).toggleClass("open active", 300);
        });
    },
    methods: {
        switchToTeam(team) {
            this.$inertia.put(
                route("current-team.update"),
                {
                    team_id: team.id,
                },
                {
                    preserveState: false,
                }
            );
        },

        logout() {
            console.log("logout");
            // this.$inertia.post(route("logout"));
        },
    },
};
</script>
