<template>
    <div class="app" v-bind:class="{ 'menu-collapsed':!$menuManager.data.open}">
        <toolbar></toolbar>
        <view-stack-dropdown></view-stack-dropdown>
        <div class="sidebar" v-bind:class="{ 'menu-collapsed':!$menuManager.data.open}">
            <div class="mobile-branding-container" v-bind:style="brandingImageStyles" @click="home"></div>
            <app-menu v-bind:class="{ 'menu-collapsed':!$menuManager.data.open}"></app-menu>
        </div>
        <div class="toolbar-viewstack-container" v-bind:class="{ 'menu-collapsed':!$menuManager.data.open}">
            <view-stack></view-stack>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";
import '@enhavo/app/assets/fonts/enhavo-icons.font'
import '@enhavo/app/assets/styles/app.scss'

@Component()
export default class MainComponent extends Vue
{
    get brandingImageStyles() {
        if(this.$mainApp.data.branding.logo) {
            return {
                backgroundImage: 'url(' + this.$mainApp.data.branding.logo + ')',
            }
        }
        return {};
    }

    home() {
        window.location.href = '/admin/'
    }
}
</script>
