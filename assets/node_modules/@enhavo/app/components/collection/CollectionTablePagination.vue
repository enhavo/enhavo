<template>
    <div class="view-table-pagination">
        <div class="pagination-select">
            <div class="label">{{ translator.trans('enhavo_app.grid.label.entry_per_page', {}, 'javascript') }}:</div>
            <v-select
                v-model="collection.paginationStep"
                :options="getPaginationStepOptions()"
                :clearable="false"
                :searchable="false"
                :reduce="value => value.code"
                @update:modelValue="changePagination">
            </v-select>
        </div>

        <div class="pagination-nav">
            <div v-if="collection.count" @click="clickPrev" :class="['pagination-nav-item', 'button', 'button--prev', {'disabled': !hasPrevPage()}]">
                <i class="icon icon-navigate_before"></i>
            </div>

            <template v-if="!isFirstSegment()">
                <div class="pagination-nav-item number" @click="clickFirst">1</div>
                <div class="pagination-nav-item spacer">...</div>
            </template>

            <div
                v-for="page in collection.pages"
                :key="page"
                :class="['pagination-nav-item', 'number', {active: getCurrentPage() === page}]"
                @click="clickPage(page)">
                {{ page }}
            </div>

            <template v-if="!isLastSegment()">
                <div class="pagination-nav-item spacer">...</div>
                <div class="pagination-nav-item number" @click="clickLast">{{ getLastPage() }}</div>
            </template>

            <div v-if="collection.count" @click="clickNext" :class="['pagination-nav-item', 'button', 'button--next', {'disabled': !hasNextPage()}]">
                <i class="icon icon-navigate_next"></i>
            </div>
        </div>

    </div>
</template>

<script setup lang="ts">
import {inject} from 'vue'
import {Translator} from "@enhavo/app/translation/Translator";
import {TableCollection} from "../../collection/model/TableCollection";

const props = defineProps<{
    collection: TableCollection,
}>()

const translator = inject<Translator>('translator');
const itemsAround: number = 2;


function changePagination()
{
    props.collection.load();
}

function getPaginationStepOptions()
{
    let steps = [];
    for (let step of props.collection.paginationSteps) {
        steps.push({
            label: step,
            code: step
        })
    }
    return steps;
}

function getCurrentPage(): number 
{
    return props.collection.page;
}

function getLastPage(): number 
{
    if (!props.collection.count || !props.collection.paginated) {
        return 1;
    }
    return Math.ceil(props.collection.count/props.collection.paginationStep);
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
    return itemsAround * 2 + 1;
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
    props.collection.changePage(page);
}

</script>
