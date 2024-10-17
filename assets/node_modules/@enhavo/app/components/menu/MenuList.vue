<template>
    <div :class="{'menu-list': true, 'selected': data.selected}" v-click-outside="outside">
        <div class="menu-child-title menu-list-child menu-list-title" @click="toggle" >
            <div class="symbol-container">
                <i :class="['icon', getIcon()]"></i>
            </div>
            <div class="label-container">
                {{ getLabel() }}
            </div>
            <menu-notification v-if="getNotification()" :data="getNotification()"></menu-notification>
            <i :class="['open-indicator', 'icon', {'icon-keyboard_arrow_up': data.isOpen }, {'icon-keyboard_arrow_down': !data.isOpen }]"></i>
        </div>
        <div class="menu-list-child menu-list-items" v-show="data.isOpen">
            <template v-for="item in data.children()">
                <component :is="item.component" :data="item"></component>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {ListMenuItem} from '@enhavo/app/menu/model/ListMenuItem';

const props = defineProps<{
    data: ListMenuItem
}>()

function getLabel(): string|boolean
{
    return (props.data && props.data.label) ? props.data.label : false;
}

function getIcon(): string|boolean
{
    return (props.data && props.data.icon) ? 'icon-' + props.data.icon : false;
}

function getNotification(): object
{
    return (props.data && props.data.notification) ? props.data.notification : false;
}

function toggle(): void
{
    props.data.isOpen = !props.data.isOpen;
    if (props.data.isOpen) {
        props.data.open();
        props.data.closeOtherMenus();
    } else {
        props.data.close();
    }
}

function outside(): void
{
    window.setTimeout(() => {
        if (!props.data.isMainMenuOpen()) {
            props.data.isOpen = false
        }
    }, 100)
}
</script>
