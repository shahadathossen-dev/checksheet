<script setup>
import ConfigIcon from "@/Icons/ConfigIcon.vue";
import Dropdown from "@/Components/Dropdown.vue";
</script>

<template>
  <Dropdown width="80">
    <template #trigger>
      <button
        class="px-4 relative text-xl text-gray-800 rounded-full hover:text-primary-500 focus:outline-none focus:text-primary-600 transition duration-150 ease-in-out"
      >
        <ConfigIcon />
      </button>
    </template>

    <template #content>
      <div
        class="border-b px-4 py-2 text-sm leading-5 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition"
        style="max-height: 245px; overflow: auto"
      >
        Configuration content
      </div>
    </template>
  </Dropdown>
</template>

<style lang="scss" scoped></style>
