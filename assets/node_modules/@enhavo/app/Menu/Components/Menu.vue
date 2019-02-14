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
.menu {
    width: 200px; height: 100%; float: left;  background-color: brown;

    .menu-child {
        padding-left: 10px; padding-right: 10px; box-sizing: border-box; display: flex; align-items: center; justify-content: flex-start; font-size: 16px; font-weight: 500; border-bottom: 2px solid yellowgreen;
    }
}
</style>

<style lang="scss">
.menu-child-title {
    i {
        margin-right: 10px;

        &.open-indicator {
            position: absolute; right: 10px; margin-right: initial;
        }
    }
}
</style>



