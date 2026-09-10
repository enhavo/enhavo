-<template>
    <div class="toolbar-action-dropdown">
        <div class="icon" :class="action.icon" @click="action.execute">{{ action.label }}</div>
        <div>
            <slot></slot>
        </div>
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
