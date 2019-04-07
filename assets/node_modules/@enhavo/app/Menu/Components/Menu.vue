<template>
    <div class="menu">
        <template v-for="item in menu.items">
            <component class="menu-child" v-bind:is="item.component" v-bind:data="item"></component>
        </template>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import MenuItem from "@enhavo/app/Menu/Components//MenuItemComponent.vue"
import MenuList from "@enhavo/app/Menu/Components//MenuListComponent.vue"
import MenuDropdown from "@enhavo/app/Menu/Components//MenuDropdownComponent.vue"
import MenuNotification from "@enhavo/app/Menu/Components/MenuNotification.vue"
import MenuData from "@enhavo/app/Menu/MenuData";

Vue.component('menu-item', MenuItem);
Vue.component('menu-list', MenuList);
Vue.component('menu-dropdown', MenuDropdown);
Vue.component('menu-notification', MenuNotification);

@Component
export default class Menu extends Vue {
    name: string = 'menu';
    @Prop()
    menu: MenuData;
}
</script>

<style lang="scss" scoped>
.menu {height:100%;
    /deep/ .menu-child,/deep/ .menu-item {padding:0 10px;display: flex; align-items: center; justify-content: flex-start;cursor:pointer;min-height:46px;
        .symbol-container {width:26px;}
    }
}
</style>

<style lang="scss">
.menu-child-title {
    i {
        &.open-indicator {
            position:absolute;right:10px;
        }
    }
}
</style>



