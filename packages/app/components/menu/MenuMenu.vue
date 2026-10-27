<template>
    <div class="menu style-compact" v-if="menuManager" :class="{ 'menu-collapsed':!menuManager.menuOpen}">
        <template v-for="item in menuManager.menuItems">
            <component class="menu-child" :is="item.component" :data="item"></component>
        </template>
    </div>
</template>

<script setup lang="ts">
import {useAsyncService} from "@enhavo/framework/composables/service";
import {MenuManager} from "../../menu/MenuManager";

const menuManager = useAsyncService<MenuManager>('menuManager');

</script>
