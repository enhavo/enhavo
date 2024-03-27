<template>
    <div class="view-table-filter-search wide">
        <span class="label">{{ data.label }}</span>
        <div class="multi-input-container">
            <datepicker :typeable="true" :inputFormat="data.format" :locale="getLocale()" :placeholder="data.labelFrom" v-model="valueFrom" @update:modelValue="update"></datepicker>
            <div class="separator">-</div>
            <datepicker :typeable="true" :inputFormat="data.format" :locale="getLocale()" :placeholder="data.labelTo" v-model="valueTo" @update:modelValue="update"></datepicker>
        </div>
    </div>
</template>

<script setup lang="ts">
import {onMounted, watch, ref} from "vue";
import {de, enUS} from 'date-fns/locale';
import DateBetweenFilter from "@enhavo/app/grid/filter/model/DateBetweenFilter";

const props = defineProps<{
    data: DateBetweenFilter
}>()

const data = props.data;
let valueFrom: Date = null;
let valueTo: Date = null;

onMounted(() => {
    valueFrom = toDate(data.value.from);
    valueTo = toDate(data.value.to);
})

const valueFromDate = ref(data.value.from);
const valueToDate = ref(data.value.to);

watch(valueFromDate, (newValue: any) => {
    valueFrom = toDate(newValue);
})

watch(valueToDate, (newValue: any) => {
    valueTo = toDate(newValue);
})

function update()
{
    data.value.from = formatDate(valueFrom);
    data.value.to = formatDate(valueTo);
}

function getLocale()
{
    if(data.locale == 'de') {
        return de;
    }
    return enUS;
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





