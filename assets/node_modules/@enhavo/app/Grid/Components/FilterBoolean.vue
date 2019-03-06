<template>
    <div class="view-table-filter-boolean">
        <h1 v-on:click="addFilter">Bool: {{ label }}</h1>
        <h1 v-on:click="removeFilter">Bool remove: {{ label }}</h1>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";

    @Component
    export default class FilterBoolean extends Vue {
        name: string = 'view-table-filter-boolean';

        @Prop()
        id: string;

        @Prop()
        label: string;

        @Prop()
        filter: Object;

        get handleFilter(): any {
            if( this.filter && this.filter.hasOwnProperty(this.id) ) {
                return this.filter[this.id]; 
            }
            return null;
        }
        set handleFilter(value: any) {
            if(value === null || value === false) {
                delete this.filter[this.id];
            } else {
                this.filter[this.id] = value;
            }
        }

        addFilter(): void {
            this.handleFilter = 'test';
        }

        removeFilter(): void {
            this.handleFilter = null;
        }
    }
</script>

<style lang="scss" scoped>
    .view-table-filter-boolean { 
        background-color: chocolate;
    }
</style>






