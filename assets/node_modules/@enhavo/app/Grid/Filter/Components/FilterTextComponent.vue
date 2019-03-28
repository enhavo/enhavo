<template>
    <div class="view-table-filter-search">
        <input v-model="data.value" v-bind:placeholder="placeholder" v-bind:class="['filter-form-field', {'has-value': hasValue}]">
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop, Watch } from "vue-property-decorator";
    import AbstractFilter from "@enhavo/app/Grid/Filter/Model/AbstractFilter";
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import IndexApplication from "@enhavo/app/Index/IndexApplication";

    @Component
    export default class FilterTextComponent extends Vue {
        name: string = 'filter-text';

        @Prop()
        data: AbstractFilter;

        @Watch('data.value', { immediate: false })
        change() {
            const application = <IndexApplication>ApplicationBag.getApplication();
            application.getFilterManager().change(this.data);
        }

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

<style lang="scss" scoped>
    .view-table-filter-search { 
        background-color: burlywood;

        .filter-form-field {
            outline: none;
            border: 1px solid darkorange;

            &.has-value {
                border-color: darkgreen;
            }
        }
    }
</style>






