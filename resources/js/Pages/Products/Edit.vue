<template>
	<form-view @submitted="update('products.update', product.id)" title="Edit Product" :breadcrumb="breadcrumb">
		<template #form>
			<!-- Name -->
			<form-group class="border-b">
				<jet-label class="md:w-1/4 mt-2" for="name" value="Name" required />
				<div class="w-full mt-1">
					<jet-input v-model="form.product_name" id="name" type="text" class="w-full" autocomplete="name" />
					<jet-input-error :message="form.errors.product_name" class="mt-2" />
				</div>
			</form-group>

			<!--  Price -->
			<form-group class="border-b">
				<jet-label class="md:w-1/4 mt-2" for="price" value="Price" />
				<div class="w-full">
					<jet-text-input v-model="form.price" id="price" type="number" class="mt-1 block w-full" autocomplete="price" />
					<jet-input-error :message="form.errors.price" class="mt-2" />
				</div>
			</form-group>

            <form-group class="border-b">
				<jet-label class="md:w-1/4 mt-2" for="priceWithVat" value="Price with VAT" />
				<div class="w-full">
					<jet-text-input v-model="form.price_with_vat" id="priceWithVat" type="number" class="mt-1 block w-full" autocomplete="priceWithVat" />
					<jet-input-error :message="form.errors.price_with_vat" class="mt-2" />
				</div>
			</form-group>

            <!-- Attributes -->
            <form-group class="border-b">
				<jet-label class="md:w-1/4 mt-2" for="Attributes" value="Attributes" />
                <div class="w-full">
                    <div class="w-full flex gap-3 block my-2" v-for="(attribute, index) in form.attributes" :key="index">
                        <jet-text-input v-model="attribute.name" id="Attributes" type="text" class="mt-1 block w-full" autocomplete="priceWithVat" />
                        <jet-text-input v-model="attribute.value" id="Attributes" type="text" class="mt-1 block w-full" autocomplete="priceWithVat" />
                        <jet-danger-button title="Add attribute" @click="index == (form.attributes.length - 1) ? addAttribute() : removeAttribute(index)">
                            <i class="ti-plus" v-if="index == (form.attributes.length - 1)"></i>
                            <i class="ti-minus" v-else></i>
                        </jet-danger-button>
                    </div>
                </div>
                <jet-input-error :message="form.errors.attributes" class="mt-2" />
			</form-group>

			<!-- Image -->
			<form-group>
				<jet-label for="image" class="md:w-1/4 mt-2" value="Image" />
				<div class="w-full mt-1">
					<jet-image-input :url="product.primaryMediaUrl" v-model="form.image"></jet-image-input>
                     <small class="mt-1 font-thin text-gray-400">* Image should be 1:1 acpect ratio. Maximum file size: 5MB(5120KB).</small>
					<jet-input-error :message="form.errors.image" class="mt-2" />
				</div>
			</form-group>

		</template>

		<template #actions>
			<Link :href="route('products.index')" class="xs:mr-4">Cancel</Link>
			<jet-button @click.prevent="update('products.update', product.id, true)" class="px-6 xs:mr-2 my-2 xs:my-0" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Update & Continue</jet-button>
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
import JetImageInput from "@/Components/ImageInput.vue";
import JetDangerButton from "@/Components/DangerButton.vue";

export default {
	name: "edit-product",
	props: {
		product: Object,
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
		JetImageInput,
        JetDangerButton
	},
	data() {
		return {
			breadcrumb: [
				{ label: "Home", route: this.route("dashboard") },
				{ label: "Products", route: this.route("products.index") },
				{ label: "Edit", route: null },
			],

			form: this.$inertia.form({
				product_name: this.product.productName,
				price: this.product.price,
				price_with_vat: this.product.priceWithVat,
                attributes: this.product.attributes,
				image: null,
			}),

		};
	},
    methods: {
        addAttribute: function() {
            this.form.attributes.push({name: '', value: ''})
        },
        removeAttribute: function(position) {
            this.form.attributes.splice(position, 1)
        }
    }
};
</script>
