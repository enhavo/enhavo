<template>
    <div :class="{'view-table-col-text': true, 'text-wrap': column.wrap}" :style="{'white-space': column.whitespace}">{{ data.value }}</div>
</template>

<script setup lang="ts">
import {TextColumn, TextColumnData} from "@enhavo/app/column/model/TextColumn";

const props = defineProps<{
    data: TextColumnData,
    column: TextColumn,
}>()
</script>
