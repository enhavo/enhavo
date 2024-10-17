<template>
    <div class="view-table-filter-search view-table-filter">
        <span class="label">{{ data.label }}</span>
        <v-select :placeholder="data.label" :options="data.choices" @update:modelValue="change" v-model="data.selected" :class="[{'has-value': getHasValue()}]"></v-select>
    </div>
</template>

<script setup lang="ts">
import {OptionFilter} from "@enhavo/app/filter/model/OptionFilter";

const props = defineProps<{
    data: OptionFilter
}>()

function change(value: any)
{
    if (value == null) {
        props.data.value = null;
        return;
    }
    props.data.value = value.code;
}

function getHasValue(): boolean
{
    return !!props.data.value;
}
</script>
