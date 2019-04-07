<template>
    <div class="toolbar">

        <div class="toolbar-item" @click="$emit('toogle-menu')">
            Hamburger
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
    open: boolean;
}

Vue.component('toolbar-dropdown', Dropdown);
</script>

<style lang="scss" scoped>
    $toolbar-height:57px;

    .toolbar {height: $toolbar-height; background-color: #28434b; color: #9da9ad; font-size: 16px; font-weight: 500; display: flex; justify-content: space-between;
        .toolbar-item { height: 100%; display: flex; align-items: flex-start; justify-content: flex-start; background-color: inherit; }
    }
</style>






