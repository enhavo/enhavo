<template>
    <div class="app" :class="{ 'menu-collapsed':!menuManager.menuOpen}" v-if="!mainManager.loading">
        <flash-messages></flash-messages>
        <toolbar-toolbar
            :primary="mainManager.primaryToolbarWidgets"
            :secondary="mainManager.secondaryToolbarWidgets"
            :menu-open="menuManager.menuOpen"
            :logo="mainManager.branding ? mainManager.branding.logo : null"
            @toggle-menu="menuManager.toggleMenu()"
        />
        <frame-stack-mobile-dropdown></frame-stack-mobile-dropdown>
        <div class="sidebar" :class="{ 'menu-collapsed':!menuManager.menuOpen}">
            <div class="mobile-branding-container" :style="getBrandingImageStyles()" @click="home"></div>
            <menu-menu></menu-menu>
        </div>
        <div class="toolbar-viewstack-container" :class="{ 'menu-collapsed':!menuManager.menuOpen}">
            <frame-stack></frame-stack>
        </div>
    </div>
</template>

<script setup lang="ts">
import { inject } from 'vue'
import '@enhavo/app/assets/styles/app.scss'
import {MainManager} from "@enhavo/app/manager/MainManager";
import {MenuManager} from "../../menu/MenuManager";
import FrameStackMobileDropdown from "../frame/FrameStackMobileDropdown.vue";
import FlashMessages from "@enhavo/app/components/flash-message/FlashMessages.vue";

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
