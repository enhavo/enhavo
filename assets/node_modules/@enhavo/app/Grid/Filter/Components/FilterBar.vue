<template>
    <div v-bind:class="name">
        <template v-for="filter in filters">
                <component
                    class="view-table-filter"
                    :is="filter.component"
                    :data="filter"
                    :data-filter="filter.key"
                    @apply="apply()"
                >
                </component>
        </template>
        <button @click="apply()" class="apply-button"><i class="icon icon-check"></i></button>
        <button @click="reset()" class="reset-button red"><i class="icon icon-close"></i></button>
    </div>
</template>

<script lang="ts">
  import { Vue, Component, Prop, Watch } from "vue-property-decorator";
  import ApplicationBag from "@enhavo/app/ApplicationBag";
  import IndexApplication from "@enhavo/app/Index/IndexApplication";
  const application = <IndexApplication>ApplicationBag.getApplication();

  @Component({
      components: application.getFilterRegistry().getComponents()
  })
  export default class FilterBar extends Vue {
    name: string = "filter-bar";
    
    @Prop()
    filters: Array<object>;

    calcWidth(filter: any): string {
        return (100 / 12 * filter.width) + '%';
    }

    apply() {
        const application = <IndexApplication>ApplicationBag.getApplication();
        application.getGrid().applyFilter();
    }

    reset() {
      const application = <IndexApplication>ApplicationBag.getApplication();
      application.getGrid().resetFilter();
    }
  }
</script>