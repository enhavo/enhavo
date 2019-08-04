<template>
    <div class="app-toolbar" v-bind:class="{ 'collapsed':!menu_open}">
        <div class="toolbar-item">
            <div class="branding-container" v-bind:style="brandingImageStyles" @click="home"></div>
        </div>
        <div class="toolbar-item" @click="$emit('toogle-menu')">
            <div v-if="menu_open" class="menu-toggle">
                <span class="icon icon-chevron_left"></span>
            </div>
            <div v-if="!menu_open" class="menu-toggle">
                <span class="icon icon-chevron_right"></span>
            </div>
        </div>
        <div class="toolbar-item right">
            <toolbar-dropdown></toolbar-dropdown>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import Dropdown from "@enhavo/app/Toolbar/Components/ToolbarDropdown.vue"
import Branding from "@enhavo/app/Main/Branding";

@Component({
    components: {'toolbar-dropdown': Dropdown}
})

export default class Toolbar extends Vue {
    name: 'toolbar';

    @Prop()
    branding: Branding;

    @Prop()
    menu_open: boolean;

    get brandingImageStyles() {
        if(this.branding.logo) {
            return {
                backgroundImage: 'url(' + this.branding.logo + ')',
            }
        }
        return {};
    }

    home() {
        window.location.href = '/admin/'
    }
}
</script>





