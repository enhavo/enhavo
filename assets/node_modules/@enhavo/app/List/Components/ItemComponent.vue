<template>
    <div class="view-table-list-row">
        <div class="view-table-row" @click="open()" :class="{ 'has-children': data.children && data.children.length > 0 }">
            <div @click="toggleExpand()" v-on:click.stop><i class="icon icon-unfold_less"></i></div>
            <div class="view-table-row-columns">
                <template v-for="column in columns">
                    <component
                        class="view-table-col"
                        v-bind:is="column.component"
                        v-bind:key="column.key"
                        v-bind:column="column"
                        v-bind:style="getColumnStyle(column)"
                        v-bind:data="getColumnData(column.key)"></component>
                </template>
            </div>
        </div>
        <div class="view-table-list-row-children" v-if="data.expand" :class="{ 'has-children': data.children && data.children.length > 0 }">
            <draggable group="list" v-model="data.children" v-on:change="save($event, data)" @start="data.dragging = true" @end="data.dragging = false" :class="{'dragging':data.dragging == true}">
                <template v-for="item in data.children">
                    <list-item v-bind:columns="columns" v-bind:data="item" :key="item.id"></list-item>
                </template>
            </draggable>
        </div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import ListApplication from "@enhavo/app/List/ListApplication";
    import Item from "@enhavo/app/List/Item";
    const application = <ListApplication>ApplicationBag.getApplication();

    @Component({
        components: application.getColumnRegistry().getComponents()
    })
    export default class ItemComponent extends Vue {
        name: string = 'list-item';

        @Prop()
        data: Array<Item>;

        @Prop()
        columns: Array<object>;

        open() {
            application.getList().open(this.data);
        }

        calcColumnWidth(parts: number): string {
            return (100 / 12 * parts) + '%';
        }

        toggleExpand()
        {
            this.data.expand = !this.data.expand;
        }

        getColumnStyle(column: any): object {
            let styles: object = Object.assign(
                {},
                column.style,
                {width: this.calcColumnWidth(column.width)} );

            return styles;
        }

        getColumnData(column: string): object {
            if( this.data.data.hasOwnProperty(column) ) {
                return this.data.data[column];
            }
            return null;
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


