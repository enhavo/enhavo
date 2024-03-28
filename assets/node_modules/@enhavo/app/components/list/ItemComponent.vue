<template>
    <div class="view-table-list-row">
        <div :class="{'view-table-row': true, 'active': data.active, 'has-children': data.children && data.children.length > 0,'hide-children':!data.expand}" @click="open()">
            <div v-if="data.parentProperty && data.children && data.children.length > 0" @click="toggleExpand()" v-on:click.stop>
                <i v-if="data.expand" class="icon icon-unfold_more"></i>
                <i v-if="!data.expand" class="icon icon-unfold_less"></i>
            </div>
            <div class="view-table-row-columns">
                <template v-for="column in columnManager.columns">
                    <component
                        class="view-table-col"
                        v-bind:is="column.component"
                        v-bind:column="column"
                        v-bind:style="getColumnStyle(column)"
                        v-bind:data="getColumnData(column.key)"></component>
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
                    <list-item v-bind:data="element"></list-item>
                </template>
            </draggable>
        </div>
    </div>
</template>

<script setup lang="ts">
import { inject } from 'vue'
import Item from "@enhavo/app/list/Item";
import List from "@enhavo/app/list/List";
import ColumnManager from "@enhavo/app/grid/column/ColumnManager";

const columnManager = inject<ColumnManager>('columnManager')
const list = inject<List>('list')

const props = defineProps<{
    data: Item
}>()

const data = props.data;


function open() 
{
    list.open(data);
}

function calcColumnWidth(parts: number): string 
{
    return (100 / 12 * parts) + '%';
}

function toggleExpand()
{
    data.expand = !data.expand;
}

function getColumnStyle(column: any): object {
    let styles: object = Object.assign(
        {},
        column.style,
        {width: calcColumnWidth(column.width)} );

    return styles;
}

function getColumnData(column: string): object
{
    if (data.data.hasOwnProperty(column) ) {
        return data.data[column];
    }
    return null;
}

function save(event, parent)
{
    if (event.added) {
        list.save(parent);
    } else if(event.moved) {
        list.save(parent);
    }
}
</script>


