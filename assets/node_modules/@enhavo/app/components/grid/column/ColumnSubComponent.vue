<template>
    <div class="view-table-col-sub">
        <template v-for="row in getRows()">
            <component 
                class="view-table-col-child" 
                :is="row.component"
                :data="getRowData(row.key)"></component>
        </template>
    </div>
</template>

<script setup lang="ts">
const props = defineProps<{
    data: any,
    column: any,
}>()

const data = props.data;
const column = props.column;

function getRows(): object {
    if (column.hasOwnProperty('rows') ) {
        return column['rows'];
    }
    return null;
}

function getRowData(row: string): any
{
    if (data.hasOwnProperty(row) ) { // TODO check if clause
        return data[row];
    }
    return null;
}
</script>

<style lang="scss" scoped>
    .view-table-col-sub { 
        background-color: seagreen;

        .view-table-col-child {
            margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px;

            &:last-child {
                margin-bottom: 0;
            }
        }
    }
</style>
