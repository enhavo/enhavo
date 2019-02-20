<template>
    <view-table></view-table>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import Table from "./Table.vue";
    import axios from 'axios';

    @Component({
        components: {Table}
    })
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
</script>

<style lang="scss" scoped>
.view-table {
    width: 100%; padding: 15px; background-color: darkseagreen;
}
</style>






