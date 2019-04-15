<template>
    <div class="toolbar-dropdown" v-click-outside="close" v-bind:class="{'selected': isOpen}">
        <div class="toolbar-dropdown-title" v-on:click="toggle">
            <i class="icon icon-person"></i>
            <i v-bind:class="['open-indicator', 'icon icon-keyboard_arrow_down', {'icon-keyboard_arrow_up': isOpen }]" aria-hidden="true"></i>
        </div>
        <div class="toolbar-dropdown-menu" v-show="isOpen">
            <template v-for="item in data.items">
                <toolbar-dropdown-item v-bind:data="item"></toolbar-dropdown-item>
            </template>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import DropdownItem from "./ToolbarDropdownItem.vue"
import { QuickMenu } from "../QuickMenu"

@Component
export default class ToolbarDropdown extends Vue {
    name: 'toolbar-dropdown';

    @Prop()
    data: QuickMenu;

    isOpen: boolean = false;

    toggle (): void {
        this.isOpen = !this.isOpen;
    }

    close(): void {
        this.isOpen = false;
    }
}

Vue.component('toolbar-dropdown-item', DropdownItem);
</script>






