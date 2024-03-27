<template>
    <div :class="{'view-table-col-text': true, 'text-wrap': column.wrap}" :style="{'white-space': column.whitespace}">{{ data }}</div>
</template>

<script setup lang="ts">
import TextColumn from "@enhavo/app/grid/column/model/TextColumn";

const props = defineProps<{
    data: string,
    column: TextColumn,
}>()
</script>
