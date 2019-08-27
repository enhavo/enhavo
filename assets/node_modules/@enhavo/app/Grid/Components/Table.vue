<template>
    <div v-bind:class="name">
        <div class="view-table-head">
            <div class="checkbox-container" v-if="batches.length > 0">
                <input type="checkbox" v-on:change="changeSelectAll" :checked="selectAll" />
                <span></span>
            </div>
            <div class="view-table-head-columns">
                <template v-for="column in columns">
                    <div
                        v-if="column.display"
                        v-bind:key="column.key"
                        v-bind:style="getColumnStyle(column)"
                        v-bind:class="['view-table-col', {'sortable': column.sortable}, {'sorted': column.directionDesc !== null}]"
                        v-on:click="changeSortDirection(column)"

                    >
                        {{ column.label }}
                        <i
                            v-if="column.sortable"
                            v-bind:class="['icon', {'icon-arrow_upward': !column.directionDesc}, {'icon-arrow_downward': column.directionDesc}, {'sortable': column.sortable}]"
                            aria-hidden="true">
                        </i>
                    </div>
                </template>
            </div>
        </div>

        <template v-if="!loading">
            <template v-for="row in rows">
                <table-row v-bind:batches="batches" v-bind:columns="columns" v-bind:data="row"></table-row>
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
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import Row from "@enhavo/app/Grid/Column/Components/Row.vue"
    import ColumnInterface from "@enhavo/app/Grid/Column/ColumnInterface"
    import RowData from "@enhavo/app/Grid/Column/RowData"
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import IndexApplication from "@enhavo/app/Index/IndexApplication";
    import * as $ from "jquery";
    const application = <IndexApplication>ApplicationBag.getApplication();

    @Component({
        components: {
            'table-row': Row,
        }
    })
    export default class Table extends Vue {
        name: string = 'view-table';
    
        @Prop()
        columns: Array<ColumnInterface>;

        @Prop()
        rows: Array<RowData>;

        @Prop()
        loading: boolean;

        @Prop()
        selectAll: boolean;

        @Prop()
        batches: Array<object>;

        mounted() {
            $(window).resize(() => {
                application.getGrid().resize();
                this.$forceUpdate();
            });
        }

        calcColumnWidth(parts: number): string {
            let totalWidth = 0;
            for(let column of this.columns) {
                if(column.display) {
                    totalWidth += column.width;
                }
            }
            return (100 / totalWidth * parts) + '%';
        }

        getColumnStyle(column: any): object {
            let styles: object = Object.assign(
                {},
                column.style,
                {width: this.calcColumnWidth(column.width)} );

            return styles;
        }

        changeSelectAll() {
            application.getGrid().changeSelectAll(!this.selectAll);
        }

        changeSortDirection(column: any) {
            application.getGrid().changeSortDirection(column);
        }
    }
</script>






