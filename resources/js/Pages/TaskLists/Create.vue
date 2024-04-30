<template>
	<form-view @submitted="update('tasklists.create', checksheet.id)" title="Create Task List" :breadcrumb="breadcrumb">
		<template #form v-if="checksheet">
			<!-- Title -->
			<detail-section class="border-b" label="Title" :value="checksheet.title"></detail-section>
			<!-- Date -->
			<detail-section class="border-b" label="Type" :value="checksheet.type"></detail-section>
			<detail-section class="border-b" label="Due By" :value="checksheet.dueBy"></detail-section>
			<!-- Author -->
			<detail-section class="border-b" label="Assignee" :value="checksheet.assignee.name"></detail-section>
			<detail-section class="border-b" label="Author" :value="checksheet.author?.name"></detail-section>
			
			<detail-section class="border-b" label="Description" :value="checksheet.description"></detail-section>

			<!-- Attributes -->
            <form-group class="border-b md:flex-col">
				<jet-label class="w-full" value="Check Sheet Items" />
                    <div class="w-full flex items-center gap-5 block mb-2" v-for="(attribute, index) in checksheet.checksheetItems" :key="index">
						<div class="task-item flex-grow">
							<jet-label class="w-full" :for="`Note-${index}`" :value="attribute.title" />

							<jet-text-input v-model="attribute.note" :id="`Note-${index}`" type="text" class="mt-1 block w-full" placeholder="Note" :required="attribute.required" />
						</div>
						<jet-label class="" :for="`Done-${index}`">
                        	<jet-check-box v-model="attribute.done" :id="`Done-${index}`" :checked="!!attribute.done" />
							<span class="px-2 align-middle">Done</span>
						</jet-label>
                    </div>
                <jet-input-error :message="form.errors.check_sheet_items" class="mt-2" />
			</form-group>
		</template>

		<template #actions>
			<Link :href="route('checksheets.index')" class="xs:mr-4">Cancel</Link>
			<jet-button @click.prevent="update('checksheets.update', checksheet.id, true)" class="px-6 xs:mr-2 my-2 xs:my-0" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Save & Continue</jet-button>
			<jet-button class="px-6" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Save</jet-button>
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
				type: 'daily'
			},
			checksheet: null,
			form: this.$inertia.form({
				checksheet_id: this.checksheet?.id,
                items: this.checksheet?.checksheetItems.map(item => ({done: null, ...item})),
			}),
		};
	},
	beforeCreate() {
		// console.log(this.filters);
		const self = this;
		try {
			axios.get(route('checksheets.details', 'daily'))
				.then(({data}) => this.checksheet = data);
			
		} catch (error) {
			console.log(error);
			
		}
	},
	methods: {
        // addAttribute: function() {
        //     this.form.check_sheet_items.push({title: '', required: null})
        // },
        // removeAttribute: function(position) {
        //     this.form.check_sheet_items.splice(position, 1)
        // }
    }
};
</script>
