<template>
    <div class="grid">
        <filter-bar v-if="data.showFilter" v-bind:filters="data.filters"></filter-bar>
        <table-pagination
            v-if="data.paginate"
            v-bind:page="data.page"
            v-bind:count="data.count"
            v-bind:paginationSteps="data.paginationSteps"
            v-bind:pagination="data.pagination"
        ></table-pagination>
        <grid-table
            v-bind:batches="data.batches"
            v-bind:columns="data.columns"
            v-bind:rows="data.rows"
            v-bind:loading="data.loading"
            v-bind:selectAll="data.selectAll"
        ></grid-table>
        <table-batches v-if="data.batches.length > 0" v-bind:value="data.batch" v-bind:batches="data.batches"></table-batches>
        <table-pagination
            v-if="data.paginate"
            v-bind:page="data.page"
            v-bind:count="data.count"
            v-bind:paginationSteps="data.paginationSteps"
            v-bind:pagination="data.pagination"
        ></table-pagination>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import Table from "@enhavo/app/Grid/Components/Table.vue";
    import Pagination from "@enhavo/app/Grid/Components/Pagination.vue";
    import FilterBar from "@enhavo/app/Grid/Filter/Components/FilterBar.vue"
    import Batches from "@enhavo/app/Grid/Components/Batches.vue"
    import GridConfiguration from "@enhavo/app/Grid/GridConfiguration";

    @Component({
        components: {
            'grid-table': Table,
            'filter-bar': FilterBar,
            'table-pagination': Pagination,
            'table-batches': Batches,
        }
    })
    export default class Grid extends Vue {
        name: string = 'view-grid';

        @Prop()
        data: GridConfiguration;
    }
</script>