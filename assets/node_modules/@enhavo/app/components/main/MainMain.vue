<template>
    <div class="app" v-bind:class="{ 'menu-collapsed':!menuManager.menuOpen}" v-if="!mainManager.loading">
        <toolbar-toolbar
            :primary="mainManager.primaryToolbarWidgets"
            :secondary="mainManager.secondaryToolbarWidgets"
            :menu-open="menuManager.menuOpen"
            :logo="mainManager.branding ? mainManager.branding.logo : null"
            @toogle-menu="menuManager.toogleMenu()"
        />
<!--        <frame-dropdown></frame-dropdown>-->
        <div class="sidebar" v-bind:class="{ 'menu-collapsed':!menuManager.menuOpen}">
            <div class="mobile-branding-container" v-bind:style="getBrandingImageStyles()" @click="home"></div>
            <menu-menu></menu-menu>
        </div>
        <div class="toolbar-viewstack-container" v-bind:class="{ 'menu-collapsed':!menuManager.menuOpen}">
            <frame-stack></frame-stack>
        </div>
    </div>
</template>

<script setup lang="ts">
import { inject } from 'vue'
import '@enhavo/app/assets/styles/app.scss'
import {MainManager} from "@enhavo/app/manager/MainManager";
import {MenuManager} from "../../menu/MenuManager";

const mainManager = inject<MainManager>('mainManager');
const menuManager = inject<MenuManager>('menuManager');

mainManager.load();

function getBrandingImageStyles()
{
    if (mainManager.branding && mainManager.branding.logo) {
        return {
            backgroundImage: 'url(' + mainManager.branding.logo + ')',
        }
    }
    return {};
}

function home()
{
    window.location.href = '/admin/'
}

</script>
