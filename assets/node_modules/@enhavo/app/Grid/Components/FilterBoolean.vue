<template>
    <div v-bind:class="name">
        <input type="checkbox" id="checkbox" v-model="value">
        <span v-on:click="addFilter">+</span> // <span v-on:click="removeFilter">-</span>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop, Watch} from "vue-property-decorator";

    @Component
    export default class FilterBoolean extends Vue {
        name: string = 'view-table-filter-boolean';

        @Prop()
        id: string;

        @Prop()
        label: string;

        @Prop()
        filter: object;

        @Prop()
        filterBy: object;

        value: boolean = false;

        @Watch('value', { immediate: true, deep: true })
        onValueChanged(newValue: boolean, oldValue: boolean): void {
            this.filter = Object.assign(this.filter, {value: newValue});
        }
        
        setFilterValue(value: any) {
            if(value === null || value === false) {
                this.value = false;
                delete this.filterBy[this.id];
            } else {
                this.value = value;
                this.filterBy = Object.assign(this.filterBy, {[this.id]: value});
            }
        }

        addFilter(): void {
            this.setFilterValue(true);
        }

        removeFilter(): void {
            this.setFilterValue(false);
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






