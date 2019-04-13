<template>
    <div class="menu">
        <template v-for="item in menu.items">
            <component class="menu-child" v-bind:is="item.component" v-bind:data="item" v-on:select:="select"></component>
        </template>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import MenuData from "@enhavo/app/Menu/MenuData";
import ApplicationBag from "@enhavo/app/ApplicationBag";
import MenuAwareApplication from "@enhavo/app/Menu/MenuAwareApplication";
let application = <MenuAwareApplication>ApplicationBag.getApplication();

@Component({
    components: application.getMenuRegistry().getComponents()
})
export default class Menu extends Vue {
    name: string = 'menu';

    @Prop()
    menu: MenuData;
}
</script>


