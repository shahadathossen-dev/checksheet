<template>
	<button :type="type" class="inline-flex items-center justify-center px-6 py-2 bg-purple-500 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-600 active:bg-purple-700 focus:outline-none focus:border-purple-700 focus:ring focus:ring-purple-200 disabled:opacity-25 transition whitespace-nowrap">
		<slot></slot>
	</button>
</template>

<script>
export default {
	props: {
		type: {
			type: String,
			default: "submit",
		},
	},
};
</script>
