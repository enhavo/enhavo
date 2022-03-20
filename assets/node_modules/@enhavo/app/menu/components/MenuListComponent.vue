<template>
    <div v-bind:class="{'menu-list': true, 'selected': data.selected}" v-click-outside="outside">
        <div class="menu-child-title menu-list-child menu-list-title" v-on:click="toggle" >
            <div class="symbol-container">
                <i v-bind:class="['icon', icon]" aria-hidden="true"></i>
            </div>
            <div class="label-container">
                {{ label }}
            </div>
            <menu-notification v-if="notification" v-bind:data="notification"></menu-notification>
            <i v-bind:class="['open-indicator', 'icon', {'icon-keyboard_arrow_up': data.isOpen }, {'icon-keyboard_arrow_down': !data.isOpen }]" aria-hidden="true"></i>
        </div>
        <div class="menu-list-child menu-list-items" v-show="data.isOpen">
            <template v-for="item in data.children()">
                <component v-bind:is="item.component" v-bind:data="item"></component>
            </template>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import MenuList from '@enhavo/app/menu/model/MenuList';

@Options({})
export default class extends Vue
{
    @Prop()
    data: MenuList;

    get label(): string|boolean {
        return (this.data && this.data.label) ? this.data.label : false;
    }

    get icon(): string|boolean {
        return (this.data && this.data.icon) ? 'icon-' + this.data.icon : false;
    }

    get notification(): object {
        return (this.data && this.data.notification) ? this.data.notification : false;
    }

    toggle (): void {
        this.data.isOpen = !this.data.isOpen;
        if(this.data.isOpen) {
            this.data.open();
            this.data.closeOtherMenus();
        } else {
            this.data.close();
        }
    }

    outside(): void {
        window.setTimeout(() => {
            if(!this.data.isMainMenuOpen()) {
                this.data.isOpen = false
            }
        }, 100)
    }
}
</script>
