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

        @Watch('value', { immediate: false })
        onValueChanged(newValue: boolean, oldValue: boolean): void {
            this.$emit('filter-change-params', {
                filter: this.id,
                value: newValue
            });

            this.setFilterByValue(newValue);
        }
        
        setFilterByValue(value: any) {
            if(value === null || value === false) {
                delete this.filterBy[this.id];
            } else {
                this.filterBy = Object.assign(this.filterBy, {[this.id]: value});
            }
        }
        
        addFilter(): void {
            this.value = true;
            this.setFilterByValue(true);
        }

        removeFilter(): void {
            this.value = false;
            this.setFilterByValue(false);
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






