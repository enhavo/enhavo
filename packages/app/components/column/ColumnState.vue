<template>
    <div :class="{'view-table-col-text': true, 'text-wrap': column.wrap}" :style="getStyle()">
        {{ data.value }}
    </div>
</template>

<script setup lang="ts">
import {StateColumn} from "@enhavo/app/column/model/StateColumn";

const props = defineProps<{
    data: any,
    column: StateColumn,
}>()

function getStyle()
{
    return 'color: ' + props.data.color;
}
</script>






