<template>
    <div class="menu style-compact" :class="{ 'menu-collapsed':!menuManager.menuOpen}">
        <template v-for="item in menuManager.menuItems">
            <component class="menu-child" :is="item.component" :data="item"></component>
        </template>
    </div>
</template>

<script setup lang="ts">
import {inject} from "vue";
import {MenuManager} from "../../menu/MenuManager";

const menuManager = inject<MenuManager>('menuManager');

</script>
