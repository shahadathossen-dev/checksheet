<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <div class="flex justify-center items-center">
        <button :type="type" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-600 disabled:opacity-25 transition">
            <slot />
        </button>
    </div>
</template>
