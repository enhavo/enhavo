<template>
    <div class="view-table-filters">

        <template v-for="filter in filters">
            <component 
                class="view-table-filter" 
                v-bind:is="filter.component" 
                v-bind:key="filter.key" 
                v-bind:label="filter.label" 
                v-bind:id="filter.key" 
                v-bind:filter="filterBy"
                v-bind:style="getFilterStyle(filter)"></component>
        </template>

    </div>
</template>

<script lang="ts">
  import { Vue, Component, Prop, Watch } from "vue-property-decorator";
  import FilterSearch from "./FilterSearch.vue"
  import FilterBoolean from "./FilterBoolean.vue"

  Vue.component('view-table-filter-search', FilterSearch);
  Vue.component('view-table-filter-boolean', FilterBoolean);

  @Component
  export default class FilterBar extends Vue {
    name: string = "view-table-filterbar";
    
    @Prop()
    filters: Array<object>;

    @Prop()
    filterBy: Object;

    calcWidth(parts: number): string {
        return (100 / 12 * parts) + '%';
    }

    getFilterStyle(filter: any): object {
        let styles: object = Object.assign( 
            {}, 
            filter.style, 
            {width: this.calcWidth(filter.width)} );

        return styles;
    }
  }
</script>

<style scoped>

</style>