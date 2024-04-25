<script setup>
import { ref, computed, onMounted, onDeactivated } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, Link } from "@inertiajs/inertia-vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { useStore } from "vuex";

import MenuIcon from "@/Icons/MenuIcon.vue";
import Sidebar from "@/Layouts/Partials/Sidebar.vue";
import SearchInput from "../Components/SearchInput.vue";
import ConfigDropdown from "@/Layouts/Partials/ConfigDropdown.vue";
import SettingsDropdown from "@/Layouts/Partials/SettingsDropdown.vue";
import NotificationDropdown from "@/Layouts/Partials/NotificationDropdown.vue";
import ChatNotificationDropdown from "@/Layouts/Partials/ChatNotificationDropdown.vue";

defineProps({
  title: String,
  breadcrumb: Array,
});

const store = useStore();

// access a state in computed function
const collapsed = computed(() => store.state.isCollapsed);

const handleResize = () => {
  const baseWidth = 1024;
  if (window.innerWidth <= baseWidth) {
    () => store.dispatch("setCollapsed", true);
  }
};

onMounted(() => {
    // Hide Sidebar menu in Medium device
    handleResize();
    // Handle Window Resize
    window.addEventListener("resize", handleResize);
});

onDeactivated(() => {
    window.addEventListener("resize", handleResize);
});

const toggleSidebar = () => store.dispatch("toggleCollapsed");
</script>

<template>
  <div
    class="overlay h-screen w-full lg:hidden"
    v-if="!collapsed"
    @click="store.dispatch('setCollapsed', true)"
  ></div>

  <div class="flex">
    <Sidebar :key="$page.url" :collapsed="collapsed"></Sidebar>
    <div class="flex-grow overflow-x-hidden">
      <Head :title="title" />

      <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100 shadow-md">
          <!-- Primary Navigation Menu -->
          <div class="px-8">
            <div class="flex justify-between h-20 items-center">
              <!-- left content -->
              <div class="items-center flex">
                <MenuIcon class="cursor-pointer" @click="toggleSidebar"></MenuIcon>
              </div>

              <!-- Right content -->
              <div class="flex items-center justify-between ml-6">
                <!-- <SearchInput class="mr-6"></SearchInput> -->

                <div class="flex justify-between ml-6">
                  <!-- chatbox Dropdown -->
                  <ChatNotificationDropdown />

                  <!-- Configuration Dropdown -->
                  <ConfigDropdown />

                  <!-- Notification Dropdown -->
                  <NotificationDropdown class="ml-4" />
                </div>

                <!-- Settings Dropdown -->
                <SettingsDropdown />
              </div>
            </div>
          </div>
        </nav>

        <!-- Page Content -->
        <main
          class="p-4 lg:p-6 overflow-y-auto w-full relative"
          style="height: calc(100vh - 85px)"
          scroll-region
        >
          <Banner />
          <slot></slot>
        </main>
      </div>
    </div>
  </div>
</template>
