<template>
    <div class="app">
        <toolbar v-bind:menu_open="menu.open" :branding="branding" v-on:toogle-menu="toogleMenu"></toolbar>
        <div class="sidebar" v-bind:class="{ 'menu-collapsed':!menu.open}">
            <app-menu v-bind:menu="menu" v-bind:class="{ 'menu-collapsed':!menu.open}"></app-menu>
        </div>
        <div class="toolbar-viewstack-container">
            <view-stack v-bind:data="view_stack"></view-stack>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import ViewStack from "@enhavo/app/ViewStack/Components/ViewStack.vue"
import Menu from "@enhavo/app/Menu/Components/Menu.vue"
import Toolbar from "@enhavo/app/Toolbar/Components/Toolbar.vue"
import ViewStackData from "@enhavo/app/ViewStack/ViewStackData"
import '@enhavo/app/assets/styles/app.scss'
import MenuData from "@enhavo/app/Menu/MenuData";
import ViewData from "@enhavo/app/View/ViewData";
import ViewComponent from "@enhavo/app/View/Components/ViewComponent.vue";
import Branding from "@enhavo/app/Main/Branding";

@Component({
    components: {
        'app-menu': Menu,
        'toolbar': Toolbar,
        'view-stack': ViewStack,
        'view-view': ViewComponent
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

    toogleMenu()
    {
        this.menu.open = !this.menu.open;
        this.menu.customChange = true;
    }
}
</script>

