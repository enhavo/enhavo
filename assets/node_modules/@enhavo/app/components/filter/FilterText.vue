<template>
    <div class="view-table-filter-search view-table-filter">
        <span class="label">{{ data.label }}</span>
        <input @keyup="keyup" type="text" v-model="data.value" :class="['filter-form-field', {'has-value': getHasValue()}]">
    </div>
</template>

<script setup lang="ts">
import {TextFilter} from "@enhavo/app/filter/model/TextFilter";

const props = defineProps<{
    data: TextFilter
}>()


const emit = defineEmits(['apply']);

function getHasValue(): boolean
{
    if (props.data.value == "") {
        return false;
    } else if (props.data.value == null) {
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
