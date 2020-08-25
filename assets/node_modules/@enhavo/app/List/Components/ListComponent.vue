<template>
    <div class="grid">
        <div class="view-table">
            <div class="view-table-head">
                <div class="view-table-head-columns">
                    <div
                        v-for="column in $list.data.columns"
                        v-bind:key="column.key"
                        v-bind:style="getColumnStyle(column)"
                        class="view-table-col"
                        >
                        {{ column.label }}
                    </div>
                </div>
            </div>

            <template v-if="!$list.data.loading">
                <template v-if="$list.data.sortable">
                    <draggable group="list" v-model="$list.data.items" v-on:change="save($event, null)" @start="$list.data.dragging = true" @end="$list.data.dragging = false" :class="{'dragging': $list.data.dragging}">
                        <template v-for="item in $list.data.items">
                            <list-item v-bind:data="item" :key="item.id"></list-item>
                        </template>
                    </draggable>
                </template>
                <template v-else>
                    <template v-for="item in $list.data.items">
                        <list-item v-bind:data="item" :key="item.id"></list-item>
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

<script lang="ts">
    import { Vue, Component } from "vue-property-decorator";
    import * as draggable from 'vuedraggable';

    Vue.component('draggable', draggable);

    @Component
    export default class ListView extends Vue
    {
        calcColumnWidth(parts: number): string {
            return (100 / 12 * parts) + '%';
        }

        getColumnStyle(column: any): object {
            return Object.assign(
                {},
                column.style,
                {width: this.calcColumnWidth(column.width)}
            );

        }

        save(event, parent)
        {
            if(event.added) {
                this.$list.save(parent);
            } else if(event.moved) {
                this.$list.save(parent);
            }
        }
    }
</script>
