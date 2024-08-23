<template>
    <div class="view-component" :style="{order: frame.position, maxWidth: frame.width}" :class="{minimized: frame.minimize, focused: frame.focus}">
        <div class="toolbar">
            <strong v-if="!frame.minimize">{{ frame.label }}</strong>
            <div class="actions">
                <div @click="open()" v-if="!frame.minimize" class="action"><span class="icon icon-open_in_new"></span></div>
                <div @click="close()" v-if="!frame.minimize" class="action"><span class="icon icon-close"></span></div>
                <div @click="minimize()" v-if="!frame.minimize" class="action minimize"><span class="icon icon-keyboard_arrow_left"></span></div>
                <div @click="maximize()" v-if="frame.minimize" class="action maximize"><span class="icon icon-keyboard_arrow_right"></span></div>
            </div>
        </div>
        <div class="view-component-inner" v-show="!frame.minimize">
            <overlay-container v-if="!frame.loaded">
                <slot>
                    <loading-screen></loading-screen>
                </slot>
            </overlay-container>
            <iframe :src="getUrl()" :name="frame.id" v-once></iframe>
        </div>
        <div class="view-resizer" @click="frame.minimize ? maximize() : null">
            <div class="view-label" v-if="frame.minimize">{{ frame.label }}</div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {inject} from "vue";
import {Frame} from "@enhavo/app/frame/Frame";
import {FrameStack} from "@enhavo/app/frame/FrameStack";

const frameStack = inject<FrameStack>('frameStack');

const props = defineProps<{
    frame: Frame,
}>()

const frame = props.frame;
const id = frame.id;

function close()
{
    frameStack.removeFrame(frame);
}

function open()
{
    let dataString = frameStack.createStringFromWindows([data])
    let uri = new URL(window.location.href);
    uri.searchParams.set('frame',  dataString);
    window.open(uri.toString(), '_blank');
}

function minimize()
{
    frame.minimize = true;
}

function maximize()
{
    frame.minimize = false;
}

function getUrl(): string
{
    let uri = new URL(frame.url, window.origin);
    return uri.toString();
}

</script>


<style lang="scss" scoped>
iframe { border: 0; height: 100%; width: 100%; }
</style>
