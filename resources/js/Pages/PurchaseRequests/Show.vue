<template>
	<detail-view title="Purchase Request Details">

		<!-- Title -->
		<detail-section class="border-b" label="Title" :value="purchaseRequest.title"></detail-section>
		<detail-section class="border-b" label="Description" :value="purchaseRequest.description"></detail-section>
		<!-- Date -->
		<!-- <detail-section class="border-b" label="Due Date" :value="purchaseRequest.dueDate"></detail-section> -->
		<!-- Author -->
		<detail-section class="border-b" label="Assignee" :value="purchaseRequest.user.name"></detail-section>
		<detail-section class="border-b" label="Status" :value="purchaseRequest.status"></detail-section>
	</detail-view>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import DetailView from "@/Views/DetailView.vue";
import DetailSection from "@/Components/DetailSection.vue";
import { Link } from "@inertiajs/inertia-vue3";
import Datatable from "@/Components/Datatable.vue";

export default {
	name: "additional-task-details",
	props: {
		purchaseRequest: Object,
	},

	components: {
		AppLayout,
		DetailView,
		DetailSection,
		Link,
		Datatable,
	},

};
</script>
