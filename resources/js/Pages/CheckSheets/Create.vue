<template>
	<form-view @submitted="save('checksheets.store')" title="Create Check Sheet" :breadcrumb="breadcrumb">
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
				<!-- Type -->
				<div class="col w-full">
					<jet-label class="md:w-1/4" for="user_id" value="Assignee" required />
					<div class="w-full mt-1">
						<jet-select v-model="form.user_id" id="user_id" class="w-full" :options="users" autocomplete="user_id" required />
						<jet-input-error :message="form.errors.user_id" class="mt-2" />
					</div>
				</div>
				<!-- Due By -->
				<div class="col w-full">
					<jet-label class="md:w-1/4" for="due_by" value="Due By" />
					<div class="w-full mt-1">
						<jet-input v-model="form.due_by" id="due_by" type="number" max="30" class="w-full" autocomplete="due_by" />
						<jet-input-error :message="form.errors.due_by" class="mt-2" />
					</div>
				</div>
			</form-group>

			<!-- Description -->
			<form-group class="border-b md:flex-col">
				<jet-label class="md:w-1/4" for="description" value="Description" />
				<div class="w-full mt-1">
					<jet-text-area v-model="form.description" id="description" type="text" class="w-full" autocomplete="description" />
					<jet-input-error :message="form.errors.description" class="mt-2" />
				</div>
			</form-group>

			<!-- Attributes -->
            <form-group class="border-b md:flex-col">
				<jet-label class="md:w-1/4" for="Note-0" value="Check Sheet Items" />
                <div class="w-full">
                    <div class="w-full flex items-center gap-5 block my-2" v-for="(attribute, index) in form.check_sheet_items" :key="index">
                        <jet-text-input v-model="attribute.title" :id="`Note-${index}`" type="text" class="mt-1 block w-full" autocomplete="priceWithVat" placeholder="Title" required />
						<jet-label class="md:w-1/4" :for="`Required-${index}`">
                        	<jet-check-box v-model="attribute.required" :id="`Required-${index}`" class="" autocomplete="priceWithVat" />
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
                <jet-input-error :message="form.errors.check_sheet_items" class="mt-2" />
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
				due_by: null,
				user_id: null,
				type: null,
                check_sheet_items: [],
			}),

		};
	},
	methods: {
        addAttribute: function() {
            this.form.check_sheet_items.push({title: '', required: null})
        },
        removeAttribute: function(position) {
            this.form.check_sheet_items.splice(position, 1)
        }
    }
};
</script>
