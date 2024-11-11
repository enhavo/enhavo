<template>
    <div class="view-table-col-text">
        <i class="icon icon-done" v-if="data.value"></i>
        <i class="icon icon-close" v-if="!data.value"></i>
    </div>
</template>

<script setup lang="ts">
import {BooleanColumn, BooleanColumnData} from "../../column/model/BooleanColumn";

const props = defineProps<{
    data: BooleanColumnData,
    column: BooleanColumn,
}>()
</script>
