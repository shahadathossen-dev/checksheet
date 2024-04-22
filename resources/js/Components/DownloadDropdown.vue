<template>
	<dropdown width="48" :align="align">
		<template #trigger>
			<span>
				<button class="inline-flex bg-white text-black items-center px-6 py-3 shadow-md border border-transparent rounded-full font-bold text-xs uppercase tracking-widest disabled:opacity-25 transition">
					<span class="mr-4 ti-download"></span>
					<span class="">Download</span>
					<span class="ml-4 ti-angle-down text-primary-500 "></span>
				</button>
			</span>
		</template>

		<template #content>
			<div class="py-1">
				<slot>
					<div class="text-center">No download added</div>
				</slot>
			</div>
		</template>
	</dropdown>
</template>

<script>
import Dropdown from "@/Components/Dropdown.vue";
import ActionIcon from "@/Icons/ActionIcon.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

export default {
	components: {
		Dropdown,
		DropdownLink,
		ActionIcon,
	},

	props: {
		align: {
			type: String,
			default: "left",
		},
	},
};
</script>
