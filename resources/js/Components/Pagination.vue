<template>
<div class="flex flex-wrap gap-2">
	<template v-for="(link, key) in links" :key="key">
		<div v-if="link.url === null" class="px-4 py-2 flex items-center justify-center text-sm leading-4 text-gray-400 bg-gray-200 rounded-full" v-html="link.label">
		</div>
		<Link v-else :key="key" class="px-4 py-2 flex items-center justify-center text-sm leading-4 bg-gray-200 rounded-full hover:bg-primary-400 hover:text-white focus:text-white" :class="{ 'bg-primary-500 text-white': link.active }" :href="link.url" v-html="link.label" />
	</template>
</div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
	components: {
		Link,
	},
	props: {
		links: Array,
	},
};
</script>
