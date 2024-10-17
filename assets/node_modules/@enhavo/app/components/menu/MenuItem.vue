<template>
    <a :href="data.getMainUrl()" :class="{'menu-child-title menu-item': true, 'selected': data.isActive()}" @click="open($event)">
        <div class="symbol-container">
            <i :class="['icon', getIcon()]"></i>
        </div>
        <div class="label-container">
            {{ getLabel() }}
        </div>
        <menu-notification v-if="getNotification()" :data="getNotification()"></menu-notification>
    </a>
</template>

<script setup lang="ts">
import {BaseMenuItem} from '@enhavo/app/menu/model/BaseMenuItem';

const props = defineProps<{
    data: BaseMenuItem
}>()

function getLabel(): string|boolean
{
    return (props.data && props.data.label) ? props.data.label : false;
}

function getIcon(): string
{
    return (props.data && props.data.icon) ? 'icon-' + props.data.icon : '';
}

function getNotification(): object
{
    return (props.data && props.data.notification) ? props.data.notification : false;
}

function open(event: Event): void
{
    event.preventDefault();
    props.data.open()
}

</script>





