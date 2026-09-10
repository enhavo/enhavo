<template>
    <div class="toolbar-brand" :class="{'is-collapsed': collapsed}">
        Brand
    </div>
</template>

<script lang="ts" setup>
withDefaults(defineProps<{
    collapsed?: boolean
}>(), {
    collapsed: false
})
</script>
