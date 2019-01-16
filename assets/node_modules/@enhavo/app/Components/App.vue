<template>
    <div class="app">
        <app-menu v-bind:menu="menu"></app-menu>
        <toolbar></toolbar>
        <view-stack v-bind:views="views"></view-stack>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import ViewStack from "./ViewStack.vue"
import Menu from "./Menu.vue"
import Toolbar from "./Toolbar.vue"

@Component
export default class App extends Vue {
    name = 'app';
    @Prop()
    menu: array;
    @Prop()
    views: array;
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


