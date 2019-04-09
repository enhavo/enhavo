<template>
    <div class="toolbar-dropdown">
        <div class="toolbar-dropdown-title" v-on:click="toggle">
            <i class="fa fa-user" aria-hidden="true"></i>
            {{ data.user.name }} 
            <i v-bind:class="['open-indicator', 'fa fa-caret-down', {'fa-flip-vertical': isOpen }]" aria-hidden="true"></i>
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
        console.log('toggle dropdown');
    }
}

Vue.component('toolbar-dropdown-item', DropdownItem);
</script>

<style lang="scss" scoped>
    @import '~@enhavo/app/assets/styles/_variables.scss';
    
    .toolbar-dropdown {background-color:$color2b;position:relative;z-index:1;
        .toolbar-dropdown-title { 
            height: $toolbarHeight; padding: 10px; box-sizing: border-box; display: flex; align-items: center; justify-content: center; cursor: pointer;
            i { margin-right: 10px; margin-left: 10px; }
        }

        .toolbar-dropdown-menu { background-color: inherit; }
    }
</style>






