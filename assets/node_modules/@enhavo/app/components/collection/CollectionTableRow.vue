<template>
    <div :class="{'view-table-row': true, 'active': data.active, 'selected': data.selected, 'clickable': !!data.url}" @click="open()">
        <div class="checkbox-container" v-if="collection.batches.length > 0">
            <input type="checkbox" @change="changeSelect" @click.stop :checked="data.selected" />
            <span></span>
        </div>
        <div class="view-table-row-columns">
            <template v-for="column in collection.columns" :key="column.key + '-' + data.id">
                <component
                    v-if="column.isVisible()"
                    class="view-table-col"
                    :is="column.component"
                    :column="column"
                    :style="getColumnStyle(column)"
                    :data="getColumnData(column.key)">
                </component>
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

function changeSelect()
{
    props.collection.changeSelect(props.data, !props.data.selected);
}

function open()
{
    props.collection.open(props.data);
}

function calcColumnWidth(parts: number): string
{
    let totalWidth = 0;
    for (let column of props.collection.columns) {
        if (column.isVisible()) {
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
    for (let field of props.data.data) {
        if (field.key === column) {
            return field.value;
        }
    }
    return null;
}
</script>
