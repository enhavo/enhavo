<template>
    <div class="view-table-filter-search">
        <input type="text" v-model="data.value" :placeholder="data.label" v-bind:class="['filter-form-field', {'has-value': hasValue}]">
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import AbstractFilter from "@enhavo/app/Grid/Filter/Model/AbstractFilter";

    @Component
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
    }
</script>





