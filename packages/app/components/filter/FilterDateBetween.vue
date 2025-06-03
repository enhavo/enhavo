<template>
    <div class="view-table-filter-search wide view-table-filter">
        <span class="label">{{ data.label }}</span>
        <div class="multi-input-container">
            <datepicker :typeable="true" :inputFormat="data.format" :locale="data.locale" :placeholder="data.labelFrom" v-model="valueFrom" @update:modelValue="update"></datepicker>
            <div class="separator">-</div>
            <datepicker :typeable="true" :inputFormat="data.format" :locale="data.locale" :placeholder="data.labelTo" v-model="valueTo" @update:modelValue="update"></datepicker>
        </div>
    </div>
</template>

<script setup lang="ts">
import {onMounted, watch, ref} from "vue";
import {DateBetweenFilter} from "@enhavo/app/filter/model/DateBetweenFilter";

const props = defineProps<{
    data: DateBetweenFilter
}>()

let valueFrom: Date = null;
let valueTo: Date = null;

onMounted(() => {
    valueFrom = toDate(props.data.value.from);
    valueTo = toDate(props.data.value.to);
})

const valueFromDate = ref(props.data.value.from);
const valueToDate = ref(props.data.value.to);

watch(valueFromDate, (newValue: any) => {
    valueFrom = toDate(newValue);
})

watch(valueToDate, (newValue: any) => {
    valueTo = toDate(newValue);
})

function update()
{
    props.data.value.from = formatDate(valueFrom);
    props.data.value.to = formatDate(valueTo);
}

function formatDate(date: Date): string
{
    if (date === null) {
        return null;
    }
    return date.getFullYear()
        + '-'
        + addLeadingZeroes(date.getMonth() + 1)
        + '-'
        + addLeadingZeroes(date.getDate())
        + ' 00:00:00';
}

function addLeadingZeroes(num: number): string
{
    if (num < 10) {
        return '0' + num;
    }
    return '' + num;
}

function toDate(str: string): Date
{
    if (str === null) {
        return null;
    } else {
        return new Date(str);
    }
}
</script>
