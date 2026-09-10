<template>
    <div class="frame">
        <slot></slot>
    </div>
</template>

<script lang="ts" setup>
defineProps<{
    frame: Object;
}>();
</script>
