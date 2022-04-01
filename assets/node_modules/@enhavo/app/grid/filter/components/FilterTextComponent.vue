<template>
    <div class="view-table-filter-search">
        <span class="label">{{ data.label }}</span>
        <input @keyup="keyup" type="text" v-model="data.value" :class="['filter-form-field', {'has-value': hasValue}]">
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import AbstractFilter from "@enhavo/app/grid/filter/model/AbstractFilter";

@Options({})
export default class FilterTextComponent extends Vue {
    name: string = 'filter-text';

    @Prop()
    data: AbstractFilter;

    get hasValue(): boolean {
        if(this.data.value == "") {
            return false;
        } else if(this.data.value == null) {
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





