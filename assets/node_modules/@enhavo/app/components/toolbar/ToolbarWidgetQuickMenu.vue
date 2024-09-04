<template>
    <div class="toolbar-dropdown" v-click-outside="close" v-bind:class="{'selected': isOpen}">
        <div class="toolbar-dropdown-title" @click="toggle">
            <i :class="getIcon()"></i>
            <i v-bind:class="['open-indicator', 'icon icon-keyboard_arrow_down', {'icon-keyboard_arrow_up': isOpen }]"></i>
        </div>
        <div class="toolbar-dropdown-menu" v-show="isOpen">
            <template v-for="menu in data.menu">
                <div class="toolbar-dropdown-item" @click="open(menu)" >{{ menu.label }}</div>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import QuickMenuWidget from "@enhavo/app/toolbar/widget/model/QuickMenuWidget";
import {ref} from "vue";

const props = defineProps<{
    data: QuickMenuWidget
}>()

const data = props.data;

let isOpen = ref<boolean>(false);

function toggle (): void 
{
    isOpen.value = !isOpen.value
}

function close(): void 
{
    isOpen.value = false;
}

function open(menu: any) 
{
    data.open(menu);
    close();
}

function getIcon() 
{
    if (data.icon) {
        return 'icon icon-' + data.icon;
    }
    return ''
}
</script>
