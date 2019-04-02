<template>
    <div v-bind:class="name">
        <div class="view-table-head">
            <input type="checkbox" v-on:click="toggleSelectAll" v-bind:checked="allSelected" />
            <div 
                v-for="column in columns" 
                v-bind:key="column.key"
                v-bind:style="getColumnStyle(column)" 
                v-bind:class="['view-table-col', {'sortable': column.sortable}]"
                v-on:click="clickHeadColumn(column)">
                    {{ column.label }}
                    <i 
                        v-if="isColumnSortVisible(column)" 
                        v-bind:class="['icon', {'icon-keyboard_arrow_up': !sort_direction_desc}, {'icon-keyboard_arrow_down': sort_direction_desc}, {'sortable': column.sortable}]" 
                        aria-hidden="true"></i>
            </div>
        </div>

        <template v-if="!loading">
            <template v-for="row in rows">
                <input type="checkbox" v-model="selected" v-bind:id="row.id" v-bind:data="row.id" v-bind:key="'checkbox-'+row.id" />
                <table-row v-bind:columns="columns" v-bind:data="row.data" v-bind:key="'row-'+row.id"></table-row>
            </template>
        </template>
        <template v-else>
            Fetching data...
        </template>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop, Watch } from "vue-property-decorator";
    import Row from "../Column/Components/Row.vue"
    import axios from 'axios';

    @Component({
        components: {
            'table-row': Row,
        }
    })
    export default class Table extends Vue {
        name: string = 'view-table';
    
        @Prop()
        columns: Array<object>;

        @Prop()
        rows: Array<any>;

        @Prop()
        loading: boolean;

        // batch
        selected: Array<number> = []; // selected items

        mounted() {
            //this.retrieveRowData(this.apiUrl);
        }

        @Watch('sort_column_key')
        @Watch('sort_direction_desc')
        onSortChanged(val: string, oldVal: string) {
            console.log("table: sort changed");
            this.retrieveRowData(this.apiUrl);
        }

        @Watch('selected', { immediate: false })
        onSelectionChanged(newValue: boolean, oldValue: boolean): void {
            console.log("table: selected changed");
        }

        get allSelected(): boolean {
            //return this.selected.length == this.rows.length;
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

        toggleSelectAll(): void {
            // check boxes if some are unchecked
            // if (this.rows.length && !this.allSelected) {
            //     for (let row of this.rows) {
            //         this.selected.push(row.id);
            //     }
            //     return;
            // }

            // remove checked state on all boxes if they are checked
            this.selected.splice(0, this.selected.length);
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






