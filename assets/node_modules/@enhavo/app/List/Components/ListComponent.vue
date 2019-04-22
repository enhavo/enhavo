<template>
    <div class="grid">
        <div class="view-table">
            <div class="view-table-head">
                <div class="view-table-head-columns">
                    <div
                        v-for="column in columns"
                        v-bind:key="column.key"
                        v-bind:style="getColumnStyle(column)"
                        class="view-table-col"
                        >
                        {{ column.label }}
                    </div>
                </div>
            </div>

            <template v-if="!loading">
                <template v-for="item in items">
                    <list-item v-bind:columns="columns" v-bind:data="item"></list-item>
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
    import Item from "@enhavo/app/List/Components/ItemComponent.vue";

    Vue.component('list-item', Item);

    @Component()
    export default class ListView extends Vue {
        name = 'list-list';

        @Prop()
        columns: Array<object>;

        @Prop()
        items: Array<object>;

        @Prop()
        loading: boolean;

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
    }
</script>


