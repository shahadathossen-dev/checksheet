<template>
	<form-view @submitted="update('checksheets.update', checksheet.id)" title="Edit User" :breadcrumb="breadcrumb">
		<template #form>
			<!-- Title -->
			<form-group class="border-b">
				<jet-label class="md:w-1/4 mt-2" for="title" value="Title" required />
				<div class="w-full mt-1">
					<jet-input v-model="form.title" id="title" type="text" class="w-full" autocomplete="title" />
					<jet-input-error :message="form.errors.title" class="mt-2" />
				</div>
			</form-group>

			<!-- Description -->
			<form-group class="border-b">
				<jet-label class="md:w-1/4 mt-2" for="description" value="Description" required />
				<div class="w-full mt-1">
					<jet-input v-model="form.description" id="description" type="text" class="w-full" autocomplete="description" />
					<jet-input-error :message="form.errors.description" class="mt-2" />
				</div>
			</form-group>

			<!-- Due Date -->
			<form-group class="border-b">
				<jet-label class="md:w-1/4 mt-2" for="due_date" value="Due Date" required />
				<div class="w-full mt-1">
					<jet-input v-model="form.dueDate" id="due_date" type="date" class="w-full" autocomplete="due_date" />
					<jet-input-error :message="form.errors.dueDate" class="mt-2" />
				</div>
			</form-group>

			<!-- Assignee -->
			<form-group class="border-b">
				<jet-label class="md:w-1/4 mt-2" for="userId" value="Assignee" required />
				<div class="w-full mt-1">
					<jet-select v-model="form.userId" id="userId" class="w-full" :options="users" autocomplete="userId" />
					<jet-input-error :message="form.errors.userId" class="mt-2" />
				</div>
			</form-group>

			<!-- Status -->
			<form-group class="border-b">
				<jet-label class="md:w-1/4 mt-2" for="status" value="Status" required />
				<div class="w-full mt-1">
					<jet-select v-model="form.status" id="status" class="w-full" :options="statusOptions" autocomplete="status" />
					<jet-input-error :message="form.errors.status" class="mt-2" />
				</div>
			</form-group>

		</template>

		<template #actions>
			<Link :href="route('checksheets.index')" class="xs:mr-4">Cancel</Link>
			<jet-button @click.prevent="update('checksheets.update', checksheet.id, true)" class="px-6 xs:mr-2 my-2 xs:my-0" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Update & Continue</jet-button>
			<jet-button class="px-6" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Update</jet-button>
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

export default {
	title: "create-checksheet",
	props: {
		checksheet: Object,
		statusOptions: Array,
		users: Array,
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
				title: this.checksheet.title,
				description: this.checksheet.description,
				dueDate: this.checksheet.dueDate,
				userId: this.checksheet.userId,
				status: this.checksheet.status,
			}),

			rowItems: [],
		};
	},
};
</script>
