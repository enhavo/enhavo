<template>
    <div class="app-toolbar" v-bind:class="{ 'menu-collapsed':!menu_open}">
        <div class="toolbar-item branding">
            <div class="branding-container" v-bind:style="brandingImageStyles" @click="home"></div>
        </div>

        <div class="toolbar-item" @click="$emit('toogle-menu')">
            <div v-if="menu_open" class="menu-toggle">
                <span class="icon icon-chevron_left"></span>
            </div>
            <div v-if="!menu_open" class="menu-toggle">
                <span class="icon icon-chevron_right"></span>
            </div>
            <div v-if="menu_open" class="mobile-menu-toggle">
                <span class="icon icon-close"></span>
            </div>
            <div v-if="!menu_open" class="mobile-menu-toggle">
                <span class="icon icon-menu"></span>
            </div>
        </div>

        <div class="toolbar-item left">
            <template v-for="widget in toolbar.primaryWidgets">
                <component class="widget-container" v-bind:is="widget.component" v-bind:data="widget"></component>
            </template>
        </div>

        <div class="toolbar-item right">
            <template v-for="widget in toolbar.secondaryWidgets">
                <component class="widget-container" v-bind:is="widget.component" v-bind:data="widget"></component>
            </template>
        </div>
    </div>
</template>

<script lang="ts">

import { Vue, Component, Prop } from "vue-property-decorator";
import Branding from "@enhavo/app/Main/Branding";
import ToolbarData from "@enhavo/app/Toolbar/ToolbarData";
import * as URI from 'urijs';
import ApplicationBag from "@enhavo/app/ApplicationBag";
import WidgetAwareApplication from "@enhavo/app/Toolbar/Widget/WidgetAwareApplication";
let application = <WidgetAwareApplication>ApplicationBag.getApplication();

@Component({
    components: application.getWidgetRegistry().getComponents()
})
export default class Toolbar extends Vue {
    name: 'toolbar';

    @Prop()
    branding: Branding;

    @Prop()
    menu_open: boolean;

    @Prop()
    toolbar: ToolbarData;

    get brandingImageStyles() {
        if(this.branding.logo) {
            return {
                backgroundImage: 'url(' + this.branding.logo + ')',
            }
        }
        return {};
    }

    home() {
        let uri = new URI(window.location.href);
        window.location.href = uri.path()
    }
}
</script>





