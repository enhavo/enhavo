<template>
    <div class="menu-item" :data-menu-item="item.key" @click="item.open()">
        {{ item.label }}
    </div>
</template>

<script lang="ts" setup>
defineProps<{
    item: Object;
}>();
</script>
