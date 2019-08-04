<template>
    <div class="view-table-filter-search">
        <v-select :placeholder="data.label" :options="data.choices" @input="change" v-bind:class="[{'has-value': hasValue}]"></v-select>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import AbstractFilter from "@enhavo/app/Grid/Filter/Model/AbstractFilter";

    @Component
    export default class FilterDropdownComponent extends Vue
    {
        name: string = 'filter-dropdown';

        @Prop()
        data: AbstractFilter;

        change(value) {
            if(value == null) {
                this.data.value = null;
                return;
            }
            this.data.value = value.code;
        }

        get hasValue(): boolean {
            return !!this.data.value;
        }
    }
</script>






