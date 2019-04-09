<template>
    <div class="app">
        <view-view v-bind:data="view"></view-view>
        <div class="sidebar" v-show="menu.open">
            <app-menu v-bind:menu="menu"></app-menu>
        </div>
        <div class="toolbar-viewstack-container">
            <toolbar v-bind:quick_menu="quick_menu" v-on:toogle-menu="toogleMenu"></toolbar>
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
import QuickMenu from "@enhavo/app/Toolbar/QuickMenu"
import '@enhavo/app/assets/styles/app.scss'
import MenuData from "@enhavo/app/Menu/MenuData";
import ViewData from "@enhavo/app/View/ViewData";
import ViewComponent from "@enhavo/app/View/Components/ViewComponent";

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
    menu: MenuData;

    @Prop()
    view: ViewData;

    @Prop()
    view_stack: ViewStackData;

    @Prop()
    quick_menu: QuickMenu;

    toogleMenu()
    {
        this.menu.open = !this.menu.open;
    }
}
</script>

<style lang="scss">
.app {height:100vh;display:flex;
    .sidebar {width:260px;}
    .toolbar-viewstack-container {width:calc(100% - 260px);display:flex;flex-direction:column;}
}
</style>


