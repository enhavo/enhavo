<template>
    <div class="view-table-filter">
        <span class="label">{{ data.label }}</span>
        <div class="checkbox-container">
            <input type="checkbox" @change="change" :checked="data.value" />
            <span></span>
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
