<template>
    <div class="view-table-pagination">
        <div class="pagination-select">
            <div class="label">{{ translator.trans('enhavo_app.grid.label.entry_per_page') }}:</div>
            <v-select v-model="collection.pagination" :options="getOptions()" :clearable="false" :searchable="false" :reduce="value => value.code"></v-select>
        </div>

        <div class="pagination-nav">
            <div v-if="collection.count" v-on:click="clickPrev" v-bind:class="['pagination-nav-item', 'button', 'button--prev', {'disabled': !hasPrevPage()}]">
                <i class="icon icon-navigate_before"></i>
            </div>

            <template v-if="!isFirstSegment()">
                <div class="pagination-nav-item number" v-on:click="clickFirst">1</div>
                <div class="pagination-nav-item spacer">...</div>
            </template>

            <div
                v-for="page in collection.pages"
                v-bind:key="page"
                v-bind:class="['pagination-nav-item', 'number', {active: getCurrentPage() === page}]"
                v-on:click="clickPage(page)">
                {{ page }}
            </div>

            <template v-if="!isLastSegment()">
                <div class="pagination-nav-item spacer">...</div>
                <div class="pagination-nav-item number" v-on:click="clickLast">{{ getLastPage() }}</div>
            </template>

            <div v-if="collection.count" v-on:click="clickNext" v-bind:class="['pagination-nav-item', 'button', 'button--next', {'disabled': !hasNextPage()}]">
                <i class="icon icon-navigate_next"></i>
            </div>
        </div>

    </div>
</template>

<script setup lang="ts">
import { inject, watch, ref } from 'vue'
import Translator from "@enhavo/core/Translator";
import {TableCollection} from "../../collection/model/TableCollection";

const props = defineProps<{
    collection: TableCollection,
}>()

const collection = props.collection;
const translator = inject<Translator>('translator');
const itemsAround: number = 2;
const paginationNumber = ref(collection.page);

watch(paginationNumber, async (value: number) => {
    collection.changePagination(value);
});

function getOptions() 
{
    let steps = [];
    for (let step of collection.paginationSteps) {
        steps.push({
            label: step,
            code: step
        })
    }
    return steps;
}

function getCurrentPage(): number 
{
    return collection.page;
}

function getLastPage(): number 
{
    if (!collection.count || !collection.paginated) {
        return 1;
    }
    return Math.ceil(collection.count/collection.page);
}

function isFirstPage(): boolean 
{
    return getCurrentPage() == 1;
}

function isLastPage(): boolean 
{
    return getCurrentPage() == getLastPage();
}

function hasPrevPage(): boolean 
{
    return getCurrentPage() !== 1;
}

function hasNextPage(): boolean 
{
    return getLastPage() !== getCurrentPage();
}

function getSegmentLength(): number 
{
    return itemsAround * 2 + 1; // 2 items each side plus the current page
}

function isFirstSegment(): boolean 
{
    return getCurrentPage() <= getSegmentLength();
}

function isLastSegment(): boolean 
{
    return getCurrentPage() > (getLastPage() - getSegmentLength());
}

function clickFirst(): void 
{
    clickPage(1);
}

function clickLast(): void 
{
    clickPage(getLastPage());
}

function clickPrev(): void 
{
    let page = getCurrentPage();
    if (page > 1) {
        clickPage(page - 1);
    }
}

function clickNext(): void 
{
    let page = getCurrentPage();
    if (page < getLastPage()) {
        clickPage(page + 1);
    }
}

function clickPage(page: number): void 
{
    collection.changePage(page);
}

</script>
