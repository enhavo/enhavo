<template>
    <collection-table-pagination :collection="collection"></collection-table-pagination>
    <div class="view-table">
        <div class="view-table-head">
            <div class="checkbox-container" v-if="collection.batches.length > 0">
                <input type="checkbox" v-on:change="collection.changeSelectAll(!collection.selectedAll)" :checked="collection.selectedAll" />
                <span></span>
            </div>
            <div class="view-table-head-columns">
                <template v-for="column in collection.columns">
                    <div
                        v-if="column.display"
                        :key="column.key"
                        :style="getColumnStyle(column)"
                        :class="['view-table-col', {'sortable': column.sortable}, {'sorted': column.directionDesc !== null}]"
                        v-on:click="() => collection.changeSortDirection(column)"
                    >
                        {{ column.label }}
                        <i
                            v-if="column.sortable"
                            v-bind:class="['icon', {'icon-arrow_upward': !column.directionDesc}, {'icon-arrow_downward': column.directionDesc}, {'sortable': column.sortable}]"
                        >
                        </i>
                    </div>
                </template>
            </div>
        </div>

        <template v-if="!collection.loading">
            <template v-for="row in collection.rows">
                <collection-table-row :data="row" :collection="collection"></collection-table-row>
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
    <collection-table-pagination :collection="collection"></collection-table-pagination>
</template>

<script setup lang="ts">
import {onMounted, onUnmounted} from "vue";
import {TableCollection} from "../../collection/model/TableCollection";
import $ from "jquery";
import CollectionTablePagination from "./CollectionTablePagination.vue";

const props = defineProps<{
    collection: TableCollection,
}>()

const collection = props.collection;

const resizeHandler = () => {
    collection.resize();
}

onMounted(() => {
    collection.load();
    window.addEventListener('resize', resizeHandler);
})

onUnmounted(() => {
    window.removeEventListener('resize', resizeHandler);
});


function calcColumnWidth(parts: number): string
{
    let totalWidth = 0;
    for(let column of collection.columns) {
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

</script>
