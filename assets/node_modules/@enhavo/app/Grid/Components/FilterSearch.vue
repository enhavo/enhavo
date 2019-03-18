<template>
    <div class="view-table-filter-search">
        <input v-model="value" v-bind:placeholder="placeholder" v-bind:class="['filter-form-field', {'has-value': hasValue}]">
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop, Watch} from "vue-property-decorator";

    @Component
    export default class FilterSearch extends Vue {
        name: string = 'view-table-filter-search';

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

        get placeholder(): string {
            return (this.filter && this.filter['placeholder']) ? this.filter['placeholder'] : null;
        }
        
        setFilterByValue(value: any) {
            if(value === null || value === '') {
                delete this.filterBy[this.id];
            } else {
                this.filterBy = Object.assign(this.filterBy, {[this.id]: value});
            }
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






