<template>

	<index-view title="Categories" :breadcrumb="breadcrumb">
		<datatable :data="categories" searchRoute="categories.index" :filters="filters">
			<!-- Left Header -->
			<template #left-header>
				<search-input v-model="filters.search"></search-input>
			</template>

			<template #right-header>
				<!-- Fiter -->
				<filter-dropdown v-model="filters" @reset="reset">
					<slot name="filter"></slot>
				</filter-dropdown>

				<!-- Admin -->
				<button-link class="px-6 py-3 ml-4" :href="route('categories.create')" v-if="$page.props.can.createCategories">
					<span class="mr-2">+ Add</span>
					<span class="hidden md:inline">Category</span>
				</button-link>
			</template>

			<!--Table Rows -->
			<template #default="{rows}">
				<table v-if="rows.length">
					<thead>
						<tr>
							<th>Id</th>
							<th>Image</th>
							<th>Name</th>
							<th>Description</th>
							<th>Action</th>
						</tr>

					</thead>
					<tbody>
						<tr v-for="(row, index) in rows" :key="index">
							<td>{{row.id}}</td>
							<td>
								<div class="w-10 h-10">
									<img :src="row.primaryMediaUrl" class="w-100 object-cover">
								</div>
							</td>
							<td>{{row.name}}</td>
							<td>
								{{row.description}}
							</td>
							<td class="flex">
								<div>
									<Link class="btn btn-purple mr-2" title="Edit" :href="route('categories.edit', row.id)" v-if="$page.props.can.updateCategories">
										<i class="ti-pencil-alt"></i>
									</Link>

									<jet-danger-button title="Delete" @click="deleteResource(route('categories.destroy', row.id))" v-if="$page.props.can.deleteCategories">
										<i class="ti-trash"></i>
									</jet-danger-button>

								</div>
							</td>

						</tr>

					</tbody>
				</table>
			</template>
			<template #nodata>No users Found</template>

		</datatable>
	</index-view>

</template>

<script>
import IndexView from "@/Views/IndexView.vue";
import { Link } from "@inertiajs/inertia-vue3";
import ButtonLink from "@/Components/ButtonLink.vue";
import JetDangerButton from "@/Components/DangerButton.vue";
import Datatable from "@/Components/Datatable.vue";
import SearchInput from "@/Components/SearchInput.vue";
import FilterDropdown from "@/Components/FilterDropdown.vue";
import DetailIcon from "@/Icons/DetailIcon.vue";
export default {
	name: "categories",

	props: {
		categories: Object,
		query: Object,
	},

	components: {
		IndexView,
		Link,
		ButtonLink,
		JetDangerButton,
		Datatable,
		SearchInput,
		FilterDropdown,
		DetailIcon,
	},

	data() {
		return {
			filters: {
				search: this.query.search,
			},
			breadcrumb: [
				{ label: "Home", route: this.route("dashboard") },
				{ label: "Categories", route: null },
			],
		};
	},
};
</script>
