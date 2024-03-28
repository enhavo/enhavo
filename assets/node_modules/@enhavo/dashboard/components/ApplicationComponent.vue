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

<script setup lang="ts">
import {inject} from "vue";
import '@enhavo/app/assets/fonts/enhavo-icons.font'
import '@enhavo/app/assets/styles/view.scss';
import WidgetManager from "@enhavo/dashboard/widget/WidgetManager";

const widgetManager = inject<WidgetManager>('widgetManager');
</script>

<style lang="css">
    .app-view { height: 100vh }
</style>
