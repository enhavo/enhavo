<template>
    <div :class="['grid', list.data.cssClass]">
        <div class="view-table">
            <div class="view-table-head">
                <div class="view-table-head-columns">
                    <div
                        v-for="column in columnManager.columns"
                        :key="column.key"
                        :style="getColumnStyle(column)"
                        class="view-table-col"
                        >
                        {{ column.label }}
                    </div>
                </div>
            </div>

            <template v-if="!list.data.loading">
                <template v-if="list.data.sortable">
                    <draggable
                        v-model="list.data.items"
                        group="list"
                        item-key="id"
                        @change="save($event, null)"
                        @start="list.data.dragging = true"
                        @end="list.data.dragging = false"
                        :class="{'dragging': list.data.dragging}"
                    >
                        <template #item="{ element }">
                            <div class="list-group-item">
                                <list-item :data="element"></list-item>
                            </div>
                        </template>
                    </draggable>
                </template>
                <template v-else>
                    <template v-for="item in list.data.items">
                        <list-item :data="item"></list-item>
                    </template>
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
    </div>
</template>

<script setup lang="ts">
import { inject } from 'vue'
import List from "@enhavo/app/list/List";
import {ColumnManager} from "@enhavo/app/column/ColumnManager";

const list = inject<List>('list')
const columnManager = inject<ColumnManager>('columnManager')

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
        list.save(parent);
    } else if(event.moved) {
        list.save(parent);
    }
}

</script>
