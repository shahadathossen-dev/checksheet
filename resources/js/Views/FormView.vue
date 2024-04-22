<template>
  <app-layout :title="title">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-5">
      <h1 class="text-xl font-bold text-gray-900">{{ title }}</h1>
    </div>
    <form @submit.prevent="$emit('submitted')">
      <div
        class="bg-white shadow w-full overflow-hidden"
        :class="
          hasActions
            ? 'rounded-tl-primary rounded-tr-primary'
            : 'rounded-primary'
        "
      >
        <slot name="form"></slot>
      </div>

      <div
        class="
          flex flex-col-reverse
          xs:flex-row xs:justify-end xs:items-center
          px-4
          py-5
          sm:px-6
          bg-gray-50
          text-center
          rounded-bl-primary rounded-br-primary
          w-full
          shadow
        "
        v-if="hasActions"
      >
        <slot name="actions"></slot>
      </div>
    </form>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
export default {
  emits: ["submitted"],
  props: {
    title: {
      type: String,
      default: "",
    },
  },

  components: {
    AppLayout,
  },

  computed: {
    hasActions() {
      return !!this.$slots.actions;
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
