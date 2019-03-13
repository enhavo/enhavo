<template>
    <div class="view-table-filter-boolean">
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

        @Watch('value', { immediate: true, deep: true })
        onValueChanged(newValue: boolean, oldValue: boolean): void {
            this.setFilterValue(newValue);
        }

        value: boolean = false;
        
        setFilterValue(value: any) {
            if(value === null || value === false) {
                this.value = false;
                delete this.filter[this.id];
            } else {
                this.value = value;
                this.filter = Object.assign(this.filter, {[this.id]: value});
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






