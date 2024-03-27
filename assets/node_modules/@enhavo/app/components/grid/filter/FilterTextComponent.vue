<template>
    <div class="view-table-filter-search">
        <span class="label">{{ data.label }}</span>
        <input @keyup="keyup" type="text" v-model="data.value" :class="['filter-form-field', {'has-value': getHasValue()}]">
    </div>
</template>

<script setup lang="ts">
import AbstractFilter from "@enhavo/app/grid/filter/model/AbstractFilter";

const props = defineProps<{
    data: AbstractFilter
}>()

const data = props.data;

const emit = defineEmits(['apply']);

function getHasValue(): boolean
{
    if (data.value == "") {
        return false;
    } else if (data.value == null) {
        return false;
    }

    return true;
}

function keyup(event: KeyboardEvent)
{
    if (event.keyCode == 13) {
        emit('apply')
    }
}
</script>
