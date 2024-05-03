<template>
<index-view title="Leaves">
	<datatable :data="leaves" searchRoute="leaves.index" class="" :filters="filters">
		<!-- Left Header -->
		<template #left-header>
			<search-input v-model="filters.search"></search-input>
		</template>

		<template #right-header>
			<!-- Downloads -->
			<download-dropdown class="mr-4">
				<pdf-download-button :href="route('leaves.pdf', searchQuery)"></pdf-download-button>
				<excel-download-button :href="route('leaves.excel', searchQuery)"></excel-download-button>
			</download-dropdown>

			<!-- Filters -->
			<filter-dropdown v-model="filters" @reset="reset">
				<slot name="filter">
					<div class="filter">
						<jet-label class="mb-2 px-2 font-semibold" for="userId" value="User" />
						<select-list id="userId" track="id" v-model="filters.userId" class="w-full rounded-md" :options="users" />
					</div>
					<div class="filter">
						<jet-label class="mb-2 px-2 font-semibold" for="type" value="Type" />
						<select-list id="type" track="value" v-model="filters.type" class="w-full rounded-md" :options="leaveTypes" />
					</div>
					<div class="filter mb-2">
						<jet-label class="mb-1 px-2 font-semibold" for="startDate" value="Date" />
						<jet-input type="date" id="startDate" v-model="filters.startDate" class="w-full rounded-md" />
					</div>
					
					<!-- <div class="filter">
						<jet-label class="mb-2 px-2 font-semibold" for="status" value="Status" />
						<select-list id="status" track="id" v-model="filters.status" class="w-full rounded-md" :options="statusOptions" />
					</div> -->
				</slot>
			</filter-dropdown>
			<!-- Reset Button -->
			<reset-button class="px-6 py-3 ml-4" title="Reset filter" @click="resetFilters">
				<i class="ti-reload"></i>
			</reset-button>
			<!-- Add New-->
			<button-link class="px-6 py-3 ml-4" :href="route('leaves.create')" v-if="$page.props.can.createLeaves">
				<span class="mr-2">+ Add</span>
				<span class="hidden md:inline">leaves</span>
			</button-link>
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
						<th>User</th>
						<th>Type</th>
						<th>Date</th>
						<!-- <th>Status</th> -->
						<!-- <th>Approver</th> -->
						<th>Action</th>
					</tr>

				</thead>
				<tbody class="h-full overflow-auto">
					<tr v-for="(row, index) in rows" :key="index">
						<td>{{ row.title }}</td>
						<td>{{ row.description }}</td>
						<td>{{ row.user?.name }}</td>
						<td>{{ row.type }}</td>
						<td>{{ row.startDateFormatted }}</td>
						<!-- <td>{{ row.approver?.name }}</td> -->
						<!-- <td>{{ row.status }}</td> -->
						<td class="actions">
							<div class="flex items-center gap-2 h-full">
								<Link class="btn btn-success" title="Details" :href="route('leaves.show', row.id)">
									<detail-icon></detail-icon>
								</Link>

								<Link class="btn btn-purple mr-2" title="Edit" :href="route('leaves.edit', row.id)" v-if="$page.props.can.updateCheckSheets">
									<i class="ti-pencil-alt"></i>
								</Link>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</template>
		<template #nodata>No leaves Found</template>
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
import JetInput from "@/Components/Input.vue";
import SelectList from "@/Components/Select.vue";
import FilterDropdown from "@/Components/FilterDropdown.vue";
import DownloadDropdown from "@/Components/DownloadDropdown.vue";
import ExcelDownloadButton from "@/Components/ExcelDownloadButton.vue";
import PdfDownloadButton from "@/Components/PdfDownloadButton.vue";
export default {
	name: "leaves",

	props: {
		users: Array,
		query: Object,
		leaves: Object,
        leaveTypes: Array,
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
		JetInput,
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
                type: this.query.type,
                assignee: this.query.assignee,
            },
            breadcrumb: [
                { label: "Home", route: this.route("dashboard") },
                { label: "Leaves", route: null },
            ],
        };
    },

    methods: {
		resetFilters() {Object.assign(this.filters, {search: null, type: null, assignee: null})},
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
                            this.route("leaves.update-status", id)
                        );
                    }
                });
        },
    },
};
</script>
