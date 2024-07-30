<template>
    <div class="view-table-filter-search wide">
        <span class="label">{{ data.label }}</span>
        <div class="multi-input-container">
            <input @keyup="keyup" type="text" v-model="data.value.from" :placeholder="data.labelFrom" :class="['filter-form-field', {'has-value': getHasFromValue}]">
            <div class="separator">-</div>
            <input @keyup="keyup" type="text" v-model="data.value.to" :placeholder="data.labelTo" :class="['filter-form-field', {'has-value': getHasToValue}]">
        </div>
    </div>
</template>

<script setup lang="ts">
import BetweenFilter from "@enhavo/app/grid/filter/model/BetweenFilter";

const emit = defineEmits(['apply']);

const props = defineProps<{
    data: BetweenFilter
}>()

const data = props.data;

function getHasFromValue(): boolean 
{
    if (data.value.from == "") {
        return false;
    } else if (data.value.from == null) {
        return false;
    }

    return true;
}

function getHasToValue(): boolean 
{
    if (data.value.to == "") {
        return false;
    } else if (data.value.to == null) {
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





