<template>
    <div class="filter-bar">
        <div class="view-table-filter filter-headline" v-if="hasActiveFilter(filters)">{{ translator.trans('enhavo_app.grid.label.filter') }}</div>
        <template v-for="filter in filterManager.getActiveFilters(filters)">
            <component
                class="view-table-filter"
                :is="filter.component"
                :data="filter"
                :data-filter="filter.key"
                @apply="apply()"
            ></component>
        </template>
        <div v-if="hasActiveFilter(filters)" class="filter-buttons">
            <button @click="apply()" class="apply-button"><i class="icon icon-check"></i></button>
            <button @click="reset()" class="reset-button red"><i class="icon icon-close"></i></button>
        </div>
    </div>
</template>

<script setup lang="ts">
import {inject} from "vue";
import Translator from "@enhavo/core/Translator";
import {FilterInterface} from "../../filter/FilterInterface";
import {FilterManager} from "../../filter/FilterManager";

const translator = inject<Translator>('translator');
const filterManager = inject<FilterManager>('filterManager');

const props = defineProps<{
    filters: FilterInterface[],
}>()


function apply()
{
    // grid.applyFilter();
}

function reset()
{
    // grid.resetFilter();
    // apply()
}

function hasActiveFilter(filters: FilterInterface[]): boolean
{
    return filterManager.getActiveFilters(filters).length > 0
}
</script>