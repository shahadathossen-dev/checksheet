<template>
	<form-view @submitted="form.model == 'checksheet' ? save('tasklists.store') : update('tasklists.update', form.id)" :title="form.model == 'checksheet' ? 'Create' : 'Update' + ' Checksheet'" :breadcrumb="breadcrumb">
		<template #form>
			<form-group class="border-b gap-8">
				<!-- Due Date -->
				<div class="col w-1/2" v-if="hasRoles(['Super Admin', 'Admin'])">
					<jet-label class="mb-1" for="dueDate" value="Due Date" required />
					<div class="w-full">
						<jet-input type="date" v-model="form.dueDate" @input="getTaskListDetails" id="dueDate" class="w-full" required />
						<jet-input-error :message="form.errors.dueDate" class="mt-2" />
					</div>
				</div>

				<!-- User -->
				<div class="col w-1/2" v-if="hasRoles(['Super Admin', 'Admin'])">
					<jet-label class="mb-1" for="userId" value="User" required />
					<div class="w-full">
						<jet-select v-model="form.userId" @change="getTaskListDetails" id="userId" class="w-full" :options="users" autocomplete="user_id" required />
						<jet-input-error :message="form.errors.userId" class="mt-2" />
					</div>
				</div>

				<!-- Type -->
				<div class="col w-1/2" :class="{flex: !hasRoles(['Super Admin', 'Admin'])}">
					<jet-label class="mb-1" for="type" value="Type" required />
					<div class="w-full">
						<jet-select v-model="form.type" @change="getTaskListDetails" id="type" class="w-full" track="value" :options="checksheetTypes" autocomplete="type" required />
						<jet-input-error :message="form.errors.type" class="mt-2" />
					</div>
				</div>
			</form-group>
			<!-- Title -->
			<detail-section class="border-b" label="Title" :value="form.title"></detail-section>
			<!-- Date -->
			<detail-section class="border-b capitalize" label="Type" :value="form.type"></detail-section>
			<detail-section class="border-b" label="Due Date" :value="form.dueDateFormatted"></detail-section>
			<!-- <detail-section class="border-b" label="Description" :value="form.description"></detail-section> -->

			<!-- Attributes -->
            <form-group class="border-b md:flex-col" v-if="form.items">
				<jet-label class="w-full" value="Check Sheet Items" />
				<div class="w-full flex items-center gap-5 block my-2" v-for="(attribute, index) in form.items" :key="index">
					<div class="task-item flex-grow">
						<jet-label class="w-full" :for="`Note-${index}`" :value="attribute.title" :required="!!attribute.noteRequired" />

						<jet-text-input v-model="attribute.note" :id="`Note-${index}`" type="text" class="mt-1 block w-full" placeholder="Note" :required="form.model != 'checksheet' && attribute.noteRequired == 1" />
					</div>
					<jet-label class="" :for="`Done-${index}`">
						<jet-check-box v-model="attribute.done" :id="`Done-${index}`" :checked="attribute.done == 1" />
						<span class="px-2 align-middle">Done</span>
					</jet-label>
				</div>
                <jet-input-error :message="form.errors.check_sheet_items" class="mt-2" />
			</form-group>
		</template>

		<template #actions>
			<Link :href="route('tasklists.index')" class="xs:mr-4">Cancel</Link>
			<jet-button @click.prevent="form.model == 'checksheet' ? save('tasklists.store', true) : update('tasklists.update', form.id, true)" class="px-6 xs:mr-2 my-2 xs:my-0" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Save & Continue</jet-button>
			<jet-button class="px-6" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">{{form.model == 'checksheet' ? 'Save' : 'Update'}}</jet-button>
		</template>
	</form-view>
</template>

<script>
import FormView from "@/Views/FormView.vue";
import { Link } from "@inertiajs/inertia-vue3";
import JetInput from "@/Components/Input.vue";
import JetSelect from "@/Components/Select.vue";
import JetInputError from "@/Components/InputError.vue";
import JetLabel from "@/Components/Label.vue";
import JetTextInput from "@/Components/TextInput.vue";
import JetButton from "@/Components/Button.vue";
import FormGroup from "@/Components/FormGroup.vue";
import JetTextArea from "@/Components/TextArea.vue";
import JetDangerButton from "@/Components/DangerButton.vue";
import JetCheckBox from "@/Components/Checkbox.vue";
import DetailSection from "@/Components/DetailSection.vue";

export default {
	title: "edit-checksheet",
	props: {
		users: Array,
		checksheetTypes: Array,
	},
	preserveState: false,
	components: {
		Link,
		FormView,
		JetInput,
		JetSelect,
		JetInputError,
		JetLabel,
		JetTextInput,
		FormGroup,
		JetButton,
		JetTextArea,
		JetDangerButton,
		JetCheckBox,
		DetailSection
	},
	data() {
		return {
			breadcrumb: [
				{ label: "Home", route: this.route("dashboard") },
				{ label: "Check Sheets", route: this.route("checksheets.index") },
				{ label: "Create", route: null },
			],
			filters: {
			},
			// checksheet: null,
			form: this.$inertia.form({
				checksheetId: null,
				type: 'daily',
				userId: null,
				title: null,
				model: null,
				dueDate: (new Date()).toISOString().slice(0,10),
                items: [],
			}),
		};
	},
	beforeMount() {
		this.getTaskListDetails();
	},
	methods: {
		getTaskListDetails() {
			if(!this.form.type || !this.form.dueDate) return false;

			try {
				axios.get(route('tasklists.details', this.form.type), {params: {userId: this.form.userId, dueDate: this.form.dueDate}})
					.then(({data}) => {
						// this.checksheet = data;
						Object.assign(this.form, data)
					});
				
			} catch (error) {
				console.log(error);
				
			}
		},
    }
};
</script>
