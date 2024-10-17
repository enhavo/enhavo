<template>
    <div class="view-table-list-row">
        <div :class="{'view-table-row': true, 'active': data.active, 'has-children': data.children && data.children.length > 0,'hide-children':!data.expand}" @click="open()">
            <div v-if="data.parentProperty && data.children && data.children.length > 0" @click="toggleExpand()" @click.stop>
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
                @change="save($event, null)"
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
    props.data.expand = !props.data.expand;
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
            return field.value;
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
</script>


