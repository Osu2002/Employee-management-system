<template>
    <!-- Menu -->

    <aside
        id="layout-menu"
        class="layout-menu menu-vertical menu bg-menu-theme"
    >
        <div class="app-brand demo justify-content-center">
            <a
                href="/admin"
                class="app-brand-link"
                style="justify-content: center"
            >
                <span
                    class="app-brand-logo demo"
                    style="justify-content: center"
                >
                    <img
                        src="/images/Weblook-300x65.png"
                        alt=""
                        style="height: 40px; width: auto"
                    />
                </span>
                <span
                    class="app-brand-text demo menu-text fw-bolder ms-2"
                ></span>
            </a>

            <a
                href="javascript:void(0);"
                class="layout-menu-toggle menu-link text-large ms-auto"
            >
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
            <!-- Dashboards -->
            <li
                class="menu-item"
                v-bind:class="{ active: addActiveClass(['dashboard']) }"
            >
                <Link :href="route('dashboard')" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-tachometer"></i>
                    <div data-i18n="Dashboards">Dashboard</div>
                </Link>
            </li>

            <!-- Media Library -->
            <!-- <li class="menu-item" v-bind:class="{ active: addActiveClass(['media.index']) }"
        v-if="$root.hasPermission('media.view') && !$page.props.branch">
        <Link :href="route('media.index', { c: 'default' })" class="menu-link">
        <i class="menu-icon tf-icons bx bx-photo-album"></i>
        <div data-i18n="Media Library">Media Library</div>
        </Link>
      </li> -->

            <!-- Employee -->
            <li
                class="menu-item"
                v-bind:class="{ active: addActiveClass(['employee']) }"
                v-if="$root.hasPermission('employee.view')"
            >
                <Link :href="route('employee')" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-pin"></i>
                    <div data-i18n="Employees">Employee</div>
                </Link>
            </li>

            <li
                class="menu-item"
                v-bind:class="{
                    active: addActiveClass(['admin.leaves.index']),
                }"
                v-if="$root.hasPermission('leaves.view')"
            >
                <Link :href="route('admin.leaves.index')" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-log-out-circle"></i>
                    <div data-i18n="Leaves">Leaves</div>
                </Link>
            </li>
            <li
                class="menu-item"
                v-bind:class="{
                    active: addActiveClass(['contact.hr.index']),
                }"
                v-if="$root.hasPermission('employee.leaves')"
            >
                <Link :href="route('contact.hr.index')" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-message-detail"></i>
                    <div data-i18n="Leaves">Contact HR</div>
                </Link>
            </li>
            <!-- Settings -->
            <li
                class="menu-item"
                v-bind:class="{
                    'active open': addActiveClass([
                        'settings.users',
                        'settings.users.create',
                        'settings.users.edit',
                        'settings.roles',
                        'settings.roles.create',
                        'settings.roles.edit',
                        'settings.roles.permissions',
                        'settings.general',
                        'settings.social-auth',
                        'settings.mail',
                        'payment-options.index',
                        'currencies.index',
                        'log.index',
                        'job-roles.index',
                        'job-roles.create',
                        'job-roles.edit'
                    ]),
                }"
                v-if="!$page.props.branch"
            >
                <a
                    v-if="$root.hasPermission('system-setting.view')"
                    href="javascript:void(0);"
                    class="menu-link menu-toggle"
                >
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div data-i18n="Settings">Settings</div>
                </a>

                <ul class="menu-sub">
                    <li
                        class="menu-item"
                        v-bind:class="{
                            active: addActiveClass([
                                'settings.users',
                                'settings.users.create',
                                'settings.users.edit',
                            ]),
                        }"
                        v-if="$root.hasPermission('backend-user.view')"
                    >
                        <Link :href="route('settings.users')" class="menu-link">
                            <div data-i18n="Backend Users">Backend Users</div>
                        </Link>
                    </li>
                    <li
                        class="menu-item"
                        v-bind:class="{
                            active: addActiveClass([
                                'settings.roles',
                                'settings.roles.create',
                                'settings.roles.edit',
                                'settings.roles.permissions',
                            ]),
                        }"
                        v-if="$root.hasPermission('roles-permissions.view')"
                    >
                        <Link :href="route('settings.roles')" class="menu-link">
                            <div data-i18n="Roles & Permissions">
                                Roles & Permissions
                            </div>
                        </Link>
                    </li>
                    <li
                        class="menu-item"
                        v-bind:class="{
                            active: addActiveClass(['settings.general']),
                        }"
                        v-if="$root.hasPermission('system-setting.view')"
                    >
                        <Link
                            :href="route('settings.general')"
                            class="menu-link"
                        >
                            <div data-i18n="General Settings">
                                General Settings
                            </div>
                        </Link>
                    </li>
                    <li
                        class="menu-item"
                        v-bind:class="{
                            active: addActiveClass(['settings.social-auth']),
                        }"
                        v-if="$root.hasPermission('system-setting.view')"
                    >
                        <Link
                            :href="route('settings.social-auth')"
                            class="menu-link"
                        >
                            <div data-i18n="Social Auth Settings">
                                Social Auth Settings
                            </div>
                        </Link>
                    </li>
                    <li
                        class="menu-item"
                        v-bind:class="{
                            active: addActiveClass(['currencies.index']),
                        }"
                        v-if="$root.hasPermission('currencies.view')"
                    >
                        <Link
                            :href="route('currencies.index')"
                            class="menu-link"
                        >
                            <div data-i18n="Currencies">Currencies</div>
                        </Link>
                    </li>
                    <li
                        class="menu-item"
                        v-bind:class="{
                            active: addActiveClass(['settings.mail']),
                        }"
                        v-if="$root.hasPermission('system-setting.view')"
                    >
                        <Link :href="route('settings.mail')" class="menu-link">
                            <div data-i18n="Mail Settings">Mail Settings</div>
                        </Link>
                    </li>

                    <li
                        class="menu-item"
                        v-bind:class="{
                            active: addActiveClass(['job-roles.index', 'job-roles.create', 'job-roles.edit']),
                        }"
                        v-if="$root.hasPermission('job-roles.view')"
                    >
                        <Link
                            :href="route('job-roles.index')"
                            class="menu-link"
                        >
                            <div data-i18n="Job Role">Job Role</div>
                        </Link>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
    <!-- / Menu -->
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
    components: {
        Link,
    },
    data() {
        return {
            showingNavigationDropdown: false,
            expanded: true,
            collapsed: false,
            hidden: true,
            currentRoute: "",
        };
    },
    mounted() {
        new PerfectScrollbar(".menu-inner", {
            wheelPropagation: false,
            wheelSpeed: 0.5,
        });
    },
    methods: {
        addActiveClass(routes) {
            if (routes.includes(route().current())) {
                return true;
            } else {
                return false;
            }
        },
    },
};
</script>

<style>
.rotate {
    transform: rotate(90deg);
}

.main-menu-content {
    max-height: 100vh;
    overflow-y: scroll;
    overflow-x: hidden;
}
</style>
