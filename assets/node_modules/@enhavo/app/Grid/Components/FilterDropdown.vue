<template>
    <div v-bind:class="name">
        <select v-model="value" v-bind:class="['filter-form-field', {'has-value': hasValue}]">
            <option v-if="placeholder" value="" v-bind:checked="!hasValue" v-bind:disabled="hasValue">{{ placeholder }}</option>
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
             this.$emit('filter-change-params', {
                filter: this.id,
                value: newValue
            });

            this.setFilterByValue(newValue);
        }

        get hasValue(): boolean {
            return this.value.length ? true : false;
        }

        get choices(): Array<string> {
            return (this.filter && this.filter['choices']) ? this.filter['choices'] : null;
        }

        get placeholder(): string {
            return (this.filter && this.filter['placeholder']) ? this.filter['placeholder'] : null;
        }
        
        setFilterByValue(value: any) {
            if(!value || !value.length) {
                this.$delete(this.filterBy, this.id);
                return; 
            }
            
            this.$set(this.filterBy, this.id, value);
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






