<template>
	<app-layout :title="title">
		<div class="flex flex-col sm:flex-row justify-between sm:items-center mb-5">
			<h1 class="font-bold text-xl">{{title}}</h1>
		</div>
		<div class="w-full bg-white rounded-xl overflow-hidden">
			<!-- Default Slot -->
			<slot></slot>
		</div>

		<div>
			<slot name="secondary-view"></slot>
		</div>

	</app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";


export default {
	props: {
		title: {
			type: String,
			default: "",
		},
		breadcrumb: {
			type: Array,
			default: () => [],
		},
	},
	components: {
		AppLayout,
	},
};
</script>

<style lang="scss" scoped>
</style>
