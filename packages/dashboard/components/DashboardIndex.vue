<template>
    <div class="app-view">
        <div class="dashboard-widgets">
            <template v-if="dashboardManager.widgets" v-for="widget of dashboardManager.widgets">
                <component :is="widget.component" :widget="widget"></component>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {inject} from "vue";
import '@enhavo/app/assets/styles/view.scss';
import '@enhavo/dashboard/assets/styles/dashboard.scss';
import {DashboardManager} from "@enhavo/dashboard/manager/DashboardManager";

const dashboardManager = inject<DashboardManager>('dashboardManager');
dashboardManager.load()
</script>

<style lang="css">
.app-view { height: 100vh }
</style>
