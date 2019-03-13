<template>
    <div class="view-table-filter-boolean">
        <input type="checkbox" id="checkbox" v-model="value">
        <span v-on:click="addFilter">+</span> // <span v-on:click="removeFilter">-</span>
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

        value: boolean = false;

        // MA@2019-03-13: not responsive working right now
        get handleFilter(): any {
            if( this.filter && this.filter.hasOwnProperty(this.id) ) {
                return this.filter[this.id]; 
            }
            return null;
        }
        set handleFilter(value: any) {
            if(value === null || value === false) {
                this.value = false;
                delete this.filter[this.id];
            } else {
                this.value = value;
                this.filter = Object.assign(this.filter, {[this.id]: value});
            }
        }

        addFilter(): void {
            this.handleFilter = true;
        }

        removeFilter(): void {
            this.handleFilter = false;
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






