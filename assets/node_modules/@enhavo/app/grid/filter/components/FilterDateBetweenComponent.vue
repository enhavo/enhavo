<template>
    <div class="view-table-filter-search">
        <datepicker :typeable="true" :inputFormat="data.format" :locale="locale" :placeholder="data.labelFrom" v-model="valueFrom" @update:modelValue="update"></datepicker>
        <datepicker :typeable="true" :inputFormat="data.format" :locale="locale" :placeholder="data.labelTo" v-model="valueTo" @update:modelValue="update"></datepicker>
    </div>
</template>

<script lang="ts">
import {Vue, Options, Prop, Watch} from "vue-property-decorator";
import {de, enUS} from 'date-fns/locale';
import DateBetweenFilter from "@enhavo/app/grid/filter/model/DateBetweenFilter";

@Options({})
export default class FilterTextComponent extends Vue {
    name: string = 'filter-date-between';

    @Prop()
    data: DateBetweenFilter;

    valueFrom: Date = null;
    valueTo: Date = null;

    mounted() {
        this.valueFrom = this.toDate(this.data.value.from);
        this.valueTo = this.toDate(this.data.value.to);
    }

    @Watch('data.value.from')
    valueFromUpdated(newValue: string)
    {
        this.valueFrom = this.toDate(newValue);
    }

    @Watch('data.value.to')
    valueFromTo(newValue: string)
    {
        this.valueTo = this.toDate(newValue);
    }

    update() {
        this.data.value.from = this.formatDate(this.valueFrom);
        this.data.value.to = this.formatDate(this.valueTo);
    }

    get locale() {
        if(this.data.locale == 'de') {
            return de;
        }
        return enUS;
    }

    get hasFromValue(): boolean {
        if(this.data.value.from == "") {
            return false;
        } else if(this.data.value.from == null) {
            return false;
        }

        return true;
    }

    get hasToValue(): boolean {
        if(this.data.value.to == "") {
            return false;
        } else if(this.data.value.to == null) {
            return false;
        }

        return true;
    }

    get placeholder(): string {
        return (this.data && this.data['placeholder']) ? this.data['placeholder'] : null;
    }

    keyup(event: Event) {
        if(event.keyCode == 13) {
            this.$emit('apply');
        }
    }

    private formatDate(date: Date): string
    {
        if (date === null) {
            return null;
        }
        return date.getFullYear()
            + '-'
            + this.addLeadingZeroes(date.getMonth() + 1)
            + '-'
            + this.addLeadingZeroes(date.getDate())
            + ' 00:00:00';
    }

    private addLeadingZeroes(num: number): string
    {
        if (num < 10) {
            return '0' + num;
        }
        return '' + num;
    }

    private toDate(str: string): Date
    {
        if (str === null) {
            return null;
        } else {
            return new Date(str);
        }
    }
}
</script>





