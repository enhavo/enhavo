<template>
    <div class="view-table">
        <template v-for="entry in apiData">
            <view-table-row v-bind:columns="columns" v-bind:data="entry" v-bind:key="entry.id"></view-table-row>
        </template>

        <button v-on:click="getTableData">Get Data from API</button>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import Row from "./Row.vue"
    import axios from 'axios';

    @Component
    export default class Table extends Vue {
        name: string = 'view-table';
    
        @Prop()
        columns: any;

        apiData: any = [];
        apiUrl: string = '/admin/table';
    
        get columnLabels(): Array<string> {
            let labels = [];

            for (let column of this.columns) {
                labels.push(column.label);
            }

            return labels;
        }

        getTableData(): Array<object> {

            axios
                .get(this.apiUrl)
                .then(response => (
                    this.apiData = response.data
                ))

            return this.apiData;
        }

        
    }

    Vue.component('view-table-row', Row);
</script>

<style lang="scss" scoped>
.view-table {
    width: 100%; padding: 15px; background-color: darkseagreen;
}
</style>






