<template>
    <div class="app" v-bind:class="{ 'menu-collapsed':!menuManager.data.open}">
        <toolbar></toolbar>
        <view-stack-dropdown></view-stack-dropdown>
        <div class="sidebar" v-bind:class="{ 'menu-collapsed':!menuManager.data.open}">
            <div class="mobile-branding-container" v-bind:style="getBrandingImageStyles()" @click="home"></div>
            <app-menu v-bind:class="{ 'menu-collapsed':!menuManager.data.open}"></app-menu>
        </div>
        <div class="toolbar-viewstack-container" v-bind:class="{ 'menu-collapsed':!menuManager.data.open}">
            <view-stack></view-stack>
        </div>
    </div>
</template>

<script setup lang="ts">
import { inject } from 'vue'
import '@enhavo/app/assets/styles/app.scss'
import MainApp from "@enhavo/app/main/MainApp";
import MenuManager from "@enhavo/app/menu/MenuManager";

const mainApp = inject<MainApp>('mainApp');
const menuManager = inject<MenuManager>('menuManager');

function getBrandingImageStyles()
{
    if (mainApp.data.branding.logo) {
        return {
            backgroundImage: 'url(' + mainApp.data.branding.logo + ')',
        }
    }
    return {};
}

function home()
{
    window.location.href = '/admin/'
}

</script>
