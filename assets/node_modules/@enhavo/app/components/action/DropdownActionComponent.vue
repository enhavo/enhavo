<template>
    <div class="action" :ref="(el) => element = el">
        <div @click="toggleOpen()">
            <div class="action-icon">
                <i v-bind:class="['icon', data.icon]" aria-hidden="true"></i>
            </div>
            <div class="label">{{ data.label }}</div>
        </div>
        <ul class="dropdown-action-list" v-if="open" @click="itemClick()">
            <template v-for="action in data.items">
                <component class="action-container" v-bind:is="action.component" v-bind:data="action" :data-action="action.key"></component>
            </template>
        </ul>
    </div>
</template>

<script setup lang="ts">
import {onMounted, onUnmounted} from 'vue'
import DropdownAction from "@enhavo/app/action/model/DropdownAction";

const props = defineProps<{
    data: DropdownAction
}>()

const data = props.data;
let open: boolean = false;
let element: HTMLElement = null;

function toggleOpen()
{
    open = !open;
}

function itemClick()
{
    if (data.closeAfter) {
        open = false;
    }
}

// Close when clicked outside
function close(e: Event)
{
    if (!element.contains(<Node>e.target)) {
        open = false;
    }
}

onMounted(() => {
    document.addEventListener('click', close)
});

onUnmounted(() => {
    document.removeEventListener('click', close)
});
</script>
