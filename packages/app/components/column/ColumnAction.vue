<template>
    <div class="view-table-col-text">
        <component v-for="action in column.getActions(data.actions)" class="action-container" :is="action.component" :data="action" :click-stop="true"></component>
    </div>
</template>

<script setup lang="ts">
import {ActionColumn, ActionColumnData} from "@enhavo/app/column/model/ActionColumn";

const props = defineProps<{
    data: ActionColumnData,
    column: ActionColumn,
}>()
</script>
