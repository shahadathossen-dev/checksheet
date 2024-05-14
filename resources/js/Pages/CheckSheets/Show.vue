<template>
	<detail-view title="Check Sheet Details">

		<!-- Title -->
		<detail-section class="border-b" label="Title" :value="checksheet.title"></detail-section>
		<!-- Date -->
		<detail-section class="border-b" label="Type" :value="checksheet.type"></detail-section>
		<detail-section class="border-b" label="Due By" :value="checksheet.dueBy"></detail-section>
		<!-- Author -->
		<detail-section class="border-b" label="Assignee" :value="checksheet.assignee.name"></detail-section>
		<detail-section class="border-b" label="Author" :value="checksheet.author.name"></detail-section>
		
		<detail-section class="border-b" label="Description" :value="checksheet.description"></detail-section>

		<template #secondary-view>
			<div class="mt-8">
				<h1 class="font-bold text-xl">Task Items</h1>
				<datatable :data="{data: checksheet.checksheetItems}">
					<template #default="{rows}">
						<table v-if="rows.length">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Type</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(row, index) in rows" :key="index">
									<td>{{index + 1}}</td>
									<td>{{row.title}}</td>
									<td>{{row.note_required ? 'Required' : 'Not required'}}</td>
								</tr>
							</tbody>
						</table>
					</template>
					<template #nodata>No Items Found</template>
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
	name: "checksheet-details",
	props: {
		checksheet: Object,
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
