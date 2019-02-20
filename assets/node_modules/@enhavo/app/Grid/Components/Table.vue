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

    @Component({
        components: {'view-table-row': Row}
    })
    export default class Table extends Vue {
        name: string = 'view-table';
    
        @Prop()
        columns: any;

        loading: boolean = false;
        rows: any = [];
        apiUrl: string = '/admin/table';

        mounted() {
            this.retrieveRowData(this.apiUrl);
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

    }
</script>

<style lang="scss" scoped>
.view-table {
    width: 100%; padding: 15px; background-color: darkseagreen;
}
</style>






