<template>
    <div class="app">
        <app-menu v-bind:menu="menu"></app-menu>
        <toolbar v-bind:quick_menu="quick_menu"></toolbar>
        <view-stack v-bind:data="view_stack"></view-stack>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import ViewStack from "../../ViewStack/Components/ViewStack.vue"
import Menu from "../../Menu/Components/Menu.vue"
import Toolbar from "../../Toolbar/Components/Toolbar.vue"
import ViewStackData from "../../ViewStack/ViewStackData"
import QuickMenu from "../../Toolbar/QuickMenu"

@Component({
    components: {
        'app-menu': Menu,
        'toolbar': Toolbar,
        'view-stack': ViewStack
    }
})
export default class App extends Vue {
    name = 'app';
    @Prop()
    menu: array;

    @Prop()
    view_stack: ViewStackData;

    @Prop()
    quick_menu: QuickMenu;
}


</script>

<style lang="scss">
.app {
    height: 100%;
}
</style>


