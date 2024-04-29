<template>
<index-view title="Check Sheets">
	<datatable :data="checksheets" searchRoute="checksheets.index" class="" :filters="filters">
		<!-- Left Header -->
		<template #left-header>
			<search-input v-model="filters.search"></search-input>
		</template>

		<template #right-header>
			<!-- Downloads -->
			<download-dropdown class="mr-4">
				<pdf-download-button :href="route('checksheets.pdf', searchQuery)"></pdf-download-button>
				<excel-download-button :href="route('checksheets.excel', searchQuery)"></excel-download-button>
			</download-dropdown>

			<!-- Filters -->
			<filter-dropdown v-model="filters" @reset="reset">
				<slot name="filter">
					<label class="mb-2 px-2 font-semibold" for="status" value="Status">Status</label>
					<select-list id="status" track="value" v-model="filters.status" class="w-full rounded-md" :options="statusOptions" />
				</slot>
			</filter-dropdown>
		</template>

		<!--Table Rows -->
		<template #default="{rows}">
			<table v-if="rows.length">
				<!-- <colgroup class="bg-white hidden">
					<col class="bg-white">
					<col class="bg-white">
					<col class="bg-white">
					<col class="bg-white">
					<col class="bg-white">
					<col class="bg-white">
				</colgroup> -->
				<thead class="sticky top-0 z-10 shadow">
					<tr>
						<th>Title</th>
						<th>Description</th>
						<th>Due By</th>
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
						<td>{{ row.dueBy }}</td>
						<td>{{ row.assignee?.name }}</td>
						<td>{{ row.author?.name }}</td>
						<td>{{ row.status }}</td>
						<td class="actions">
							<div class="flex items-center gap-2 h-full">
								<Link class="btn btn-success" title="Details" :href="route('checksheets.show', row.id)">
									<detail-icon></detail-icon>
								</Link>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</template>
		<template #nodata>No checksheets Found</template>
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
import DetailIcon from "@/Icons/DetailIcon.vue";
import Label from "@/Components/Label.vue";
import SelectList from "@/Components/Select.vue";
import FilterDropdown from "@/Components/FilterDropdown.vue";
import DownloadDropdown from "@/Components/DownloadDropdown.vue";
import ExcelDownloadButton from "@/Components/ExcelDownloadButton.vue";
import PdfDownloadButton from "@/Components/PdfDownloadButton.vue";
export default {
	name: "checksheets",

	props: {
		query: Object,
		checksheets: Object,
        statusOptions: Array,
	},

	components: {
		IndexView,
		Link,
		ButtonLink,
        SelectList,
        Label,
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
            },
            breadcrumb: [
                { label: "Home", route: this.route("dashboard") },
                { label: "Check Sheets", route: null },
            ],
        };
    },

    methods: {
        toggleStatus(id) {
            this.$swal
                .fire({
                    title: "Are you sure?",
                    text: "",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#EF4444",
                    confirmButtonText: "Yes, do it!",
                    cancelButtonText: "Cancel",
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        this.$inertia.post(
                            this.route("checksheets.update-status", id)
                        );
                    }
                });
        },
    },
};
</script>
