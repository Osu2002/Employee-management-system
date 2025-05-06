<template>
  <li class="nav-item" v-if="subItems.length < 1">
    <Link class="d-flex align-items-center" :href="route(itemLink)">
      <i data-feather="x"></i>
      <span class="menu-title text-truncate" data-i18n="User">{{
        itemTitle
      }}</span>
    </Link>
  </li>
  <li class="nav-item" v-else>
    <a class="d-flex align-items-center" href="#">
      <i data-feather="tool"></i>
      <span class="menu-title text-truncate" data-i18n="User">{{
        itemTitle
      }}</span>
    </a>
    <ul class="menu-content" v-bind:class="{'d-block':String(currentUrl).includes(identifier)}">
      <li v-for="subItem in subItems" :key="subItem.id">
        <Link class="d-flex align-items-center" :href="route(subItem.link)" v-if="!subItem.hasOwnProperty('subItemsL1')">
          <i data-feather="circle"></i>
          <span class="menu-item text-truncate" data-i18n="List">{{
            subItem.name
          }}</span>
        </Link>

        <a class="d-flex align-items-center" href="#" v-if="subItem.hasOwnProperty('subItemsL1')">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate" data-i18n="User">{{
            subItem.name
          }}</span>
        </a>
        <ul class="menu-content" v-if="subItem.hasOwnProperty('subItemsL1')" v-bind:class="{'d-block':String(currentUrl).includes(subItem.link)}">
          <li v-for="subItemL1 in subItem.subItemsL1" :key="subItemL1.id">
            <Link class="d-flex align-items-center" :href="route(subItemL1.link)">
              <i data-feather="circle"></i>
              <span class="menu-item text-truncate" data-i18n="List">{{
                subItemL1.name
              }}</span>
            </Link>
          </li>
        </ul>
      </li>
      <!-- nested items -->
    </ul>
  </li>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
  components: {
    Link,
  },
  props: {
    identifier: String,
    itemTitle: String,
    itemLink: {
      type: String,
      default: "dashboard",
    },
    subItems: Array,
  },
  data() {
    return {
      currentUrl: route().current()
    }
  }
};
</script>

<style></style>
