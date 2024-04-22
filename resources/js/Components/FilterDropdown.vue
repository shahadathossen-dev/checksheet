<template>
    <dropdown width="64" align="left">
        <template #trigger>
            <button class="inline-flex bg-transparent text-black items-center px-5 py-2  border border-primary-500 rounded-primary font-bold text-sm tracking-widest disabled:opacity-25 transition">
                <span class="mr-4 ti-calendar"></span>
                <span class="">Filter</span>
                <span class="ml-4 ti-angle-down text-primary-500 "></span>
            </button>
        </template>

        <template #content>
            <div class="p-2">
                <slot>
                    <div>No Filter Added.</div>
                </slot>
            </div>
        </template>
    </dropdown>
</template>

<script>
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
export default {
    components: {
        Dropdown,
        DropdownLink,
    },
};
</script>
