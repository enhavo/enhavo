<template>
    <div class="view-table-filter-search wide view-table-filter">
        <span class="label">{{ data.label }}</span>
        <div class="multi-input-container">
            <input @keyup="keyup" type="text" v-model="data.value.from" :placeholder="data.labelFrom" :class="['filter-form-field', {'has-value': getHasFromValue}]">
            <div class="separator">-</div>
            <input @keyup="keyup" type="text" v-model="data.value.to" :placeholder="data.labelTo" :class="['filter-form-field', {'has-value': getHasToValue}]">
        </div>
    </div>
</template>

<script setup lang="ts">
import {BetweenFilter} from "@enhavo/app/filter/model/BetweenFilter";

const emit = defineEmits(['apply']);

const props = defineProps<{
    data: BetweenFilter
}>()

function getHasFromValue(): boolean 
{
    if (props.data.value.from == "") {
        return false;
    } else if (props.data.value.from == null) {
        return false;
    }

    return true;
}

function getHasToValue(): boolean 
{
    if (props.data.value.to == "") {
        return false;
    } else if (props.data.value.to == null) {
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





