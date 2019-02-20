<template>
    <div class="view-table-col-sub">
        <template v-for="row in rows">
            <component 
                class="view-table-col-child" 
                v-bind:is="row.component" 
                v-bind:key="row.key"
                v-bind:data="getRowData(row.key)"></component>
        </template>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import dispatcher from "../../ViewStack/dispatcher";
    import CreateEvent from '../../ViewStack/Event/CreateEvent';

    @Component
    export default class ColumnDate extends Vue {
        name: string = 'view-table-col-date';

        @Prop()
        data: any;

        @Prop()
        column: any;

        get rows(): object {
            if( this.column.hasOwnProperty('rows') ) {
                return this.column['rows'];
            }
            return null;
        }

        getRowData(row: string): any {
            if( this.data.hasOwnProperty(row) ) { // TODO check if clause
                return this.data[row];
            }
            return null;
        }
    }
</script>

<style lang="scss" scoped>
    .view-table-col-sub { 
        color: #FFF; background-color: seagreen;
    }
</style>






