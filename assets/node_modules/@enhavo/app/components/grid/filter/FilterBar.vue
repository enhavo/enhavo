<template>
    <div class="filter-bar">
        <div class="view-table-filter filter-headline" v-if="filterManager.activeFilters.length > 0">{{ translator.trans('enhavo_app.grid.label.filter') }}</div>
        <template v-for="filter in filterManager.activeFilters">
            <component
                class="view-table-filter"
                :is="filter.component"
                :data="filter"
                :data-filter="filter.key"
                @apply="apply()"
            ></component>
        </template>
        <div v-if="filterManager.activeFilters.length > 0" class="filter-buttons">
            <button @click="apply()" class="apply-button"><i class="icon icon-check"></i></button>
            <button @click="reset()" class="reset-button red"><i class="icon icon-close"></i></button>
        </div>
    </div>
</template>

<script setup lang="ts">
import {inject} from "vue";
import Grid from "@enhavo/app/grid/Grid";
import FilterManager from "@enhavo/app/grid/filter/FilterManager";
import Translator from "@enhavo/core/Translator";

const grid = inject<Grid>('grid');
const filterManager = inject<FilterManager>('filterManager');
const translator = inject<Translator>('translator');


function apply()
{
    grid.applyFilter();
}

function reset()
{
    grid.resetFilter();
    apply()
}
</script>