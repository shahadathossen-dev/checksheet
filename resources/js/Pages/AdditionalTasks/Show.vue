<template>
	<detail-view title="Additional Task Details">

		<!-- Title -->
		<detail-section class="border-b" label="Title" :value="additionalTask.title"></detail-section>
		<detail-section class="border-b" label="Description" :value="additionalTask.description"></detail-section>
		<!-- Date -->
		<detail-section class="border-b" label="Due Date" :value="additionalTask.dueDate"></detail-section>
		<detail-section class="border-b" label="Submit Date" :value="additionalTask.submitDate"></detail-section>
		<!-- Author -->
		<detail-section class="border-b" label="Assignee" :value="additionalTask.assignee.name"></detail-section>
		<detail-section class="border-b" label="Author" :value="additionalTask.author.name"></detail-section>

		<detail-section class="border-b" label="Status" :value="additionalTask.status"></detail-section>
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
		additionalTask: Object,
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
