<template>
    <div class="view-table">
        <div class="view-table-head">
            <div class="checkbox-container" v-if="batchManager.hasBatches()">
                <input type="checkbox" v-on:change="changeSelectAll" :checked="grid.configuration.selectAll" />
                <span></span>
            </div>
            <div class="view-table-head-columns">
                <template v-for="column in columnManager.columns">
                    <div
                        v-if="column.display"
                        v-bind:key="column.key"
                        v-bind:style="getColumnStyle(column)"
                        v-bind:class="['view-table-col', {'sortable': column.sortable}, {'sorted': column.directionDesc !== null}]"
                        v-on:click="changeSortDirection(column)"
                    >
                        {{ column.label }}
                        <i
                            v-if="column.sortable"
                            v-bind:class="['icon', {'icon-arrow_upward': !column.directionDesc}, {'icon-arrow_downward': column.directionDesc}, {'sortable': column.sortable}]"
                            aria-hidden="true">
                        </i>
                    </div>
                </template>
            </div>
        </div>

        <template v-if="! grid.configuration.loading">
            <template v-for="row in grid.configuration.rows">
                <grid-table-row v-bind:data="row"></grid-table-row>
            </template>
        </template>
        <template v-else>
            <div class="loading-placeholder">
                <div class="loading-indicator">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import { inject, onMounted, getCurrentInstance } from 'vue'
import * as $ from "jquery";
import Grid from "@enhavo/app/grid/Grid";
import BatchManager from "@enhavo/app/grid/batch/BatchManager";
import ColumnManager from "@enhavo/app/grid/column/ColumnManager";

const grid = inject<Grid>('grid');
const batchManager = inject<BatchManager>('batchManager');
const columnManager = inject<ColumnManager>('columnManager');
const instance = getCurrentInstance();

onMounted(() => {
    $(window).on('resize', () => {
        grid.resize();
        instance?.proxy?.$forceUpdate();
    });
})


function calcColumnWidth(parts: number): string
{
    let totalWidth = 0;
    for(let column of columnManager.columns) {
        if(column.display) {
            totalWidth += column.width;
        }
    }
    return (100 / totalWidth * parts) + '%';
}

function getColumnStyle(column: any): object
{
    let styles: object = Object.assign(
        {},
        column.style,
        {width: calcColumnWidth(column.width)} );

    return styles;
}

function changeSelectAll()
{
    grid.changeSelectAll(!grid.configuration.selectAll);
}

function changeSortDirection(column: any)
{
    grid.changeSortDirection(column);
}

</script>
