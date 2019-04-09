<template>
    <div class="toolbar">

        <div class="toolbar-item" @click="$emit('toogle-menu')">
            <div v-if="menu_open" class="menu-toggle">
                <span class="icon icon-chevron_left"></span>
            </div>
            <div v-if="!menu_open" class="menu-toggle">
                <span class="icon icon-chevron_right"></span>
            </div>
        </div>
        <div class="toolbar-item">
            <toolbar-dropdown v-bind:data="quick_menu"></toolbar-dropdown>
        </div>

    </div>

</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import Dropdown from "./ToolbarDropdown.vue"
import QuickMenu from "../QuickMenu"

@Component
export default class Toolbar extends Vue {
    name: 'toolbar';
    
    @Prop()
    quick_menu: QuickMenu;

    @Prop()
    menu_open: boolean;
}

Vue.component('toolbar-dropdown', Dropdown);
</script>





