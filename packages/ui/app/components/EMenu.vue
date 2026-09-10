<template>
    <div class="menu" :class="{'is-collapsed': collapsed}">
        <slot></slot>
    </div>
</template>

<script lang="ts" setup>
withDefaults(defineProps<{
    collapsed?: boolean
}>(), {
    collapsed: false
})
</script>
