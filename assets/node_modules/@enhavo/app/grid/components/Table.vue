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

<script lang="ts">
import {Vue, Options, Inject} from "vue-property-decorator";
import * as $ from "jquery";
import Grid from "@enhavo/app/grid/Grid";
import BatchManager from "@enhavo/app/grid/batch/BatchManager";
import ColumnManager from "@enhavo/app/grid/column/ColumnManager";

@Options({})
export default class extends Vue
{
    @Inject()
    grid: Grid;

    @Inject()
    batchManager: BatchManager;

    @Inject()
    columnManager: ColumnManager;

    mounted() {
        $(window).resize(() => {
            this.grid.resize();
            this.$forceUpdate();
        });
    }

    calcColumnWidth(parts: number): string {
        let totalWidth = 0;
        for(let column of this.columnManager.columns) {
            if(column.display) {
                totalWidth += column.width;
            }
        }
        return (100 / totalWidth * parts) + '%';
    }

    getColumnStyle(column: any): object {
        let styles: object = Object.assign(
            {},
            column.style,
            {width: this.calcColumnWidth(column.width)} );

        return styles;
    }

    changeSelectAll() {
        this.grid.changeSelectAll(!this.grid.configuration.selectAll);
    }

    changeSortDirection(column: any) {
        this.grid.changeSortDirection(column);
    }
}
</script>
