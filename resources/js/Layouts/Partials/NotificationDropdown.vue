<script setup>
import { usePage, Link } from '@inertiajs/inertia-vue3';
import NotificationIcon from "@/Icons/NotificationIcon.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { computed, ref } from 'vue';
import axios from 'axios';

const notifications = ref(usePage().props.value.notifications);
const unreadCount = computed(() => notifications.value.reduce((unreadTotal, item) => (item.read_at == null) ? unreadTotal+1 : unreadTotal, 0));

// const markAsRead = async (notification, index) => {
//   try {
//     await axios.get(route('notifications.mark-as-read', notification.id));
//     router.get(route('tasklists.show', notification.data.id))
//   } catch (error) {
//     console.log(error); 
//   }
// }

const markAllAsRead = async () => {
  try {
    await axios.post(route('notifications.mark-all-read'));
    notifications.value = notifications.value.map((item) => {item.read_at = Date.now(); return item;});
  } catch (error) {
    console.log(error); 
  }
}

const deleteAll = async () => {
  try {
    await axios.delete(route('notifications.delete-all'));
    notifications.value = [];
  } catch (error) {
    console.log(error); 
  }
}
</script>

<template>
  <Dropdown width="80">
    <template #trigger>
      <button
        class="relative text-xl text-gray-800 rounded-full hover:text-primary-500 focus:outline-none focus:text-primary-600 transition duration-150 ease-in-out"
      >
        <NotificationIcon />
        <span class="count absolute -top-1/2 super text-xs text-white p-1 rounded-full bg-primary-400">
          {{ unreadCount }}
        </span>
      </button>
    </template>

    <template #content>
      <div class="notifications p-4 w-96">
        <div class="header pb-2 font-bold border-b">Notifications</div>
        <ul class="max-h-72 overflow-auto" v-if="notifications.length">
          <li class="p-2 border-b border-gray-200" :class="{'bg-gray-100': !notification.read_at}" v-for="(notification, index) in notifications" :key="index">
            <Link class="block" title="Details" :href="route('tasklists.show', {tasklist: notification.data.id, notification: notification.id})">
                {{ notification.data.title }}
            </Link>
          </li>
        </ul>
        <div class="no-data text-center py-4" v-else>No data found!</div>
        <div class="footer pt-2 text-center flex justify-around border-t">
            <button class="btn btn-success" @click.prevent="markAllAsRead">Mark all as read</button>
            <button class="btn btn-primary" @click.prevent="deleteAll">Delete All</button>
        </div>
      </div>
    </template>
  </Dropdown>
</template>

<style lang="scss" scoped></style>
