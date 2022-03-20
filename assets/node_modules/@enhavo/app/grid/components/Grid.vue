<template>
    <div v-bind:class="['grid', grid.configuration.cssClass]">
        <filter-bar></filter-bar>
        <grid-pagination v-if="grid.hasPagination()"></grid-pagination>
        <grid-table></grid-table>
        <grid-batches v-if="batchManager.hasBatches()"></grid-batches>
        <grid-pagination v-if="grid.hasPagination()"></grid-pagination>
    </div>
</template>

<script lang="ts">
import { Vue, Options, Inject } from "vue-property-decorator";
import Grid from "@enhavo/app/grid/Grid";
import BatchManager from "@enhavo/app/grid/batch/BatchManager";

@Options({})
export default class extends Vue
{
    @Inject()
    grid: Grid;

    @Inject()
    batchManager: BatchManager;
}
</script>
