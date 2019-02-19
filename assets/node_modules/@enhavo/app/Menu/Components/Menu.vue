<template>
    <div class="menu">

        <template v-for="item in menu">
            <component class="menu-child" v-bind:is="item.component" v-bind:data="item"></component>
        </template>

    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import MenuItem from "./MenuItemComponent.vue"
import MenuList from "./MenuListComponent.vue"
import MenuDropdown from "./MenuDropdownComponent.vue"
import MenuNotification from "./MenuNotification.vue"

@Component
export default class Menu extends Vue {
    name: string = 'menu';
    @Prop()
    menu: object;
}

Vue.component('menu-item', MenuItem);
Vue.component('menu-list', MenuList);
Vue.component('menu-dropdown', MenuDropdown);
Vue.component('menu-notification', MenuNotification);
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



