<template>
    <div class="view-table-col-text">
        <component class="action-container" :is="getAction().component" :data="getAction()" :click-stop="true"></component>
    </div>
</template>

<script setup lang="ts">
import {ActionColumn} from "@enhavo/app/column/model/ActionColumn";
import {ActionInterface} from "@enhavo/app/action/ActionInterface";

const props = defineProps<{
    data: any,
    column: ActionColumn,
}>()

let action: ActionInterface = null;

function getAction()
{
    if (action == null) {
        action = props.column.getAction(props.data);
    }
    return action;
}
</script>
