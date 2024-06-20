<template>
	<form-view @submitted="form.id ? update('checksheets.update', form.id) : save('checksheets.store')" title="Create Template" :breadcrumb="breadcrumb">
		<template #form>
			<form-group class="border-b gap-8">
				<!-- User -->
				<div class="col w-full">
					<jet-label class="md:w-1/4" for="userId" value="User" required />
					<div class="w-full mt-1">
						<jet-select v-model="form.userId" @change="getCheckSheet" id="userId" class="w-full" :options="users" autocomplete="userId" required />
						<jet-input-error :message="form.errors.userId" class="mt-2" />
					</div>
				</div>
				
				<!-- Type -->
				<div class="col w-full">
					<jet-label class="md:w-1/4" for="type" value="Type" required />
					<div class="w-full mt-1">
						<jet-select v-model="form.type" @change="getCheckSheet" id="type" class="w-full" track="value" :options="checksheetTypes" autocomplete="type" required />
						<jet-input-error :message="form.errors.type" class="mt-2" />
					</div>
				</div>
			</form-group>

			<form-group class="border-b gap-8">
				<!-- Title -->
				<div class="col w-full">
					<jet-label class="md:w-1/4" for="title" value="Title" required />
					<div class="w-full mt-1">
						<jet-input v-model="form.title" id="title" type="text" class="w-full" autocomplete="title" required />
						<jet-input-error :message="form.errors.title" class="mt-2" />
					</div>
				</div>
				<!-- Due By -->
				<div class="col w-full">
					<jet-label class="md:w-1/4" for="dueBy" value="Due By" />
					<div class="w-full mt-1">
						<jet-input :max="maxDueBy" v-model="form.dueBy" id="dueBy" type="number" max="30" class="w-full" autocomplete="dueBy" :disabled="form.type == 'daily'" />
						<div class="flex justify-between">
							<jet-input-error :message="form.errors.dueBy" class="mt-2" />
							<dvi class="helping-text text-gray-400">{{ helpeingTextDueBy }}</dvi>
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
                        <jet-text-input v-model="attribute.title" :id="`Note-${index}`" type="text" class="mt-1 block w-full" autocomplete="priceWithVat" placeholder="Title" required />
						<jet-label class="md:w-1/4" :for="`Required-${index}`">
                        	<jet-check-box v-model="attribute.note_required" :id="`Required-${index}`" :checked="attribute.note_required == 1" autocomplete="priceWithVat" />
							<span class="px-2 align-middle">Note Required</span>
						</jet-label>
                        <jet-danger-button title="Remove Task" type="text" @click.prevent="removeAttribute(index)">
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
			<jet-button @click.prevent="save('checksheets.store', true)" class="px-6 xs:mr-2 my-2 xs:my-0" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Save & Continue</jet-button>
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
	title: "create-user",
	props: {
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
				{ label: "Create", route: null },
			],

			form: this.$inertia.form({
				title: null,
				description: null,
				dueBy: null,
				userId: null,
				type: null,
				noteRequired: null,
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
	methods: {
		getCheckSheet() {
			if(!(this.form.userId && this.form.type)) return false;

			try {
				axios.get(route('checksheets.details', this.form.type), {params: {userId: this.form.userId}})
					.then(({data}) => Object.assign(this.form, data));
				
			} catch (error) {
				console.log(error);
				
			}
		},
        addAttribute: function() {
            this.form.checksheetItems.push({title: '', required: 0})
        },
        removeAttribute: function(position) {
            this.form.checksheetItems.splice(position, 1)
        },

    }
};
</script>
