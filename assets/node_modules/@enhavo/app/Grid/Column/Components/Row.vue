<template>
    <div class="view-table-row" @click="open()">
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
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import CreateEvent from "@enhavo/app/ViewStack/Event/CreateEvent";
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import IndexApplication from "@enhavo/app/Index/IndexApplication";
    const application = <IndexApplication>ApplicationBag.getApplication();

    @Component({
        components: application.getColumnRegistry().getComponents()
    })
    export default class Row extends Vue {
        name: string = 'table-row';
    
        @Prop()
        columns: Array<object>;

        @Prop()
        data: any;
    
        open() {
            application.getEventDispatcher().dispatch(new CreateEvent({
                label: 'table',
                component: 'iframe-view',
                url: '/admin/view'
            }));
        }

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

        getColumnData(column: string): object {
            if( this.data.hasOwnProperty(column) ) {
                return this.data[column];
            }
            return null;
        }
    }
</script>

<style lang="scss" scoped>
    .view-table-row { 
        margin-top: 10px; margin-bottom: 10px; color: #FFF; background-color: lightgray; display: flex;
    }
</style>






