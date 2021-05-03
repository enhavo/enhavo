<template>
    <div v-bind:class="['grid', $grid.configuration.cssClass]">
        <filter-bar></filter-bar>
        <grid-pagination v-if="$grid.hasPagination()"></grid-pagination>
        <grid-table></grid-table>
        <grid-batches v-if="$batchManager.hasBatches()"></grid-batches>
        <grid-pagination v-if="$grid.hasPagination()"></grid-pagination>
    </div>
</template>

<script lang="ts">
    import { Vue, Component } from "vue-property-decorator";

    @Component
    export default class Grid extends Vue
    {

    }
</script>
