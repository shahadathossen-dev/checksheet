<template>
	<Link :href="href" class="inline-flex bg-primary-500 hover:bg-primary-600 focus:bg-primary-700 text-white items-center px-4 py-0 shadow-md border border-transparent rounded-primary font-bold text-sm  transition">
	<slot class="mr-2" name="icon"></slot>
	<slot></slot>
	</Link>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
	props: ["href"],
	components: {
		Link,
	},
};
</script>

Button
