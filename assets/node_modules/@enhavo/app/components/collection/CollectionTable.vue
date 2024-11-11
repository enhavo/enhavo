<template>
    <collection-table-pagination :collection="collection"></collection-table-pagination>
    <div class="view-table">
        <div class="view-table-head">
            <div class="checkbox-container" v-if="collection.batches.length > 0">
                <input type="checkbox" @change="collection.changeSelectAll(!collection.selectedAll)" :checked="collection.selectedAll" />
                <span></span>
            </div>
            <div class="view-table-head-columns">
                <template v-for="column in collection.columns">
                    <div
                        v-if="column.isVisible()"
                        :style="getColumnStyle(column)"
                        :class="['view-table-col', {'sortable': column.sortable}, {'sorted': column.sortingDirection !== null}]"
                        @click="() => collection.changeSortDirection(column)"
                    >
                        {{ column.label }}
                        <i
                            v-if="column.sortable"
                            :class="['icon', {'icon-arrow_upward': column.sortingDirection !== 'desc'}, {'icon-arrow_downward': column.sortingDirection === 'desc'}, {'sortable': column.sortable}]"
                        >
                        </i>
                    </div>
                </template>
            </div>
        </div>

        <collection-table-row v-for="row in collection.rows" :data="row" :collection="collection"></collection-table-row>

        <div class="loading-placeholder" v-if="collection.loading">
            <div class="loading-indicator">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    <collection-table-pagination :collection="collection"></collection-table-pagination>
</template>

<script setup lang="ts">
import {StyleValue, onMounted} from "vue";
import {TableCollection} from "../../collection/model/TableCollection";

const props = withDefaults(defineProps<{
    collection: TableCollection,
    load?: boolean
}>(), {
    load: true
})

const collection = props.collection;

if (props.load) {
    collection.load();
}

onMounted(() => {
    window.addEventListener('resize', () => {
        for (let column of collection.columns) {
            column.checkVisibility();
        }
    })
})

function calcColumnWidth(parts: number): string
{
    let totalWidth = 0;
    for (let column of collection.columns) {
        if (column.isVisible()) {
            totalWidth += column.width;
        }
    }
    return (100 / totalWidth * parts) + '%';
}

function getColumnStyle(column: any): StyleValue
{
    let styles: object = Object.assign(
        {},
        column.style,
        {width: calcColumnWidth(column.width)}
    ) as StyleValue;

    return styles;
}

</script>
