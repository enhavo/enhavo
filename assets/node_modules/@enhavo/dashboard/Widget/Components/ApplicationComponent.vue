<template>
    <div class="app-view">
        <view-view :data="view"></view-view>
        <template v-for="widget of widgets">
            <component
                class="widget-container"
                :is="widget.component"
                :data="widget"
            ></component>
        </template>
    </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from "vue-property-decorator";
    import '@enhavo/app/assets/styles/view.scss';
    import ViewData from "@enhavo/app/View/ViewData";
    import ViewComponent from "@enhavo/app/View/Components/ViewComponent";
    import DashboardApplication from "@enhavo/dashboard/DashboardApplication";
    import ApplicationBag from "@enhavo/app/ApplicationBag";

    let application = <DashboardApplication>ApplicationBag.getApplication();
    let viewComponent = {'view-view': ViewComponent};
    let widgetComponents = application.getWidgetRegistry().getComponents();
    let components = {...viewComponent, ...widgetComponents};

    @Component({
        components: components
    })
export default class ApplicationComponent extends Vue
{
    @Prop()
    view: ViewData;

    @Prop()
    widgets: any;
}
</script>

<style lang="css">
    .app-view { height: 100vh }
</style>
