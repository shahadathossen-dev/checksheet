<template>
	<form-view @submitted="update('checksheets.update', checksheet.id)" title="Update Template" :breadcrumb="breadcrumb">
		<template #form>
			<form-group class="border-b gap-8">
				<!-- Title -->
				<div class="col w-full">
					<jet-label class="md:w-1/4" for="title" value="Title" required />
					<div class="w-full mt-1">
						<jet-input v-model="form.title" id="title" type="text" class="w-full" autocomplete="title" required />
						<jet-input-error :message="form.errors.title" class="mt-2" />
					</div>
				</div>
				<!-- Type -->
				<div class="col w-full">
					<jet-label class="md:w-1/4" for="type" value="Type" required />
					<div class="w-full mt-1">
						<jet-select v-model="form.type" id="type" class="w-full" track="value" :options="checksheetTypes" autocomplete="type" required />
						<jet-input-error :message="form.errors.type" class="mt-2" />
					</div>
				</div>
			</form-group>

			<form-group class="border-b gap-8">
				<!-- Assignee -->
				<div class="col w-full">
					<jet-label class="md:w-1/4" for="userId" value="Assignee" required />
					<div class="w-full mt-1">
						<jet-select v-model="form.userId" id="userId" class="w-full" :options="users" autocomplete="userId" required />
						<jet-input-error :message="form.errors.userId" class="mt-2" />
					</div>
				</div>
				<!-- Due By -->
				<div class="col w-full">
					<jet-label class="md:w-1/4" for="dueBy" value="Due By" />
					<div class="w-full mt-1">
						<jet-input :max="maxDueBy" v-model="form.dueBy" id="dueBy" type="number" max="30" class="w-full" autocomplete="dueBy" :disabled="form.type == 'daily'" />
						<div class="flex justify-between">
							<jet-input-error :message="form.errors.dueBy" class="mt-2" />
							<div class="helping-text text-gray-400">{{ helpeingTextDueBy }}</div>
						</div>
					</div>
				</div>
			</form-group>

			<!-- Description -->
			<!-- <form-group class="border-b md:flex-col">
				<jet-label class="md:w-1/4" for="description" value="Description" />
				<div class="w-full mt-1">
					<jet-text-area v-model="form.description" id="description" type="text" class="w-full" autocomplete="description" />
					<jet-input-error :message="form.errors.description" class="mt-2" />
				</div>
			</form-group> -->

			<!-- Attributes -->
            <form-group class="border-b md:flex-col">
				<jet-label class="md:w-1/4" for="Note-0" value="Check Sheet Items" />
                <div class="w-full">
                    <div class="w-full flex items-center gap-5 block my-2" v-for="(attribute, index) in form.checksheetItems" :key="index">
                        <jet-text-input v-model="attribute.title" :id="`Note-${index}`" type="text" class="mt-1 block w-full" placeholder="Title" required />
						<jet-label class="md:w-1/4" :for="`Required-${index}`">
                        	<jet-check-box v-model="attribute.noteRequired" :id="`Required-${index}`" :checked="attribute.noteRequired == 1" />
							<span class="px-2 align-middle">Note Required</span>
						</jet-label>
                        <jet-danger-button type="text" title="Remove Task" @click.prevent="removeAttribute(index)">
                            <i class="ti-minus"></i>
                        </jet-danger-button>
                    </div>
					<div class="text-right">
						<jet-button class="w-full" type="text" title="Add Task" @click.prevent="addAttribute">
                            <i class="ti-plus"></i>
                        </jet-button>
					</div>
                </div>
                <jet-input-error :message="form.errors.checksheetItems" class="mt-2" />
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

export default {
	title: "edit-checksheet",
	props: {
		checksheet: Object,
		users: Array,
		checksheetTypes: Array,
	},

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
		JetCheckBox
	},
	data() {
		return {
			breadcrumb: [
				{ label: "Home", route: this.route("dashboard") },
				{ label: "Check Sheets", route: this.route("checksheets.index") },
				{
					label: this.checksheet.title,
					route: this.route("checksheets.show", this.checksheet.id),
				},
				{ label: "Edit", route: null },
			],

			form: this.$inertia.form({
				title: null,
				description: null,
				dueBy: null,
				type: null,
				userId: null,
                checksheetItems: [],
			}),
		};
	},
	computed: {
		helpeingTextDueBy () {
			return this.form.type == 'monthly' ? '1 - 30 (Keep it empty to indicate end of every month)' : (this.form.type == 'weekly' ? '0-6 (Weekdays every week)' : 'Not required (The current date)')
		},

		maxDueBy () {
			return this.form.type == 'monthly' ? 30 : (this.form.type == 'weekly' ? 6 : null)
		},
	},
	beforeMount() {
		Object.assign(this.form, this.checksheet);
	},
	methods: {
        addAttribute: function() {
            this.form.checksheetItems.push({title: '', required: 0})
        },
        removeAttribute: function(position) {
            this.form.checksheetItems.splice(position, 1)
        }
    }
};
</script>
