<template>
    <div class="view-table">

        <view-table-filters v-bind:filters="filters" v-bind:filterBy="filter_by"></view-table-filters>

        <view-table-pagination v-bind:page="page"></view-table-pagination>

        <div class="view-table-head">
            <div 
                v-for="column in columns" 
                v-bind:key="column.key" 
                v-bind:style="getColumnStyle(column)" 
                v-bind:class="['view-table-col', {'sortable': column.sortable}]"
                v-on:click="clickHeadColumn(column)">
                {{ column.label }}
                <i v-if="isColumnSortVisible(column)" v-bind:class="['icon', {'icon-keyboard_arrow_up': !sort_direction_desc}, {'icon-keyboard_arrow_down': sort_direction_desc}, {'sortable': column.sortable}]" aria-hidden="true"></i>
            </div>
        </div>

        <template v-if="!loading">
            <template v-for="row in rows">
                <view-table-row v-bind:columns="columns" v-bind:data="row" v-bind:key="row.id"></view-table-row>
            </template>
        </template>
        <template v-else>
            Fetching data...
        </template>

        <view-table-pagination v-bind:page="page"></view-table-pagination>

    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop, Watch } from "vue-property-decorator";
    import Row from "./Row.vue"
    import Pagination from "./Pagination.vue"
    import FilterBar from "./FilterBar.vue"
    import axios from 'axios';

    Vue.component('view-table-filters', FilterBar);
    Vue.component('view-table-row', Row);
    Vue.component('view-table-pagination', Pagination);

    @Component
    export default class Table extends Vue {
        name: string = 'view-table';
    
        @Prop()
        columns: Array<object>;
    
        @Prop()
        page: Array<object>;
    
        @Prop()
        filters: Array<object>;

        loading: boolean = false;
        rows: any = [];
        apiUrl: string = '/admin/table';

        sort_column_key: string = null;
        sort_direction_desc: boolean = false;

        filter_by: object = {};

        mounted() {
            this.retrieveRowData(this.apiUrl);
        }

        @Watch('sort_column_key')
        @Watch('sort_direction_desc')
        onSortChanged(val: string, oldVal: string) {
            this.retrieveRowData(this.apiUrl);
        }

        retrieveRowData(apiUrl: string): void {
            this.loading = true;

            let requestParams = {
                sort_direction: this.sort_direction_desc ? 'DESC' : 'ASC'
            };

            if(this.sort_column_key) {
                requestParams['sort_column_key'] = this.sort_column_key;
            }

            axios
                .get(apiUrl, {params: requestParams})
                // executed on success
                .then(response => {
                    this.rows = response.data
                })
                // executed on error
                .catch(error => {

                })
                // always executed
                .then(() => {
                    this.loading = false;
                })
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

        isColumnSortVisible(column: any): boolean {
            if(column.sortable === true && this.sort_column_key == column.key) {
                return true;
            }
            return false;
        }

        clickHeadColumn(column: any) {
            // revert the sort order if 
            if(this.sort_column_key == column.key) {
                this.sort_direction_desc = !this.sort_direction_desc;
            }

            // click column the first time to sort asc
            if(this.sort_column_key != column.key) {
                this.sort_column_key = column.key;
            }
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






