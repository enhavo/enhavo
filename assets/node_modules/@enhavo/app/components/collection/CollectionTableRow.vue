<template>
    <div :class="{'view-table-row': true, 'active': data.active, 'selected': data.selected, 'clickable': data.open}" @click="open()">
        <div class="checkbox-container" v-if="collection.batches.length > 0">
            <input type="checkbox" v-on:change="changeSelect" v-on:click.stop :checked="data.selected" />
            <span></span>
        </div>
        <div class="view-table-row-columns">
            <template v-for="column in collection.columns">
                <component
                    class="view-table-col"
                    v-if="column.display"
                    v-bind:is="column.component"
                    v-bind:key="column.key"
                    v-bind:column="column"
                    v-bind:style="getColumnStyle(column)"
                    v-bind:data="getColumnData(column.key)"></component>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {TableCollection} from "../../collection/model/TableCollection";
import {CollectionResourceItem} from "../../collection/CollectionResourceItem";

const props = defineProps<{
    data: CollectionResourceItem,
    collection: TableCollection,
}>()

const data = props.data;
const collection = props.collection;

function changeSelect()
{
    collection.changeSelect(data, !data.selected);
}

function open()
{
    collection.open(data);
}

function calcColumnWidth(parts: number): string
{
    let totalWidth = 0;
    for (let column of collection.columns) {
        if (column.display) {
            totalWidth += column.width;
        }
    }
    return (100 / totalWidth * parts) + '%';
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
</script>
