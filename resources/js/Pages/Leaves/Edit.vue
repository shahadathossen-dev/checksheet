<template>
	<form-view @submitted="update('leaves.update', form.id)" title="Create Leave" :breadcrumb="breadcrumb">
		<template #form>
			<form-group class="border-b gap-8" v-if="hasRoles(['Super Admin', 'Admin'])">
				<!-- Type -->
				<div class="col w-full">
					<jet-label class="md:w-1/4" for="type" value="Type" required />
					<div class="w-full mt-1">
						<jet-select v-model="form.type" @change="checkLeaves" id="type" class="w-full" track="value" :options="leaveTypes" autocomplete="type" required disabled />
						<jet-input-error :message="form.errors.type" class="mt-2" />
					</div>
				</div>
				<!-- User -->
				<div class="col w-full" v-if="form.type == 'individual'">
					<jet-label class="md:w-1/4" for="userId" value="User" required />
					<div class="w-full mt-1">
						<jet-select v-model="form.userId" @change="checkLeaves" id="userId" class="w-full" :options="users" autocomplete="userId" required disabled />
						<jet-input-error :message="form.errors.userId" class="mt-2" />
					</div>
				</div>
				
			</form-group>

			<form-group class="border-b gap-8">
				<!-- Start Date -->
				<div class="col w-full">
					<jet-label class="md:w-1/4 mb-1" for="startDate" value="Date" required />
					<div class="w-full">
						<jet-input type="date" v-model="form.startDate" @input="checkLeaves" id="startDate" class="w-full" required />
						<jet-input-error :message="form.errors.startDate" class="mt-2" />
					</div>
				</div>
				
				<!-- End Date -->
				<!-- <div class="col w-1/2">
					<jet-label class="md:w-1/4 mb-1" for="endDate" value="End Date" required />
					<div class="w-full">
						<jet-input type="date" v-model="form.endDate" @input="checkLeaves" id="endDate" class="w-full" required />
						<jet-input-error :message="form.errors.endDate" class="mt-2" />
					</div>
				</div> -->
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
			</form-group>

			<form-group class="border-b gap-8">
				<!-- Description -->
				<div class="col w-full">
					<jet-label class="md:w-1/4" for="description" value="Description" />
					<div class="w-full mt-1">
						<jet-text-area v-model="form.description" id="description" type="text" class="w-full" autocomplete="description" />
						<jet-input-error :message="form.errors.description" class="mt-2" />
					</div>
				</div>
			</form-group>
		</template>

		<template #actions>
			<Link :href="route('leaves.index')" class="xs:mr-4">Cancel</Link>
			<jet-button @click.prevent="save('leaves.store', true)" class="px-6 xs:mr-2 my-2 xs:my-0" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Save & Continue</jet-button>
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
		leave: Object,
		users: Array,
		leaveTypes: Array,
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
				{ label: "Holidays", route: this.route("leaves.index") },
				{ label: "Edit", route: null },
			],

			form: this.$inertia.form({
				type: 'individual',
				userId: null,
				startDate: null,
				endDate: null,
				title: null,
				description: null,
			}),

		};
	},
	watch: {
		'form.startDate': function (val) {
			this.form.endDate = this.form.startDate
			// this.checkLeaves();
		}
	},
	beforeMount() {
		Object.assign(this.form, this.leave)
	},
	methods: {
		checkLeaves() {
			if(!((this.form.type == 'general' || this.form.userId) &&
				this.form.startDate && this.form.endDate)
			) return false;

			try {
				axios.get(route('leaves.details', this.form.type),
					{params: {userId: this.form.userId, startDate: this.form.startDate, endDate: this.form.endDate}}
				)
				// .then(({data}) => Object.assign(this.form, data));
				.then(({data}) => this.form.errors.startDate = 'Requested date overlaps with existing leaves.');
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
