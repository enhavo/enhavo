<template>
    <div v-bind:class="name">
        <div class="view-table-head">
            <div class="checkbox-container">
                <input v-if="batches.length > 0" type="checkbox" v-on:change="changeSelectAll" :checked="selectAll" />
                <span></span>
            </div>
            <div class="view-table-head-columns">
                <div
                    v-for="column in columns"
                    v-bind:key="column.key"
                    v-bind:style="getColumnStyle(column)"
                    v-bind:class="['view-table-col', {'sortable': column.sortable}]"
                    v-on:click="changeSortDirection(column)">
                        {{ column.label }}
                        <i
                            v-if="column.sortable && column.directionDesc !== null"
                            v-bind:class="['icon', {'icon-keyboard_arrow_up': !column.directionDesc}, {'icon-keyboard_arrow_down': column.directionDesc}, {'sortable': column.sortable}]"
                            aria-hidden="true">
                        </i>
                </div>
            </div>
        </div>


        <template v-if="!loading">
            <template v-for="row in rows">
                <table-row v-bind:batches="batches"  v-bind:columns="columns" v-bind:data="row"></table-row>
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
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import Row from "@enhavo/app/Grid/Column/Components/Row.vue"
    import ColumnInterface from "@enhavo/app/Grid/Column/ColumnInterface"
    import RowData from "@enhavo/app/Grid/Column/RowData"
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import IndexApplication from "@enhavo/app/Index/IndexApplication";
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

        calcColumnWidth(parts: number): string {
            return (100 / 12 * parts) + '%';
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






