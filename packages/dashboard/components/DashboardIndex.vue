<template>
    <div class="app-view">
        <div class="dashboard-widgets">
            <template v-if="dashboardManager && dashboardManager.widgets" v-for="widget of dashboardManager.widgets">
                <component :is="widget.component" :widget="widget"></component>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {useAsyncService, useWhenReady} from "@enhavo/framework/composables/service";
import {DashboardManager} from "@enhavo/dashboard/manager/DashboardManager";

const dashboardManager = useAsyncService<DashboardManager>('dashboardManager');

useWhenReady([dashboardManager], (dashboardManager) => {
    dashboardManager.load();
});
</script>

<style lang="css">
.app-view { height: 100vh }
</style>
