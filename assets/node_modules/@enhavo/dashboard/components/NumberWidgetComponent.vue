<template>
    <div>
        <div class="label">{{ data.label }}</div>
        <div class="value">{{ data.value }}</div>
    </div>
</template>

<script setup lang="ts">
import NumberWidget from "@enhavo/dashboard/widget/model/NumberWidget";

const props = defineProps<{
    data: NumberWidget
}>()
</script>
