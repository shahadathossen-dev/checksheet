<template>

	<index-view title="Delegates">
		<datatable :data="delegates" searchRoute="delegates.index" :filters="filters">
			<!-- Left Header -->
			<template #left-header>
				<search-input v-model="filters.search"></search-input>
			</template>

			<template #right-header>
				<!-- Downloads -->
                <download-dropdown class="mr-4">
                    <pdf-download-button :href="route('delegates.pdf', searchQuery)"></pdf-download-button>
                    <excel-download-button :href="route('delegates.excel', searchQuery)"></excel-download-button>
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
					<thead>
						<tr>
							<th>Id</th>
							<th>Date</th>
							<th>Name</th>
							<th>Email</th>
							<th>Country</th>
							<th>Action</th>
						</tr>

					</thead>
					<tbody>
						<tr v-for="(row, index) in rows" :key="index">
							<td>{{ row.id }}</td>
							<td>{{ row.createdAtFormatted}}</td>
							<td>{{ row.name }}</td>
							<td>{{ row.email }}</td>
							<td>{{ row.country.code }}</td>

							<td class="flex">

								<div>
									<Link class="btn btn-success mr-2" title="Details" :href="route('delegates.show', row.id)">
									<detail-icon></detail-icon>
									</Link>
								</div>

							</td>
						</tr>
					</tbody>
				</table>
			</template>
			<template #nodata>No delegates Found</template>

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
	name: "delegates",

	props: {
		query: Object,
		delegates: Object,
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
                { label: "Delegates", route: null },
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
                            this.route("delegates.update-status", id)
                        );
                    }
                });
        },
    },
};
</script>
