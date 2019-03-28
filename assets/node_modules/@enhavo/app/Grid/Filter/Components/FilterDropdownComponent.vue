<template>
    <div v-bind:class="name">
        <select v-model="data.value" v-bind:class="['filter-form-field', {'has-value': hasValue}]">
            <option v-if="placeholder" value="" v-bind:checked="!hasValue" v-bind:disabled="hasValue">{{ placeholder }}</option>
            <option v-for="(choiceLabel, choiceValue) in choices" v-bind:value="choiceValue" v-bind:key="choiceValue">
                {{ choiceLabel }}
            </option>
        </select>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop, Watch} from "vue-property-decorator";
    import AbstractFilter from "@enhavo/app/Grid/Filter/Model/AbstractFilter";
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import IndexApplication from "@enhavo/app/Index/IndexApplication";

    @Component
    export default class FilterDropdownComponent extends Vue
    {
        name: string = 'filter-dropdown';

        @Prop()
        data: AbstractFilter;

        @Watch('data.value', { immediate: false })
        change() {
            const application = <IndexApplication>ApplicationBag.getApplication();
            application.getFilterManager().change(this.data);
        }

        get hasValue(): boolean {
            return !!this.data.value;
        }

        get choices(): Array<string> {
            return (this.data && this.data['choices']) ? this.data['choices'] : null;
        }

        get placeholder(): string {
            return (this.data && this.data['placeholder']) ? this.data['placeholder'] : null;
        }
    }
</script>

<style lang="scss" scoped>
    .view-table-filter-dropdown { 
        background-color: darkblue;

        .filter-form-field {
            outline: none;
            border: 1px solid darkorange;

            &.has-value {
                border-color: darkgreen;
            }
        }
    }
</style>






