<template>
	<form-view @submitted="save('additional-tasks.store')" title="Create Additional Task" :breadcrumb="breadcrumb">
		<template #form>
			<form-group class="border-b gap-8">
				<!-- User -->
				<div class="col w-full">
					<jet-label class="md:w-1/4" for="userId" value="User" required />
					<div class="w-full mt-1">
						<jet-select v-model="form.userId" id="userId" class="w-full" :options="users" autocomplete="userId" required />
						<jet-input-error :message="form.errors.userId" class="mt-2" />
					</div>
				</div>
				<!-- Due By -->
				<div class="col w-full">
					<jet-label class="md:w-1/4" for="dueDate" value="Due Date" required />
					<div class="w-full mt-1">
						<jet-input v-model="form.dueDate" id="dueDate" type="date" max="30" class="w-full" autocomplete="dueDate" required />
						<jet-input-error :message="form.errors.dueDate" class="mt-2" />
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
			<Link :href="route('additional-tasks.index')" class="xs:mr-4">Cancel</Link>
			<jet-button @click.prevent="save('additional-tasks.store', true)" class="px-6 xs:mr-2 my-2 xs:my-0" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Save & Continue</jet-button>
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
	title: "create-additional-task",
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
				{ label: "Additional Tasks", route: this.route("additional-tasks.index") },
				{ label: "Create", route: null },
			],

			form: this.$inertia.form({
				title: null,
				description: null,
				dueDate: null,
				userId: null,
			}),

		};
	},
	methods: {
		//
	}
};
</script>
