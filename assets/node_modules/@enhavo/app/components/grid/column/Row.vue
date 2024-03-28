<template>
    <div :class="{'view-table-row': true, 'active': data.active, 'selected': data.selected, 'clickable': grid.isRowClickable()}" @click="open()">
        <div class="checkbox-container" v-if="batchManager.hasBatches()">
            <input type="checkbox" v-on:change="changeSelect" v-on:click.stop :checked="data.selected" />
            <span></span>
        </div>
        <div class="view-table-row-columns">
            <template v-for="column in columnManager.columns">
                <component
                    class="view-table-col"
                    v-if="column.display"
                    v-bind:is="column.component"
                    v-bind:key="column.key"
                    v-bind:column="column"
                    v-bind:style="getColumnStyle(column)"
                    v-bind:data="getColumnData(column.key)"></component>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {inject, onMounted, getCurrentInstance} from "vue";
import RowData from "@enhavo/app/grid/column/RowData";
import * as $ from "jquery";
import BatchManager from "@enhavo/app/grid/batch/BatchManager";
import ColumnManager from "@enhavo/app/grid/column/ColumnManager";
import Grid from "@enhavo/app/grid/Grid";


const batchManager = inject<BatchManager>('batchManager');
const columnManager = inject<ColumnManager>('columnManager');
const grid = inject<Grid>('grid');

const props = defineProps<{
    data: RowData
}>()

const data = props.data;

onMounted(() => {
    $(window).on('resize', () => {
        grid.resize();
        const instance = getCurrentInstance();
        instance?.proxy?.$forceUpdate();
    });
});

function changeSelect()
{
    grid.changeSelect(data, !data.selected);
}

function open()
{
    if (grid.isRowClickable()) {
        grid.open(data);
    }
}

function calcColumnWidth(parts: number): string
{
    let totalWidth = 0;
    for (let column of columnManager.columns) {
        if (column.display) {
            totalWidth += column.width;
        }
    }
    return (100 / totalWidth * parts) + '%';
}

function getColumnStyle(column: any): object
{
    return Object.assign(
        {},
        column.style,
        {width: calcColumnWidth(column.width)}
    );
}

function getColumnData(column: string): object
{
    if (data.data.hasOwnProperty(column) ) {
        return data.data[column];
    }
    return null;
}
</script>
