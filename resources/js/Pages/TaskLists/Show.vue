<template>
	<detail-view title="Task List Details">

		<!-- Title -->
		<detail-section class="border-b" label="Title" :value="tasklist.checksheet.title"></detail-section>
		<detail-section class="border-b" label="Description" :value="tasklist.checksheet.description"></detail-section>
		<!-- Date -->
		<detail-section class="border-b" label="Due Date" :value="tasklist.dueDateFormatted"></detail-section>
		<detail-section class="border-b" label="Submit Date" :value="tasklist.submitDateFormatted"></detail-section>
		<!-- Author -->
		<detail-section class="border-b" label="Assignee" :value="tasklist.assignee?.name"></detail-section>
		<detail-section class="border-b" label="Author" :value="tasklist.author?.name"></detail-section>
		
		<detail-section class="border-b" label="Type" :value="tasklist.type"></detail-section>
		<detail-section class="border-b" label="Status" :value="tasklist.status"></detail-section>

		<template #secondary-view>
			<div class="mt-8">
				<h1 class="font-bold text-xl">Task Items</h1>
				<datatable :data="{data: tasklist.items}">
					<template #default="{rows}">
						<table v-if="rows.length">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Note</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(row, index) in rows" :key="index">
									<td>{{index + 1}}</td>
									<td>{{row.checksheetItem.title}}</td>
									<td>{{row.note}}</td>
									<td>{{row.done ? 'Done' : 'Not done'}}</td>
								</tr>
							</tbody>
						</table>
					</template>
					<template #nodata>No Subscriptions Found</template>
				</datatable>
			</div>
		</template>
	</detail-view>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import DetailView from "@/Views/DetailView.vue";
import DetailSection from "@/Components/DetailSection.vue";
import { Link } from "@inertiajs/inertia-vue3";
import Datatable from "@/Components/Datatable.vue";

export default {
	name: "tasklist-details",
	props: {
		tasklist: Object,
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
