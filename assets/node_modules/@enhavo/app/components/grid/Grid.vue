<template>
    <div v-bind:class="['grid', grid.configuration.cssClass]">
        <filter-bar></filter-bar>
        <grid-pagination v-if="grid.hasPagination()"></grid-pagination>
        <grid-table></grid-table>
        <grid-batches v-if="batchManager.hasBatches()"></grid-batches>
        <grid-pagination v-if="grid.hasPagination()"></grid-pagination>
    </div>
</template>

<script setup lang="ts">
import { inject } from 'vue'
import Grid from "@enhavo/app/grid/Grid";
import BatchManager from "@enhavo/app/grid/batch/BatchManager";

const grid = inject<Grid>('grid')
const batchManager = inject<BatchManager>('batchManager')
</script>
