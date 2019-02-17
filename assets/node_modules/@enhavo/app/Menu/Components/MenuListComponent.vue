<template>
    <div class="menu-list">

        <div class="menu-child-title menu-list-child menu-list-title" v-on:click="toggle">
            <i v-bind:class="['icon', icon]" aria-hidden="true"></i>
            {{ label }} 
            <menu-notification v-if="notification" v-bind:data="notification"></menu-notification>
            <i v-bind:class="['open-indicator', 'fa fa-caret-down', {'fa-flip-vertical': isOpen }]" aria-hidden="true"></i>
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
            return (this.data && this.data.items) ? this.data.items : false;
        }

        toggle (): void {
            this.isOpen = !this.isOpen;
        }
    }
</script>

<style lang="scss" scoped>
    .menu-list {
        flex-wrap: wrap;

        .menu-list-child {
            flex-basis: 100%;
        }

        .menu-list-title {
            width: 100%; height: 50px; cursor: pointer; display: flex; align-items: center; flex-wrap: nowrap; position: relative;
        }

        .menu-list-items {
            width: 100%; padding-left: 20px; box-sizing: border-box;

            .menu-item {
                border-left: 2px solid white;
                margin-bottom: 2px;
            }
        }
    }
</style>






