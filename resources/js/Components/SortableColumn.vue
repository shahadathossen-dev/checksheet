<script setup>
import { computed, ref } from 'vue';

const emit = defineEmits(['update:modelValue']);

const props = defineProps({
    columnName: {
        type: String,
        required: true
    },
    modelValue: {
        type: String,
        default: null,
    },
});

const counter = ref(0);

const sortOrder = computed(() => {
    const direction = counter.value < 2 ? 'asc' : 'desc';
    return counter.value > 0 ?`${props.columnName},${direction}` : null;
});

const updateSort = () => {
    counter.value = counter.value < 2 ? counter.value + 1 : 0;
    emit('update:modelValue', sortOrder.value);
    
}
</script>

<template>
    <span class="group flex justify-between items-center" @click="updateSort">
		<slot></slot>
        
        <i v-if="counter == 1" class="ti-arrow-down text-xs ml-1 group-hover:text-gray-900 text-gray-500" title="asc"></i>
        <i v-else-if="counter == 2" class="ti-arrow-up text-xs ml-1 group-hover:text-gray-900 text-gray-500" title="desc"></i>
        <i v-else class="ti-exchange-vertical text-xs ml-1 group-hover:text-gray-900 text-gray-500" title="None"></i>
    </span>
</template>
