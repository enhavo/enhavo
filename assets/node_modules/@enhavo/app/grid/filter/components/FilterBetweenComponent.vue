<template>
    <div class="view-table-filter-search">
        <input @keyup="keyup" type="text" v-model="data.value.from" :placeholder="data.labelFrom" :class="['filter-form-field', {'has-value': hasFromValue}]">
        <input @keyup="keyup" type="text" v-model="data.value.to" :placeholder="data.labelTo" :class="['filter-form-field', {'has-value': hasToValue}]">
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import BetweenFilter from "@enhavo/app/grid/filter/model/BetweenFilter";

@Options({})
export default class FilterTextComponent extends Vue {
    name: string = 'filter-between';

    @Prop()
    data: BetweenFilter;

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
            this.$emit('apply')
        }
    }
}
</script>





