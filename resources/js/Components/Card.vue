<template>
<div class="card">
    <header class="card_header" :class="$attrs.headerClass">
        <div class="card_header--left">
            <slot name="left-header"></slot>
        </div>
        <div class="card_header--right">
            <slot name="right-header"></slot>
        </div>
    </header>

    <div class="card_body" :class="$attrs.bodyClass">
        <slot name="body"></slot>
    </div>

    <footer class="card_footer" :class="$attrs.footerClass">
        <slot name="footer"></slot>
    </footer>
</div>
</template>

<script setup>
//
</script>
<script>
export default {
  inheritAttrs: false,
}
</script>
<style lang="scss" scoped>
.card {
    @apply  w-full;
    &_header {
        @apply  p-2 flex w-full justify-between;

    }

    &_body {
        @apply p-2;
    }

    &_footer {
        @apply p-2;
    }
}
</style>