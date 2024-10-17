<template>
    <div class="view-table-filter-search">
        <span class="label">&nbsp;</span>
        <div class="checkbox-container">
            <input type="checkbox" @change="change" :checked="data.value" />
            <span></span>
            <div class="label">{{ data.label }}</div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {FilterInterface} from "@enhavo/app/filter/FilterInterface";

const props = defineProps<{
    data: FilterInterface
}>()

function change()
{
    props.data.value = !props.data.value;
}
</script>
