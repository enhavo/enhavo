<template>
    <div v-bind:class="{'menu-list': true, 'selected': data.selected}" v-click-outside="outside">
        <div class="menu-child-title menu-list-child menu-list-title" v-on:click="toggle" >
            <div class="symbol-container">
                <i v-bind:class="['icon', getIcon()]" aria-hidden="true"></i>
            </div>
            <div class="label-container">
                {{ getLabel() }}
            </div>
            <menu-notification v-if="getNotification()" v-bind:data="getNotification()"></menu-notification>
            <i v-bind:class="['open-indicator', 'icon', {'icon-keyboard_arrow_up': data.isOpen }, {'icon-keyboard_arrow_down': !data.isOpen }]" aria-hidden="true"></i>
        </div>
        <div class="menu-list-child menu-list-items" v-show="data.isOpen">
            <template v-for="item in data.children()">
                <component v-bind:is="item.component" v-bind:data="item"></component>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import ListMenu from '@enhavo/app/menu/model/MenuList';

const props = defineProps<{
    data: ListMenu
}>()

const data = props.data;

function getLabel(): string|boolean
{
    return (data && data.label) ? data.label : false;
}

function getIcon(): string|boolean
{
    return (data && data.icon) ? 'icon-' + data.icon : false;
}

function getNotification(): object
{
    return (data && data.notification) ? data.notification : false;
}

function toggle (): void
{
    data.isOpen = !data.isOpen;
    if(data.isOpen) {
        data.open();
        data.closeOtherMenus();
    } else {
        data.close();
    }
}

function outside(): void
{
    window.setTimeout(() => {
        if(!data.isMainMenuOpen()) {
            data.isOpen = false
        }
    }, 100)
}
</script>
