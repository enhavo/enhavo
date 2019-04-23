<template>
    <div v-bind:class="name">
        <template v-for="filter in filters">
            <template>
                <component
                    class="view-table-filter"
                    v-bind:is="filter.component"
                    v-bind:data="filter">
                </component>
            </template>
        </template>
        <button v-on:click="apply()" class="apply-button"><i class="icon icon-refresh"></i></button>
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
  }
</script>