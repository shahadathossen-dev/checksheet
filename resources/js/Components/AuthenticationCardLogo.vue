<script setup>
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <Link :href="'/'">
        <img class="w-60 rounded-lg" src="../../images/logo-mark.png" alt="{{ env('APP_NAME') }}" />
    </Link>
</template>
