<template>
    <div class="filter-bar">
        <div class="view-table-filter filter-headline" v-if="hasActiveFilter(filters)">{{ translator.trans('enhavo_app.grid.label.filter', {}, 'javascript') }}</div>
        <template v-for="filter in filterManager.getActiveFilters(filters)">
            <div @click="deactivateFilter(filter)">x</div>
            <component
                :is="filter.component"
                :data="filter"
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
import {Translator} from "@enhavo/app/translation/Translator";
import {FilterInterface} from "@enhavo/app/filter/FilterInterface";
import {FilterManager} from "@enhavo/app/filter/FilterManager";

const translator = inject<Translator>('translator');
const filterManager = inject<FilterManager>('filterManager');

const props = defineProps<{
    filters: FilterInterface[],
}>()


const emit = defineEmits(['apply', 'reset']);

function apply()
{
    emit('apply');
}

function reset()
{
    emit('reset');
}

function deactivateFilter(filter: FilterInterface)
{
    filter.reset();
    filter.active = false;
    emit('apply');
}

function hasActiveFilter(filters: FilterInterface[]): boolean
{
    return filterManager.getActiveFilters(filters).length > 0
}
</script>