<template>
    <div class="grid">
        <div class="view-table">
            <div class="view-table-head">
                <div class="view-table-head-columns">
                    <div
                        v-for="column in data.columns"
                        v-bind:key="column.key"
                        v-bind:style="getColumnStyle(column)"
                        class="view-table-col"
                        >
                        {{ column.label }}
                    </div>
                </div>
            </div>

            <template v-if="!data.loading">
                <template v-if="data.sortable">
                    <draggable group="list" v-model="data.items" v-on:change="save($event, null)" @start="data.dragging = true" @end="data.dragging = false" :class="{'dragging':data.dragging == true}">
                        <template v-for="item in data.items">
                            <list-item v-bind:columns="data.columns" v-bind:data="item" :key="item.id"></list-item>
                        </template>
                    </draggable>
                </template>
                <template v-else>
                    <template v-for="item in data.items">
                        <list-item v-bind:columns="data.columns" v-bind:data="item" :key="item.id"></list-item>
                    </template>
                </template>
            </template>

            <template v-else>
                <div class="loading-placeholder">
                    <div class="loading-indicator">
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
    import { Vue, Component, Prop } from "vue-property-decorator";
    import ItemComponent from "@enhavo/app/List/Components/ItemComponent.vue";
    import ListData from "@enhavo/app/List/ListData";
    import * as draggable from 'vuedraggable';
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import ListApplication from "@enhavo/app/List/ListApplication";
    const application = <ListApplication>ApplicationBag.getApplication();

    Vue.component('draggable', draggable);
    Vue.component('list-item', ItemComponent);

    @Component()
    export default class ListView extends Vue {
        name = 'list-list';

        @Prop()
        data: ListData;

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
                application.getList().save(parent);
            } else if(event.moved) {
                application.getList().save(parent);
            }
        }
    }
</script>


