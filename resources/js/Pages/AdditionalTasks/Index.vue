<template>
<index-view title="Additional Tasks">
	<datatable :data="additionalTasks" searchRoute="additional-tasks.index" class="" :filters="filters">
		<!-- Left Header -->
		<template #left-header>
			<search-input v-model="filters.search"></search-input>
		</template>

		<template #right-header>
			<!-- Downloads -->
			<!-- <download-dropdown class="mr-4">
				<pdf-download-button :href="route('additional-tasks.pdf', searchQuery)"></pdf-download-button>
				<excel-download-button :href="route('additional-tasks.excel', searchQuery)"></excel-download-button>
			</download-dropdown> -->

			<!-- Filters -->
			<filter-dropdown v-model="filters" @reset="reset">
				<slot name="filter">
					<div class="filter">
						<jet-label class="mb-2 px-2 font-semibold" for="status" value="Status" />
						<select-list id="status" track="value" v-model="filters.status" class="w-full rounded-md" :options="statusOptions" />
					</div>
					<div class="filter">
						<jet-label class="mb-2 px-2 font-semibold" for="assignee" value="Assignee" />
						<select-list id="assignee" track="id" v-model="filters.assignee" class="w-full rounded-md" :options="users" />
					</div>
				</slot>
			</filter-dropdown>
			<!-- Reset Button -->
			<reset-button class="px-6 py-3 ml-4" title="Reset filter" @click="resetFilters">
				<i class="ti-reload"></i>
			</reset-button>
			<!-- Add New-->
			<button-link class="px-6 py-3 ml-4" :href="route('additional-tasks.create')" v-if="$page.props.can.createAdditionalTasks">
				<span class="mr-2">+ Add</span>
				<span class="hidden md:inline">Additional Task</span>
			</button-link>
		</template>

		<!--Table Rows -->
		<template #default="{rows}">
			<table v-if="rows.length">
				<thead class="sticky top-0 z-10 shadow">
					<tr>
						<th>Title</th>
						<th>Description</th>
						<th>Due Date</th>
						<th>Submit Date</th>
						<th>Assignee</th>
						<th>Author</th>
						<th>Status</th>
						<th>Action</th>
					</tr>

				</thead>
				<tbody class="h-full overflow-auto">
					<tr v-for="(row, index) in rows" :key="index">
						<td>{{ row.title }}</td>
						<td>{{ row.description }}</td>
						<td>{{ row.dueDate }}</td>
						<td>{{ row.submitDate }}</td>
						<td>{{ row.assignee?.name }}</td>
						<td>{{ row.author?.name }}</td>
						<td class="capitalize">{{ row.status }}</td>
						<td class="actions">
							<div class="flex items-center gap-2 h-full">
								<Link class="btn btn-success" title="Details" :href="route('additional-tasks.show', row.id)">
									<detail-icon></detail-icon>
								</Link>

								<Link class="btn btn-purple mr-2" title="Edit" :href="route('additional-tasks.edit', row.id)" v-if="$page.props.can.updateAdditionalTasks">
									<i class="ti-pencil-alt"></i>
								</Link>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</template>
		<template #nodata>No Additional Tasks Found</template>
	</datatable>
</index-view>
</template>

<script>
import IndexView from "@/Views/IndexView.vue";
import { Link } from "@inertiajs/inertia-vue3";
import ButtonLink from "@/Components/ButtonLink.vue";
import ResetButton from "@/Components/ResetButton.vue";
import JetDangerButton from "@/Components/DangerButton.vue";
import Datatable from "@/Components/Datatable.vue";
import SearchInput from "@/Components/SearchInput.vue";
import DetailIcon from "@/Icons/DetailIcon.vue";
import JetLabel from "@/Components/Label.vue";
import SelectList from "@/Components/Select.vue";
import FilterDropdown from "@/Components/FilterDropdown.vue";
import DownloadDropdown from "@/Components/DownloadDropdown.vue";
import ExcelDownloadButton from "@/Components/ExcelDownloadButton.vue";
import PdfDownloadButton from "@/Components/PdfDownloadButton.vue";
export default {
	name: "additional-tasks",

	props: {
		users: Array,
		query: Object,
		additionalTasks: Object,
        statusOptions: Array,
	},

	components: {
		IndexView,
		Link,
		ButtonLink,
		ResetButton,
        SelectList,
        JetLabel,
		JetDangerButton,
		Datatable,
		SearchInput,
		FilterDropdown,
		DetailIcon,
        DownloadDropdown,
        ExcelDownloadButton,
        PdfDownloadButton,
	},

	data() {
        return {
            filters: {
                search: this.query.search,
                status: this.query.status,
                assignee: this.query.assignee,
            },
            breadcrumb: [
                { label: "Home", route: this.route("dashboard") },
                { label: "Additional Tasks", route: null },
            ],
        };
    },

    methods: {
		resetFilters() {Object.assign(this.filters, {search: null, status: null, assignee: null})},
    },
};
</script>
