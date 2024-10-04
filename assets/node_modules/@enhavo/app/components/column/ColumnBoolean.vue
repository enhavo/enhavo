<template>
    <div class="view-table-col-text">
        <i class="icon icon-done" v-if="data"></i>
        <i class="icon icon-close" v-if="!data"></i>
    </div>
</template>

<script setup lang="ts">
import {BooleanColumn} from "../../column/model/BooleanColumn";

const props = defineProps<{
    data: boolean|null,
    column: BooleanColumn,
}>()
</script>
