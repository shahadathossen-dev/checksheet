<template>
    <label class="block font-medium text-sm text-gray-700">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot></slot></span>
        <span v-if="required" class="text-primary-500">*</span>
    </label>
</template>

<script>
    import { defineComponent } from 'vue'
    export default {
        props: {
            value: {type: String},
            required: {
                type: Boolean,
                default: false
            }
        },
        // inheritAttrs: false,
    }
</script>
