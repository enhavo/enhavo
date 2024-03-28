<template>
    <div class="view-component" :style="{order: getPosition(), maxWidth: data.width}" :class="{minimized: data.minimize, focused: data.focus}">
        <div class="toolbar">
            <strong v-if="!data.minimize">{{ data.label }}</strong>
            <div class="actions">
                <div @click="open()" v-if="!data.minimize" class="action"><span class="icon icon-open_in_new"></span></div>
                <div @click="close()" v-if="!data.minimize" class="action"><span class="icon icon-close"></span></div>
                <div @click="minimize()" v-if="!data.minimize" class="action minimize"><span class="icon icon-keyboard_arrow_left"></span></div>
                <div @click="maximize()" v-if="data.minimize" class="action maximize"><span class="icon icon-keyboard_arrow_right"></span></div>
            </div>
        </div>
        <div class="view-component-inner" v-show="!data.minimize">
            <overlay-container v-if="!getLoaded()">
                <slot>
                    <loading-screen></loading-screen>
                </slot>
            </overlay-container>
            <component v-bind:is="data.component" v-bind:data="data"></component>
        </div>
        <div class="view-resizer" @click="data.minimize ? maximize() : null">
            <div class="view-label" v-if="data.minimize">{{ data.label }}</div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {inject, onMounted, onUnmounted} from "vue";
import CloseEvent from '@enhavo/app/view-stack/event/CloseEvent';
import ArrangeEvent from '@enhavo/app/view-stack/event/ArrangeEvent';
import ViewInterface from "@enhavo/app/view-stack/ViewInterface";
import StateManager from "@enhavo/app/state/StateManager";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import MinimizeEvent from "@enhavo/app/view-stack/event/MinimizeEvent";
import MaximizeEvent from "@enhavo/app/view-stack/event/MaximizeEvent";

const stateManager = inject<StateManager>('stateManager');
const eventDispatcher = inject<EventDispatcher>('eventDispatcher');

const props = defineProps<{
    data: ViewInterface,
}>()

const data = props.data;

onMounted(() => {
    eventDispatcher.dispatch(new ArrangeEvent());
});

onUnmounted(() => {
    eventDispatcher.dispatch(new ArrangeEvent());
});

function close() 
{
    eventDispatcher.dispatch(new CloseEvent(data.id));
}

function open() 
{
    let url = stateManager.generateViewUrl(data);
    window.open(url, '_blank');
}

function minimize() 
{
    eventDispatcher.dispatch(new MinimizeEvent(data.id, true));
    eventDispatcher.dispatch(new ArrangeEvent());
}

function maximize() 
{
    eventDispatcher.dispatch(new MaximizeEvent(data.id, true));
    eventDispatcher.dispatch(new ArrangeEvent());
}

function getPosition(): number
{
    return data.position;
}

function getLoaded(): boolean
{
    return data.loaded;
}
</script>
