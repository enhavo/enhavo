<template>
    <div class="view-table-col-text">
        <img :style="{height: data.height + 'px'}" :src="data.url" />
    </div>
</template>

<script setup lang="ts">
const props = defineProps<{
    data: any,
    column: any,
}>()
</script>
