<template>
    <span :class="['menu-notification', getCSSClass()]">
        {{ getLabel() }}
    </span>
</template>

<script setup lang="ts">
const props = defineProps<{
    data: Object
}>()

function getLabel(): string
{
    return (props.data && props.data.label) ? props.data.label : '';
}

function getCSSClass(): string
{
    return (props.data && props.data.class) ? data.class : '';
}
</script>

<style lang="scss" scoped>
    .menu-notification { 
        background-color: gold;
    }
</style>






