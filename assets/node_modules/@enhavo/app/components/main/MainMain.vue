<template>
    <div class="app" v-bind:class="{ 'menu-collapsed':!mainManager.menuOpen}" v-if="!mainManager.loading">
        <toolbar-toolbar
            :primary="mainManager.primaryToolbarWidgets"
            :secondary="mainManager.secondaryToolbarWidgets"
            :menu-open="mainManager.menuOpen"
            :logo="mainManager.branding ? mainManager.branding.logo : null"
            @toogle-menu="mainManager.toogleMenu()"
        />
<!--        <view-stack-dropdown></view-stack-dropdown>-->
        <div class="sidebar" v-bind:class="{ 'menu-collapsed':!mainManager.menuOpen}">
            <div class="mobile-branding-container" v-bind:style="getBrandingImageStyles()" @click="home"></div>
            <menu-menu :items="mainManager.menuItems" v-bind:class="{ 'menu-collapsed':!mainManager.menuOpen}"></menu-menu>
        </div>
<!--        <div class="toolbar-viewstack-container" v-bind:class="{ 'menu-collapsed':!mainManager.menuOpen}">-->
<!--            <view-stack></view-stack>&ndash;&gt;-->
<!--        </div>-->
    </div>
</template>

<script setup lang="ts">
import { inject } from 'vue'
import '@enhavo/app/assets/styles/app.scss'
import {MainManager} from "@enhavo/app/manager/MainManager";

const mainManager = inject<MainManager>('mainManager');

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
