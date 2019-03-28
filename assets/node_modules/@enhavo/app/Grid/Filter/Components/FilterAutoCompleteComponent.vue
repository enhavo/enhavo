<template>
    <div v-bind:class="name">
        <input type="checkbox" id="checkbox" v-model="value">
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop, Watch} from "vue-property-decorator";
    import AbstractFilter from "@enhavo/app/Grid/Filter/Model/AbstractFilter";
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import IndexApplication from "@enhavo/app/Index/IndexApplication";

    @Component
    export default class FilterAutoCompleteComponent extends Vue
    {
        name: string = 'filter-autocomplete';

        @Prop()
        data: AbstractFilter;

        @Watch('data.value', { immediate: false })
        change() {
            const application = <IndexApplication>ApplicationBag.getApplication();
            application.getFilterManager().change(this.data);
        }
    }
</script>

<style lang="scss" scoped>
    .view-table-filter-boolean { 
        background-color: chocolate;

        span {
            cursor: pointer;
        }
    }
</style>






