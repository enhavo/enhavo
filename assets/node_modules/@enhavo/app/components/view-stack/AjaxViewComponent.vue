<template>
    <iframe :src="data.url"></iframe>
</template>

<script setup lang="ts">
const props = defineProps<{
    data: object
}>()
</script>

<style lang="scss" scoped>
    iframe { border: 0; height: 100%; width: 100%; }
</style>
