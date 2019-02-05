<template>
    <div class="app">
        <app-menu v-bind:menu="menu"></app-menu>
        <toolbar v-bind:quick_menu="quick_menu"></toolbar>
        <view-stack v-bind:views="views"></view-stack>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import ViewStack from "./ViewStack.vue"
import Menu from "./Menu/Menu.vue"
import Toolbar from "./Toolbar.vue"
import { QuickMenu } from "../Models/QuickMenu"

@Component
export default class App extends Vue {
    name = 'app';
    @Prop()
    menu: array;

    @Prop()
    views: array;

    @Prop()
    quick_menu: QuickMenu;
}

Vue.component('app-menu', Menu);
Vue.component('toolbar', Toolbar);
Vue.component('view-stack', ViewStack);
</script>

<style lang="scss">
.app {
    height: 100%;
}
</style>


