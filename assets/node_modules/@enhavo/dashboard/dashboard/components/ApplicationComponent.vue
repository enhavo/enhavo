<template>
    <div class="app-view">
        <view-view></view-view>
        <template v-for="widget of widgetManager.widgets">
            <component
                class="widget-container"
                :is="widget.component"
                :data="widget"
            ></component>
        </template>
    </div>
</template>

<script lang="ts">
import {Inject, Options, Vue} from "vue-property-decorator";
import '@enhavo/app/assets/fonts/enhavo-icons.font'
import '@enhavo/app/assets/styles/view.scss';
import DashboardApp from "@enhavo/dashboard/dashboard/DashboardApp";
import WidgetManager from "@enhavo/dashboard/widget/WidgetManager";

@Options({})
export default class ApplicationComponent extends Vue
{
    @Inject()
    dashboard: DashboardApp

    @Inject()
    widgetManager: WidgetManager
}
</script>

<style lang="css">
    .app-view { height: 100vh }
</style>
