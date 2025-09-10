<template>
    <div class="view-table-filter-search wide view-table-filter">
        <span class="label">{{ data.label }}</span>
        <div class="multi-input-container">
            <datepicker
                :format="data.format"
                :locale="data.locale"
                :placeholder="data.labelFrom"
                :enable-time-picker="data.time"
                :clearable="true"
                :auto-apply="!data.time"
                :time-picker-inline="true"
                v-model="valueFrom"
                @update:modelValue="update"
            ></datepicker>
            <div class="separator">-</div>
            <datepicker
                :format="data.format"
                :locale="data.locale"
                :placeholder="data.labelTo"
                :enable-time-picker="data.time"
                :clearable="true"
                :auto-apply="!data.time"
                :time-picker-inline="true"
                v-model="valueTo"
                @update:modelValue="update"
            ></datepicker>
        </div>
    </div>
</template>

<script setup lang="ts">
import {onMounted, watch, ref} from "vue";
import {DateBetweenFilter} from "@enhavo/app/filter/model/DateBetweenFilter";

const props = defineProps<{
    data: DateBetweenFilter
}>()

const valueFrom = ref<Date>(null);
const valueTo = ref<Date>(null);

onMounted(() => {
    valueFrom.value = toDate(props.data.value.from);
    valueTo.value = toDate(props.data.value.to);
})

const valueFromDate = ref(props.data.value.from);
const valueToDate = ref(props.data.value.to);

watch(valueFromDate, (newValue: any) => {
    valueFrom.value = toDate(newValue);
})

watch(valueToDate, (newValue: any) => {
    valueTo.value = toDate(newValue);
})

function update()
{
    if (!props.data.time) {
        valueFrom.value?.setHours(0, 0, 0, 0);
        valueTo.value?.setHours(23, 59, 59, 0);
    }
    props.data.value.from = formatDate(valueFrom.value);
    props.data.value.to = formatDate(valueTo.value);
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
