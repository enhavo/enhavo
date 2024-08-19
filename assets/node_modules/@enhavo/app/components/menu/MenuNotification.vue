<template>
    <span v-bind:class="['menu-notification', getCSSClass()]">
        {{ getLabel() }}
    </span>
</template>

<script setup lang="ts">
const props = defineProps<{
    data: Object
}>()

const data = props.data;

function getLabel(): string
{
    return (data && data.label) ? data.label : '';
}

function getCSSClass(): string
{
    return (data && data.class) ? data.class : '';
}
</script>

<style lang="scss" scoped>
    .menu-notification { 
        background-color: gold;
    }
</style>






