<template>
    <div class="view-table-filter-search">
        <span class="label">{{ data.label }}</span>
        <div class="checkbox-container">
            <input type="checkbox" v-on:change="change" :checked="data.value" />
            <span></span>
            <div class="label">{{ data.label }}</div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AbstractFilter from "@enhavo/app/grid/filter/model/AbstractFilter";

const props = defineProps<{
    data: AbstractFilter
}>()

function change()
{
    props.data.value = !props.data.value;
}
</script>







