<template>
    <div class="media-library-sort">
        <ul>
            <li v-for="column in collection.columns"
                 @click="onSort(column)"
                 :class="isSorted(column)?'sorted':''"
                 >
                {{ column.label }}
                <i aria-hidden="true"
                   class="icon sortable"
                   :class="sortClass(column)">
                </i>
            </li>
        </ul>
    </div>
    <collection-table-pagination :collection="collection"></collection-table-pagination>
    <ul class="images scroll-container">
        <li v-for="row of collection.rows" :class="{'active': collection.isSelectMode() && row.selected}" @click="open(row)" >
            <span v-if="collection.isSelectMode() && row.selected"><i class="icon icon-check"></i></span>
            <div class="img">
                <div class="background" :style="{ backgroundImage: 'url(' + row.previewImageUrl + ')' }">
                </div>
                <span ><i class="icon" :class="row.icon"></i></span>
                <label class="checkbox-click-wrapper" :for="'media-item-' + row.id" v-if="collection.batches.length > 0 && !collection.isSelectMode()" @click.stop>
                    <div class="checkbox-container">
                        <input type="checkbox" :id="'media-item-' + row.id" @change="collection.changeSelect(row, !row.selected)" :checked="row.selected" />
                        <span></span>
                    </div>
                </label>
            </div>
            <div class="title">{{ row.label }}</div>
        </li>
    </ul>
    <collection-table-pagination :collection="collection"></collection-table-pagination>
</template>

<script setup lang="ts">
import {MediaLibraryCollection} from "@enhavo/media-library/collection/MediaLibraryCollection";

const props = defineProps<{
    collection: MediaLibraryCollection,
}>()


function onSort(column: Column)
{
    //props.collection.setSortColumn(column);
}

function isSorted(column: Column)
{
    //return props.collection.isSortedColumn(column);
}

function sortClass(column: Column)
{
    //if (props.collection.isSortedColumn(column)) {
    //    return props.collection.data.sortColumn.direction === 'asc' ? 'icon-arrow_upward' : 'icon-arrow_downward';
    //}
}

function open(data)
{
    props.collection.open(data);
}
</script>
