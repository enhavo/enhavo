<template>
    <div class="menu style-compact">
        <template v-for="item in menuManager.data.items">
            <component class="menu-child" v-bind:is="item.component" v-bind:data="item"></component>
        </template>
    </div>
</template>

<script setup lang="ts">
import { inject } from 'vue'
import MenuManager from "@enhavo/app/menu/MenuManager";

const menuManager = inject<MenuManager>('menuManager')
</script>
