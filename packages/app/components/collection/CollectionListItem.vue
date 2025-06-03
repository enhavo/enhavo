<template>
    <div class="view-table-list-row">
        <div :class="{'view-table-row': true, 'active': data.active, 'has-children': data.children && data.children.length > 0,'hide-children':!isExpanded()}" @click="open()">
            <template v-if="collection.treeable">
                <div v-if="data.children && data.children.length > 0" @click="toggleExpand()" @click.stop class="view-table-list-expand-icon">
                    <i v-if="isExpanded()" class="icon icon-unfold_more"></i>
                    <i v-if="!isExpanded()" class="icon icon-unfold_less"></i>
                </div>
                <div v-else class="view-table-list-expand-icon"></div>
            </template>
            <div class="checkbox-container" v-if="collection.batches.length > 0">
                <input type="checkbox" @change="changeSelect" @click.stop :checked="data.selected" />
                <span></span>
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
        <div class="view-table-list-row-children" v-if="isExpanded() && collection.treeable" :class="{ 'has-children': data.children && data.children.length > 0 }">
            <draggable
                v-model="data.children"
                group="list"
                item-key="id"
                @change="save($event, data)"
                @start="collection.dragging = true"
                @end="collection.dragging = false"
                :class="{'dragging':collection.dragging == true}"
            >
                <template #item="{ element }">
                    <collection-list-item :data="element" :collection="collection"></collection-list-item>
                </template>
            </draggable>
        </div>
    </div>
</template>

<script setup lang="ts">
import {ListCollection} from "@enhavo/app/collection/model/ListCollection";
import draggable from 'vuedraggable'
import {ListCollectionResourceItem} from "@enhavo/app/collection/ListCollectionResourceItem";

const props = defineProps<{
    data: ListCollectionResourceItem,
    collection: ListCollection,
}>()

function open() 
{
    props.collection.open(props.data);
}

function calcColumnWidth(parts: number): string 
{
    return (100 / 12 * parts) + '%';
}

function toggleExpand()
{
    if (isExpanded()) {
        let index = props.collection.expandedIds.indexOf(props.data.id);
        props.collection.expandedIds.splice(index, 1);
    } else {
        props.collection.expandedIds.push(props.data.id)
    }
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
    for (let field of props.data.data) {
        if (field.key === column) {
            return field;
        }
    }
    return null;
}

function save(event, parent)
{
    if (event.added) {
        props.collection.save(parent);
    } else if(event.moved) {
        props.collection.save(parent);
    }
}

function changeSelect()
{
    props.collection.changeSelect(props.data, !props.data.selected);
}

function isExpanded(): boolean
{
    return props.collection.expandedIds.includes(props.data.id);
}

</script>


