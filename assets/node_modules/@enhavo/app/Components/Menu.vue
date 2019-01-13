<template>
    <div class="menu">
        <div v-for="item in menu">
            <menu-item v-bind:label="item.label"></menu-item>
        </div>

    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import MenuItem from "./MenuItem.vue"

@Component
export default class Menu extends Vue {
    name: string = 'menu';
    @Prop()
    menu: object;
}

Vue.component('menu-item', MenuItem);
</script>

<style lang="scss">
.menu {
    width: 200px; height: 100%; float: left;  background-color: red;
}
</style>



