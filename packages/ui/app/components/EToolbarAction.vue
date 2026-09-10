<template>
    <div class="toolbar-action">
        <div class="icon" :class="action.icon" @click="action.execute">{{ action.label }}</div>
    </div>
</template>

<script lang="ts" setup>
defineProps<{
    action: {
        label: string;
        icon: string;
        execute: () => void;
    };
}>();
</script>
