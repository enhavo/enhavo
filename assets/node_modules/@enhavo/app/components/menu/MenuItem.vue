<template>
    <a :href="data.mainUrl" v-bind:class="{'menu-child-title menu-item': true, 'selected': data.selected}" @click="open($event)">
        <div class="symbol-container">
            <i v-bind:class="['icon', getIcon()]" aria-hidden="true"></i>
        </div>
        <div class="label-container">
            {{ getLabel() }}
        </div>
        <menu-notification v-if="getNotification()" v-bind:data="getNotification()"></menu-notification>
    </a>
</template>

<script setup lang="ts">
import ItemMenu from '@enhavo/app/menu/model/MenuItem';


const props = defineProps<{
    data: ItemMenu
}>()

const data = props.data;

function getLabel(): string|boolean
{
    return (data && data.label) ? data.label : false;
}

function getIcon(): string
{
    return (data && data.icon) ? 'icon-' + data.icon : '';
}

function getNotification(): object
{
    return (data && data.notification) ? data.notification : false;
}

function open(event: Event): void
{
    event.preventDefault();
    data.open()
}

</script>





