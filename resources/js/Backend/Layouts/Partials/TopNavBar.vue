<template>
  <nav
    class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar"
  >
    <div
      class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none"
    >
      <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
        <i class="bx bx-menu bx-sm"></i>
      </a>
    </div>

    <div
      class="navbar-nav-right d-flex align-items-center"
      id="navbar-collapse"
    >
    <ul class="navbar-nav flex-row align-items-center me-auto">
      <li v-if="$page.props.branch">
        <!-- <img :src="$page.props.branch ? $page.props.branch.image : ''" alt="" style="width:45px;height:45px;object-fit:cover;"> -->
      </li>
      <li class="ms-2">
        <p class="m-0" v-if="$page.props.branch">Selected branch</p>
      <h3 class="m-0">{{$page.props.branch ? $page.props.branch.name : "Configurations"}}</h3>
      </li>
    </ul>
      <ul class="navbar-nav flex-row align-items-center ms-auto">

        <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1"
        style="width: 200px">
        <span class="mx-3">{{ currentDate }}</span>
        <span>{{ currentTime }}</span>
        </li>
        <!-- branch change -->
        <!-- <li
          class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1"
          style="width: 215px"
        >
          <select
            class="select2 form-control form-select"
            id="branch_select"
            v-model="branchSelect.id"
          >
          
            <option selected value="null">Super Admin</option>
            <option
              :value="branch.id"
              v-for="branch in $page.props.all_branches"
              :key="branch.id"
            >
              {{ branch.name }}
            </option>
          </select>
        </li> -->
        <!-- Notification -->
        <!-- <li
          class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1"
        >
          <a
            class="nav-link dropdown-toggle hide-arrow"
            href="javascript:void(0);"
            data-bs-toggle="dropdown"
            data-bs-auto-close="outside"
            aria-expanded="false"
          >
            <i class="bx bx-bell bx-sm"></i>
            <span class="badge bg-danger rounded-pill badge-notifications"
              >5</span
            >
          </a>
          <ul class="dropdown-menu dropdown-menu-end py-0">
            <li class="dropdown-menu-header border-bottom">
              <div class="dropdown-header d-flex align-items-center py-3">
                <h5 class="text-body mb-0 me-auto">Notification</h5>
                <a
                  href="javascript:void(0)"
                  class="dropdown-notifications-all text-body"
                  data-bs-toggle="tooltip"
                  data-bs-placement="top"
                  title="Mark all as read"
                  ><i class="bx fs-4 bx-envelope-open"></i
                ></a>
              </div>
            </li>
            <li class="dropdown-notifications-list scrollable-container">
              <ul class="list-group list-group-flush">
                <li
                  class="list-group-item list-group-item-action dropdown-notifications-item"
                >
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar">
                        <img
                          src="/images/profile-avatar.png"
                          alt
                          class="w-px-40 h-auto rounded-circle"
                        />
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1">Congratulation Lettie 🎉</h6>
                      <p class="mb-0">Won the monthly best seller gold badge</p>
                      <small class="text-muted">1h ago</small>
                    </div>
                    <div class="flex-shrink-0 dropdown-notifications-actions">
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-read"
                        ><span class="badge badge-dot"></span
                      ></a>
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-archive"
                        ><span class="bx bx-x"></span
                      ></a>
                    </div>
                  </div>
                </li>
                <li
                  class="list-group-item list-group-item-action dropdown-notifications-item"
                >
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar">
                        <span
                          class="avatar-initial rounded-circle bg-label-danger"
                          >CF</span
                        >
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1">Charles Franklin</h6>
                      <p class="mb-0">Accepted your connection</p>
                      <small class="text-muted">12hr ago</small>
                    </div>
                    <div class="flex-shrink-0 dropdown-notifications-actions">
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-read"
                        ><span class="badge badge-dot"></span
                      ></a>
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-archive"
                        ><span class="bx bx-x"></span
                      ></a>
                    </div>
                  </div>
                </li>
                <li
                  class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read"
                >
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar">
                        <img
                          src="/images/profile-avatar.png"
                          alt
                          class="w-px-40 h-auto rounded-circle"
                        />
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1">New Message ✉️</h6>
                      <p class="mb-0">You have new message from Natalie</p>
                      <small class="text-muted">1h ago</small>
                    </div>
                    <div class="flex-shrink-0 dropdown-notifications-actions">
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-read"
                        ><span class="badge badge-dot"></span
                      ></a>
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-archive"
                        ><span class="bx bx-x"></span
                      ></a>
                    </div>
                  </div>
                </li>
                <li
                  class="list-group-item list-group-item-action dropdown-notifications-item"
                >
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar">
                        <span
                          class="avatar-initial rounded-circle bg-label-success"
                          ><i class="bx bx-cart"></i
                        ></span>
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1">Whoo! You have new order 🛒</h6>
                      <p class="mb-0">ACME Inc. made new order $1,154</p>
                      <small class="text-muted">1 day ago</small>
                    </div>
                    <div class="flex-shrink-0 dropdown-notifications-actions">
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-read"
                        ><span class="badge badge-dot"></span
                      ></a>
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-archive"
                        ><span class="bx bx-x"></span
                      ></a>
                    </div>
                  </div>
                </li>
                <li
                  class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read"
                >
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar">
                        <img
                          src="/images/profile-avatar.png"
                          alt
                          class="w-px-40 h-auto rounded-circle"
                        />
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1">Application has been approved 🚀</h6>
                      <p class="mb-0">
                        Your ABC project application has been approved.
                      </p>
                      <small class="text-muted">2 days ago</small>
                    </div>
                    <div class="flex-shrink-0 dropdown-notifications-actions">
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-read"
                        ><span class="badge badge-dot"></span
                      ></a>
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-archive"
                        ><span class="bx bx-x"></span
                      ></a>
                    </div>
                  </div>
                </li>
                <li
                  class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read"
                >
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar">
                        <span
                          class="avatar-initial rounded-circle bg-label-success"
                          ><i class="bx bx-pie-chart-alt"></i
                        ></span>
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1">Monthly report is generated</h6>
                      <p class="mb-0">
                        July monthly financial report is generated
                      </p>
                      <small class="text-muted">3 days ago</small>
                    </div>
                    <div class="flex-shrink-0 dropdown-notifications-actions">
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-read"
                        ><span class="badge badge-dot"></span
                      ></a>
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-archive"
                        ><span class="bx bx-x"></span
                      ></a>
                    </div>
                  </div>
                </li>
                <li
                  class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read"
                >
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar">
                        <img
                          src="/images/profile-avatar.png"
                          alt
                          class="w-px-40 h-auto rounded-circle"
                        />
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1">Send connection request</h6>
                      <p class="mb-0">Peter sent you connection request</p>
                      <small class="text-muted">4 days ago</small>
                    </div>
                    <div class="flex-shrink-0 dropdown-notifications-actions">
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-read"
                        ><span class="badge badge-dot"></span
                      ></a>
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-archive"
                        ><span class="bx bx-x"></span
                      ></a>
                    </div>
                  </div>
                </li>
                <li
                  class="list-group-item list-group-item-action dropdown-notifications-item"
                >
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar">
                        <img
                          src="/images/profile-avatar.png"
                          alt
                          class="w-px-40 h-auto rounded-circle"
                        />
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1">New message from Jane</h6>
                      <p class="mb-0">Your have new message from Jane</p>
                      <small class="text-muted">5 days ago</small>
                    </div>
                    <div class="flex-shrink-0 dropdown-notifications-actions">
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-read"
                        ><span class="badge badge-dot"></span
                      ></a>
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-archive"
                        ><span class="bx bx-x"></span
                      ></a>
                    </div>
                  </div>
                </li>
                <li
                  class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read"
                >
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar">
                        <span
                          class="avatar-initial rounded-circle bg-label-warning"
                          ><i class="bx bx-error"></i
                        ></span>
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1">CPU is running high</h6>
                      <p class="mb-0">
                        CPU Utilization Percent is currently at 88.63%,
                      </p>
                      <small class="text-muted">5 days ago</small>
                    </div>
                    <div class="flex-shrink-0 dropdown-notifications-actions">
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-read"
                        ><span class="badge badge-dot"></span
                      ></a>
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-archive"
                        ><span class="bx bx-x"></span
                      ></a>
                    </div>
                  </div>
                </li>
              </ul>
            </li>
            <li class="dropdown-menu-footer border-top">
              <a
                href="javascript:void(0);"
                class="dropdown-item d-flex justify-content-center p-3"
              >
                View all notifications
              </a>
            </li>
          </ul>
        </li> -->
        <!--/ Notification -->
        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
          <a
            class="nav-link dropdown-toggle hide-arrow"
            href="javascript:void(0);"
            data-bs-toggle="dropdown"
          >
            <div class="avatar avatar-online">
              <img
                :src="
                  $page.props.user.profile_photo_path
                    ? $page.props.user.profile_photo_path
                    : '/images/profile-avatar.png'
                "
                alt
                class="w-px-40 h-auto rounded-circle"
              />
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item">
                <div class="d-flex">
                  <div class="flex-shrink-0 me-3">
                    <div class="avatar avatar-online">
                      <img
                        :src="
                          $page.props.user.profile_photo_path
                            ? $page.props.user.profile_photo_path
                            : '/images/profile-avatar.png'
                        "
                        alt
                        class="w-px-40 h-auto rounded-circle"
                      />
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <span class="fw-semibold d-block text-capitalize">{{
                      $page.props.user.name
                    }}</span>
                    <small class="text-muted">{{
                      $page.props.user.roles.length > 0
                        ? $page.props.user.roles[0].name
                        : ""
                    }}</small>
                  </div>
                </div>
              </a>
            </li>
            <li>
              <div class="dropdown-divider"></div>
            </li>
            <li>
              <Link class="dropdown-item" :href="route('profile')">
                <i class="bx bx-user me-2"></i>
                <span class="align-middle">My Profile</span>
              </Link>
            </li>
            <li>
              <div class="dropdown-divider"></div>
            </li>
            <li>
              <Link
                class="dropdown-item"
                :href="route('logout')"
                method="post"
                as="button"
              >
                <i class="bx bx-power-off me-2"></i>
                <span class="align-middle">Log Out</span>
              </Link>
            </li>
          </ul>
        </li>
        <!--/ User -->
      </ul>
    </div>
  </nav>
