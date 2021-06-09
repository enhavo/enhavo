<template>
    <div class="filter-bar">
        <template v-for="filter in $filterManager.activeFilters">
                <component
                    class="view-table-filter"
                    :is="filter.component"
                    :data="filter"
                    :data-filter="filter.key"
                    @apply="apply()"
                >
                </component>
        </template>
        <div v-if="$filterManager.activeFilters.length > 0">
            <button @click="apply()" class="apply-button"><i class="icon icon-check"></i></button>
            <button @click="reset()" class="reset-button red"><i class="icon icon-close"></i></button>
        </div>
    </div>
</template>

<script lang="ts">
  import { Vue, Component } from "vue-property-decorator";

  @Component()
  export default class FilterBar extends Vue
  {
    calcWidth(filter: any): string {
        return (100 / 12 * filter.width) + '%';
    }

    apply() {
        this.$grid.applyFilter();
    }

    reset() {
        this.$grid.resetFilter();
        this.apply()
    }
  }
</script>