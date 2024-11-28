<template>
    <div class="dashboard-widget"
         :class="'width-' + widget.width"
    >
        <div class="widget-content">
            <div class="label">{{ widget.label }}</div>
            <div class="value">{{ widget.value }}</div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {NumberDashboardWidget} from "@enhavo/dashboard/dashboard/model/NumberDashboardWidget";

const props = defineProps<{
    widget: NumberDashboardWidget
}>()
</script>