</template>
<script>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import SelectInputComponent from "@/Components/SelectInputComponent.vue";
import { Inertia } from '@inertiajs/inertia';
export default {
  components: {
    Link,
    SelectInputComponent,
  },
  props: {},
  data() {
    return {
      currentDate: this.getCurrentDate(),
      currentTime: this.getCurrentTime(),
      branchSelect: useForm({
        id: "",
      }),
    };
  },
  created() {},
  mounted() {
    this.updateClock();
    setInterval(this.updateClock, 1000);

    var self = this;
    if(this.$page.props.branch) {
      this.branchSelect.id = this.$page.props.branch.id
    }

    $("#branch_select").select2();
    $("#branch_select").on("change", function (evt) {
      self.branchSelect.id = $(evt.target).val();
      self.branchSelect.post(route("branch.set.backend"), {
        onSuccess: () => {
          Inertia.visit(route('dashboard'));
          self.$root.showMessage(
            "success",
            '<span class="text-success">Success</span><br/>',
            "branch Change Successfully! "
          );
        },
      });
    });
  },
  computed: {},
  methods: {
    getCurrentTime() {
      const now = new Date();
      
      let hours = now.getHours();
      const minutes = now.getMinutes();
      const ampm = hours >= 12 ? 'PM' : 'AM';
      
      hours = hours % 12;
      hours = hours ? hours : 12; 
      
      const minutesStr = minutes < 10 ? '0' + minutes : minutes;
      
      return `${hours}:${minutesStr} ${ampm}`;
    },
    getCurrentDate() {
      const now = new Date();
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return now.toLocaleDateString(undefined, options);
    },
    updateClock() {
      this.currentTime = this.getCurrentTime();
      this.currentDate = this.getCurrentDate();
    },
 
    logout() {
      this.$inertia.post(route("logout"));
    },
  },
};
</script>

<style scoped>
.sidebar-toggle-icon {
  width: 2rem;
  height: 2rem;
}
</style>