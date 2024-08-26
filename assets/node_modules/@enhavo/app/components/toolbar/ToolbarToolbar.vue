<template>
    <div class="app-toolbar" v-bind:class="{ 'menu-collapsed':!menuOpen}">
        <div class="toolbar-item branding">
            <div class="branding-container" v-bind:style="getBrandingImageStyles()" @click="home"></div>
        </div>

        <div class="toolbar-item" @click="emit('toggleMenu')">
            <div v-if="menuOpen" class="menu-toggle">
                <span class="icon icon-chevron_left"></span>
            </div>
            <div v-if="!menuOpen" class="menu-toggle">
                <span class="icon icon-chevron_right"></span>
            </div>
            <div v-if="menuOpen" class="mobile-menu-toggle">
                <span class="icon icon-close"></span>
            </div>
            <div v-if="!menuOpen" class="mobile-menu-toggle">
                <span class="icon icon-menu"></span>
            </div>
        </div>

        <div class="toolbar-item left">
            <template v-for="widget in primary">
                <component class="widget-container" v-bind:is="widget.component" v-bind:data="widget"></component>
            </template>
        </div>

        <div class="toolbar-item right">
            <template v-for="widget in secondary">
                <component class="widget-container" v-bind:is="widget.component" v-bind:data="widget"></component>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {ToolbarWidgetInterface} from "@enhavo/app/toolbar/ToolbarWidgetInterface";

const emit = defineEmits(['toggleMenu']);

const props = defineProps<{
    primary?: ToolbarWidgetInterface[],
    secondary?: ToolbarWidgetInterface[],
    logo?: String,
    menuOpen?: Boolean
}>()

function getBrandingImageStyles()
{
    if (props.logo) {
        return {
            backgroundImage: 'url(' + props.logo + ')',
        }
    }
    return {};
}

function home()
{
    let uri = new URL(window.location.href);
    window.location.href = uri.pathname;
}
</script>
