<template>
    <div class="menu-list">
        <div class="menu-child-title menu-list-child menu-list-title" v-on:click="toggle">
            <div class="symbol-container">
                <i v-bind:class="['icon', icon]" aria-hidden="true"></i>
            </div>
            <div class="label-container">
                {{ label }}
            </div>
            <menu-notification v-if="notification" v-bind:data="notification"></menu-notification>
            <i v-bind:class="['open-indicator', 'icon', {'icon-keyboard_arrow_up': isOpen }, {'icon-keyboard_arrow_down': !isOpen }]" aria-hidden="true"></i>
        </div>
        <div class="menu-list-child menu-list-items" v-show="isOpen">
            <template v-for="item in items">
                <component v-bind:is="item.component" v-bind:data="item"></component>
            </template>
        </div>

    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";

    @Component
    export default class MenuList extends Vue {
        name: string = 'menu-list';

        @Prop()
        data: object;

        isOpen: boolean = false;

        get label(): string {
            return (this.data && this.data.label) ? this.data.label : false;
        }

        get icon(): string {
            return (this.data && this.data.icon) ? 'icon-' + this.data.icon : false;
        }

        get notification(): object {
            return (this.data && this.data.notification) ? this.data.notification : false;
        }

        get items(): array {
            return (this.data && this.data.children) ? this.data.children : false;
        }

        toggle (): void {
            this.isOpen = !this.isOpen;
        }
    }
</script>






