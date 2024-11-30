<template>
    <div class="view-table">
        <div class="view-table-head">
            <div class="view-table-head-columns">
                <div
                    v-for="column in collection.columns"
                    :style="getColumnStyle(column)"
                    class="view-table-col"
                >
                    {{ column.label }}
                </div>
            </div>
        </div>


        <template v-if="collection.sortable && !collection.filtered">
            <draggable
                v-model="collection.items"
                group="list"
                item-key="id"
                @change="save($event, null)"
                @start="collection.dragging = true"
                @end="collection.dragging = false"
                :class="{'dragging': collection.dragging}"
            >
                <template #item="{ element }">
                    <div class="list-group-item">
                        <collection-list-item :data="element" :collection="collection"></collection-list-item>
                    </div>
                </template>
            </draggable>
        </template>
        <template v-else>
            <template v-for="item in collection.items">
                <collection-list-item :data="item" :collection="collection"></collection-list-item>
            </template>
        </template>

        <div class="loading-placeholder" v-if="collection.loading">
            <div class="loading-indicator">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {ListCollection} from "@enhavo/app/collection/model/ListCollection";
import draggable from 'vuedraggable'
import {onMounted, onUnmounted} from "vue";

const props = defineProps<{
    collection: ListCollection,
}>()

const resizeHandler = () => {
    props.collection.resize();
}

onMounted(() => {
    props.collection.load();
    window.addEventListener('resize', resizeHandler);
})

onUnmounted(() => {
    window.removeEventListener('resize', resizeHandler);
});

function calcColumnWidth(parts: number): string
{
    return (100 / 12 * parts) + '%';
}

function getColumnStyle(column: any): object
{
    return Object.assign(
        {},
        column.style,
        {width: calcColumnWidth(column.width)}
    );
}

function save(event, parent)
{
    if (event.added) {
        props.collection.save(parent);
    } else if(event.moved) {
        props.collection.save(parent);
    }
}

</script>