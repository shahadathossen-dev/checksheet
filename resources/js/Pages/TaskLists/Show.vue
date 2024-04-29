<template>
	<detail-view title="Check Sheet Details">

		<!-- Title -->
		<detail-section class="border-b" label="Title" :value="checksheet.title"></detail-section>
		<detail-section class="border-b" label="Description" :value="checksheet.description"></detail-section>
		<!-- Country -->
		<detail-section class="border-b" label="Assignee" :value="checksheet.assignee.name"></detail-section>
		<detail-section class="border-b" label="Author" :value="checksheet.author.name"></detail-section>
		<!-- Date -->
		<detail-section class="border-b" label="Due Date" :value="checksheet.dueDateFormatted"></detail-section>
		<detail-section class="border-b" label="Status" :value="checksheet.status"></detail-section>


		<!-- <template #secondary-view>
			<div class="mt-8">
				<h1 class="font-bold text-xl">Subscription list</h1>
				<datatable :data="subscriptions">
					<template #default="{rows}">
						<table v-if="rows.length">
							<thead>
								<tr>
									<th>Id</th>
									<th>Date</th>
									<th>Package Name</th>
									<th>Category</th>
									<th>Price</th>
									<th>Type</th>
									<th>Payment Method</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(row, index) in rows" :key="index">
									<td>{{row.id}}</td>
									<td>{{row.dateFormatted}}</td>
									<td>{{row.package.name}}</td>
                                    <td>{{row.package.category.name}}</td>
									<td>${{row.package.price}}</td>
									<td>{{row.package.type}}</td>
									<td>{{row.paymentMethod}}</td>
								</tr>
							</tbody>
						</table>
					</template>
					<template #nodata>No Subscriptions Found</template>
				</datatable>
			</div>
		</template> -->

	</detail-view>

</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import DetailView from "@/Views/DetailView.vue";
import DetailSection from "@/Components/DetailSection.vue";
import { Link } from "@inertiajs/inertia-vue3";
import Datatable from "@/Components/Datatable.vue";

export default {
	name: "order-details",
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
