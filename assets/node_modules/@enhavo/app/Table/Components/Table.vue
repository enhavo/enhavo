<template>
    <div class="view-table">
        <template v-if="!loading">
            <template v-for="row in rows">
                <view-table-row v-bind:columns="columns" v-bind:data="row" v-bind:key="row.id"></view-table-row>
            </template>
        </template>
        <template v-else>
            Fetching data...
        </template>
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

        loading: boolean = false;
        rows: any = [];
        apiUrl: string = '/admin/table';

        mounted() {
            this.rows = this.retrieveRowData(this.apiUrl);
        }
    
        get columnLabels(): Array<string> {
            let labels = []

            for (let column of this.columns) {
                labels.push(column.label);
            }

            return labels;
        }

        retrieveRowData(apiUrl: string): void {
            this.loading = true;

            axios
                .get(apiUrl)
                .then(response => {
                    this.rows = response.data
                })
                .catch(error => {
                    
                })
                .then(() => {
                    this.loading = false;
                })
        }
        
    }

    Vue.component('view-table-row', Row);
</script>

<style lang="scss" scoped>
.view-table {
    width: 100%; padding: 15px; background-color: darkseagreen;
}
</style>






