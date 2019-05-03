<template>
    <div class="view-table-row" @click="open()">
        <div class="checkbox-container">
            <input v-if="batches.length > 0" type="checkbox" v-on:change="changeSelect" v-on:click.stop :checked="selected" />
            <span></span>
        </div>
        <div class="view-table-row-columns">
            <template v-for="column in columns">
                <component
                    class="view-table-col"
                    v-if="column.display"
                    v-bind:is="column.component"
                    v-bind:key="column.key"
                    v-bind:column="column"
                    v-bind:style="getColumnStyle(column)"
                    v-bind:data="getColumnData(column.key)"></component>
            </template>
        </div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import RowData from "@enhavo/app/Grid/Column/RowData";
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import IndexApplication from "@enhavo/app/Index/IndexApplication";
    import * as $ from "jquery";
    const application = <IndexApplication>ApplicationBag.getApplication();

    @Component({
        components: application.getColumnRegistry().getComponents()
    })
    export default class Row extends Vue {
        name: string = 'table-row';
    
        @Prop()
        columns: Array<object>;

        @Prop()
        data: RowData;

        @Prop()
        selected: boolean;

        @Prop()
        batches: Array<object>;

        mounted() {
            $(window).resize(() => {
                application.getGrid().resize();
                this.$forceUpdate();
            });
        }

        changeSelect() {
            application.getGrid().changeSelect(this.data, !this.data.selected);
        }

        open() {
            application.getGrid().open(this.data);
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

        getColumnData(column: string): object {
            if( this.data.data.hasOwnProperty(column) ) {
                return this.data.data[column];
            }
            return null;
        }
    }
</script>






