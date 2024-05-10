<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div v-show="message" class="error-message">
        <p class="text-sm text-red-600">
            {{ message }}
        </p>
    </div>
</template>
