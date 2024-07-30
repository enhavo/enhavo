<template>
    <div class="view-table-filter-search">
        <span class="label">{{ data.label }}</span>
        <v-select :placeholder="data.label" :options="data.choices" @update:modelValue="change" v-model="data.selected" v-bind:class="[{'has-value': getHasValue()}]"></v-select>
    </div>
</template>

<script setup lang="ts">
import OptionFilter from "@enhavo/app/grid/filter/model/OptionFilter";

const props = defineProps<{
    data: OptionFilter
}>()

const data = props.data;

function change(value: any)
{
    if (value == null) {
        data.value = null;
        return;
    }
    data.value = value.code;
}

function getHasValue(): boolean
{
    return !!data.value;
}
</script>
