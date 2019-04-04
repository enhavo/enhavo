<template>
    <div v-bind:class="name">
        <div class="view-table-head">
            <input v-if="batches.length > 0" type="checkbox" v-on:change="changeSelectAll" :checked="selectAll" />
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

        <template v-if="!loading">
            <template v-for="row in rows">
                <table-row v-bind:batches="batches"  v-bind:columns="columns" v-bind:data="row"></table-row>
            </template>
        </template>
        <template v-else>
            Fetching data...
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

        changeSelectAll() {
            application.getGrid().changeSelectAll(!this.selectAll);
        }

        calcWidth(parts: number): string {
            return (100 / 12 * parts) + '%';
        }

        getColumnStyle(column: any): object {
            let styles: object = Object.assign( 
                {}, 
                column.style, 
                {width: this.calcWidth(column.width)} );

            return styles;
        }

        changeSortDirection(column: any) {
            application.getGrid().changeSortDirection(column);
        }
    }
</script>

<style lang="scss">
.view-table-col {
    padding: 10px; margin: 10px; color: #FFF; 
}
</style>

<style lang="scss" scoped>
.view-table {
    width: 100%; padding: 15px; background-color: darkseagreen;

    .view-table-head {
        display: flex; background-color: lightslategrey; margin-bottom: 30px;

        .view-table-col {
            background-color: burlywood;

            &.sortable {
                text-decoration: underline;
                cursor: pointer;
            }
        }
    }
}
</style>






