<template>
    <div class="app" v-bind:class="{ 'menu-collapsed':!menuManager.data.open}">
        <toolbar></toolbar>
        <view-stack-dropdown></view-stack-dropdown>
        <div class="sidebar" v-bind:class="{ 'menu-collapsed':!menuManager.data.open}">
            <div class="mobile-branding-container" v-bind:style="brandingImageStyles" @click="home"></div>
            <app-menu v-bind:class="{ 'menu-collapsed':!menuManager.data.open}"></app-menu>
        </div>
        <div class="toolbar-viewstack-container" v-bind:class="{ 'menu-collapsed':!menuManager.data.open}">
            <view-stack></view-stack>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Options, Inject } from "vue-property-decorator";
import '@enhavo/app/assets/fonts/enhavo-icons.font'
import '@enhavo/app/assets/styles/app.scss'
import MainApp from "@enhavo/app/main/MainApp";
import MenuManager from "@enhavo/app/menu/MenuManager";

@Options({})
export default class extends Vue
{
    @Inject()
    mainApp: MainApp

    @Inject()
    menuManager: MenuManager

    get brandingImageStyles() {
        if (this.mainApp.data.branding.logo) {
            return {
                backgroundImage: 'url(' + this.mainApp.data.branding.logo + ')',
            }
        }
        return {};
    }

    home() {
        window.location.href = '/admin/'
    }
}
</script>
