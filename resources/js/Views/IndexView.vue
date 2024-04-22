<template>
	<app-layout :title="title">
		<div class="flex flex-col sm:flex-row justify-between sm:items-center mb-5">
			<h1 class="font-bold text-2xl">{{title}}</h1>
		</div>
		<slot></slot>
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
	},
	components: {
		AppLayout,

	},
};
</script>

<style lang="scss" scoped>
</style>
