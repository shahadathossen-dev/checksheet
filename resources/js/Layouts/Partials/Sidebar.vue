<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import SidebarItem from "@/Layouts/Partials/SidebarItem.vue";
import SubmenuItem from "@/Layouts/Partials/SubmenuItem.vue";
import { Link } from "@inertiajs/inertia-vue3";
import UserIcon from "@/Icons/UserIcon.vue";

defineProps({
  collapsed: Boolean,
});
const isActive = (routes) => routes.some((currentRoute) => route().current(currentRoute));
</script>

<template>
  <div class="sidebar shadow-md" :class="{ collapsed: collapsed }">
    <Link :href="route('dashboard')" class="flex items-center justify-center px-5 h-20">
      <div class="ml-4 flex-grow">
        <Transition name="zoomin">
          <ApplicationLogo class="" />
        </Transition>
      </div>
    </Link>

    <div class="px-4 overflow-auto" style="max-height: calc(100vh - 80px)">
      <!-- Dashboard -->
      <SidebarItem
        :href="route('dashboard')"
        :active="route().current('dashboard')"
        :collapsed="collapsed"
      >
        Dashboard
      </SidebarItem>
      <!-- RESOURCES -->
      <p
        class="px-4 py-3 text-xs font-bold text-gray-400 uppercase"
        v-if="!collapsed && hasPermissions(['viewAnyRoles', 'viewAnyUsers'])"
      >
        ACL
      </p>

      <!-- People -->
      <SidebarItem
        :active="isActive(['roles.*', 'users.*', 'messages.*'])"
        :collapsed="collapsed"
        v-if="hasPermissions(['viewAnyRoles', 'viewAnyUsers'])"
      >
        <template #icon>
          <i class="ti-user" title="ACL"></i>
        </template>
        ACL
        <!-- Nested Menu Items -->
        <template #submenu>
          <!-- Roles -->
          <SubmenuItem
            title="Roles"
            :href="route('roles.index')"
            :active="isActive(['roles.*'])"
            :collapsed="collapsed"
            v-if="hasPermissions(['viewAnyRoles'])"
          >
            Roles
          </SubmenuItem>
          <!-- Users -->
          <SubmenuItem
            title="Users"
            :href="route('users.index')"
            :active="isActive(['users.*'])"
            :collapsed="collapsed"
            v-if="hasPermissions(['viewAnyUsers'])"
          >
            Users
          </SubmenuItem>
        </template>
      </SidebarItem>

      <!-- RESOURCES -->
      <p
        class="px-4 py-3 text-xs font-bold text-gray-400 uppercase"
        v-if="!collapsed && hasPermissions(['viewAnyCheckSheets', 'viewAnyTaskLists'])"
      >
        Resources
      </p>

      <!-- Check Sheets -->
      <SidebarItem
        title="Check Sheets"
        :active="isActive(['checksheets.*'])"
        :href="route('checksheets.index')"
        :collapsed="collapsed"
        v-if="hasPermissions(['viewAnyCheckSheets'])"
      >
        <template #icon>
          <i class="ti-package" title="Check Sheets"></i>
        </template>
        Check Sheets
      </SidebarItem>

      <SidebarItem
        title="Task Lists"
        :active="isActive(['tasklists.*'])"
        :href="route('tasklists.index')"
        :collapsed="collapsed"
        v-if="hasPermissions(['viewAnyTaskLists'])"
      >
        <template #icon>
          <i class="ti-user" title="Task List"></i>
        </template>
        Task List
      </SidebarItem>

      <!-- Holidays -->
      <SidebarItem
        title="Leaves"
        :active="isActive(['leaves.*'])"
        :href="route('leaves.index')"
        :collapsed="collapsed"
        v-if="hasPermissions(['viewAnyLeaves'])"
      >
        <template #icon>
          <i class="ti-package" title="Leaves"></i>
        </template>
        Leaves
      </SidebarItem>

      <!-- Delegates -->
      <!-- <SidebarItem
        title="Delegates"
        :active="isActive(['delegates.*'])"
        :href="route('delegates.index')"
        :collapsed="collapsed"
        v-if="hasPermissions(['viewAnyDelegates'])"
      >
        <template #icon>
          <i class="ti-user" title="Delegates"></i>
        </template>
        Delegates
      </SidebarItem> -->
    </div>
  </div>
</template>

<style lang="scss">
.sidebar {
  @apply h-screen bg-white overflow-hidden w-72 flex-shrink-0 absolute lg:relative transition-all duration-500 ease-in-out z-20;
  &.collapsed {
    @apply lg:w-20 -translate-x-full lg:translate-x-0;
  }
}
</style>
