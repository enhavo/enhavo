<template>
    <div class="view-table-list-row">
        <div :class="{'view-table-row': true, 'active': data.active, 'has-children': data.children && data.children.length > 0,'hide-children':!data.expand}" @click="open()">
            <div v-if="data.parentProperty && data.children && data.children.length > 0" @click="toggleExpand()" v-on:click.stop>
                <i v-if="data.expand" class="icon icon-unfold_more"></i>
                <i v-if="!data.expand" class="icon icon-unfold_less"></i>
            </div>
            <div class="view-table-row-columns">
                <template v-for="column in collection.columns">
                    <component
                        class="view-table-col"
                        :is="column.component"
                        :column="column"
                        :style="getColumnStyle(column)"
                        :data="getColumnData(column.key)"></component>
                </template>
            </div>
        </div>
        <div class="view-table-list-row-children" v-if="data.expand && data.parentProperty" :class="{ 'has-children': data.children && data.children.length > 0 }">
            <draggable
                v-model="data.children"
                group="list"
                item-key="id"
                v-on:change="save($event, null)"
                @start="data.dragging = true"
                @end="data.dragging = false"
                :class="{'dragging':data.dragging == true}"
            >
                <template #item="{ element }">
                    <collection-list-item :data="element" :collection="collection"></collection-list-item>
                </template>
            </draggable>
        </div>
    </div>
</template>

<script setup lang="ts">
import {CollectionResourceItem} from "@enhavo/app/collection/CollectionResourceItem";
import {TableCollection} from "@enhavo/app/collection/model/TableCollection";
import draggable from 'vuedraggable'

const props = defineProps<{
    data: CollectionResourceItem,
    collection: TableCollection,
}>()

const data = props.data;
const collection = props.collection;


function open() 
{
    collection.open(data);
}

function calcColumnWidth(parts: number): string 
{
    return (100 / 12 * parts) + '%';
}

function toggleExpand()
{
    data.expand = !data.expand;
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
    for (let field of data.data) {
        if (field.key === column) {
            return field.value;
        }
    }
    return null;
}

function save(event, parent)
{
    if (event.added) {
        collection.save(parent);
    } else if(event.moved) {
        collection.save(parent);
    }
}
</script>


