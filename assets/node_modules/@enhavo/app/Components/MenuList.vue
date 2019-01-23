<template>
    <div class="menu-list">

        <div class="menu-list-child menu-list-title" v-on:click="toggle">
            {{ data.label }} 
        </div>
        <div class="menu-list-child menu-list-items" v-show="isOpen">
            <template v-for="item in data.items">
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

        data () {
            return {
                isOpen: false
            }
        }

        toggle (): void {
            this.isOpen = !this.isOpen;
            console.log('toggle menu with label: ', data.label);
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
            width: 100%; height: 50px; cursor: pointer; display: flex; align-items: center;
        }

        .menu-list-items {
            width: 100%;

            .menu-item {
                border-left: 2px solid white;
            }
        }
    }
</style>






