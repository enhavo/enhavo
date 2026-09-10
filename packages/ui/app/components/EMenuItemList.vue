<template>
    <div class="menu-item-list">
        <div class="menu-item" :data-menu-item="item.key" @click="item.open()">
            {{ item.label }}
        </div>
        <div class="menu-item-list-container" :class="{'is-expanded': item.expanded}">
            <template v-for="item in item.children">
                <component :is="item.component" :item="item"></component>
            </template>
        </div>
    </div>
</template>

<script lang="ts" setup>
defineProps<{
    item: Object;
}>();
</script>
