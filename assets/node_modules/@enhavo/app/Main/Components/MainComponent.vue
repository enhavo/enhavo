<template>
    <div class="app" v-bind:class="{ 'menu-collapsed':!menu.open}">
        <toolbar v-bind:menu_open="menu.open" :branding="branding" :toolbar="toolbar" v-on:toogle-menu="toogleMenu"></toolbar>
        <viewstack-dropdown :view_stack="view_stack"></viewstack-dropdown>
        <div class="sidebar" v-bind:class="{ 'menu-collapsed':!menu.open}">
            <div class="mobile-branding-container" v-bind:style="brandingImageStyles" @click="home"></div>
            <app-menu v-bind:menu="menu" v-bind:class="{ 'menu-collapsed':!menu.open}"></app-menu>
        </div>
        <div class="toolbar-viewstack-container" v-bind:class="{ 'menu-collapsed':!menu.open}">
            <view-stack v-bind:data="view_stack"></view-stack>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import ViewStack from "@enhavo/app/ViewStack/Components/ViewStack.vue"
import Menu from "@enhavo/app/Menu/Components/Menu.vue"
import Toolbar from "@enhavo/app/Toolbar/Components/Toolbar.vue"
import ToolbarData from "@enhavo/app/Toolbar/ToolbarData"
import ViewStackData from "@enhavo/app/ViewStack/ViewStackData"
import '@enhavo/app/assets/styles/app.scss'
import MenuData from "@enhavo/app/Menu/MenuData";
import ViewData from "@enhavo/app/View/ViewData";
import ViewComponent from "@enhavo/app/View/Components/ViewComponent.vue";
import Branding from "@enhavo/app/Main/Branding";
import ViewstackDropdown from "@enhavo/app/ViewStack/Components/ViewstackDropdown";

@Component({
    components: {
        'app-menu': Menu,
        'toolbar': Toolbar,
        'view-stack': ViewStack,
        'view-view': ViewComponent,
        'viewstack-dropdown':ViewstackDropdown,
    }
})
export default class App extends Vue {
    name = 'app';

    @Prop()
    branding: Branding;

    @Prop()
    menu: MenuData;

    @Prop()
    view: ViewData;

    @Prop()
    view_stack: ViewStackData;

    @Prop()
    toolbar: ToolbarData;

    toogleMenu()
    {
        this.menu.open = !this.menu.open;
        this.menu.customChange = true;
    }

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

