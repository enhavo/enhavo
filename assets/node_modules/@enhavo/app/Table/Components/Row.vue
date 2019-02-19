<template>
    <div class="view-table-row" @click="open()">

        <template v-for="column in columns">
            <component class="view-table-col" 
                v-bind:is="column.component" 
                v-bind:key="column.key" 
                v-bind:width="calculateWidth(column.width)"
                v-bind:rows="column.rows"
                v-bind:data="getColumnData(column.key)"
            ></component>
        </template>

    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import dispatcher from "../../ViewStack/dispatcher";
    import CreateEvent from '../../ViewStack/Event/CreateEvent';
    import ColumnText from "./ColumnText.vue"
    import ColumnDate from "./ColumnDate.vue"
    import ColumnSub from "./ColumnSub.vue"

    @Component
    export default class Row extends Vue {
        name: string = 'view-table-row';
    
        @Prop()
        columns: Array<object>;

        @Prop()
        data: any;
    
        open() {
            dispatcher.dispatch(new CreateEvent({
                label: 'table',
                component: 'iframe-view',
                url: '/admin/view'
            }));
        }

        calculateWidth(parts: number): string {
            return (100 / 12 * parts) + '%';
        }

        getColumnData(column: string): any {
            if( this.data.hasOwnProperty(column) ) {
                return this.data[column];
            } else {
                return null;
            }
        }
    }


    Vue.component('view-table-col-text', ColumnText);
    Vue.component('view-table-col-date', ColumnDate);
    Vue.component('view-table-col-sub', ColumnSub);
</script>

<style lang="scss" scoped>
    .view-table-row { 
        margin-top: 10px; margin-bottom: 10px; color: #FFF; background-color: midnightblue; display: flex;
    }
</style>






