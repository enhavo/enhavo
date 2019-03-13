<template>
    <div v-bind:class="name">
        <select v-model="value">
            <option v-for="(choiceLabel, choiceValue) in choices" v-bind:value="choiceValue" v-bind:key="choiceValue">
                {{ choiceLabel }}
            </option>
        </select>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop, Watch} from "vue-property-decorator";

    @Component
    export default class FilterDropdown extends Vue {
        name: string = 'view-table-filter-dropdown';

        @Prop()
        id: string;

        @Prop()
        label: string;

        @Prop()
        filter: object;

        @Prop()
        filterBy: object;

        value: string = '';

        @Watch('value', { immediate: false })
        onValueChanged(newValue: string, oldValue: string): void {
            this.filter = Object.assign(this.filter, {value: newValue});
        }

        get choices(): Array<string> {
            return (this.filter && this.filter['choices']) ? this.filter['choices'] : null;
        }
    }
</script>

<style lang="scss" scoped>
    .view-table-filter-search { 
        background-color: burlywood;
    }
</style>






