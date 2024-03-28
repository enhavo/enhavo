<template>
    <div class="view-table-col-text">
        <component class="action-container" v-bind:is="getAction().component" v-bind:data="getAction()" :click-stop="true"></component>
    </div>
</template>

<script setup lang="ts">
import ActionColumn from "@enhavo/app/grid/column/model/ActionColumn";
import ActionInterface from "@enhavo/app/action/ActionInterface";

const props = defineProps<{
    data: string,
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
