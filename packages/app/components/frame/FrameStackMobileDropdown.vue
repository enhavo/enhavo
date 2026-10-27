<template>
    <div class="viewstack-dropdown-container" v-if="frameStack && getHasMoreThanOneView()" v-click-outside="close" :class="{'selected': isOpen}">
        <div class="dropdown-trigger" @click="toggle()">
            <span class="icon icon-more_horiz"></span>
        </div>
        <ul v-if="isOpen" class="viewstack-dropdown">
            <template v-for="frame in frameStack.getRenderFrames()">
                <li v-if="!frame.removed" @click="maximize(frame);close();">{{ frame.label }}</li>
            </template>
        </ul>
    </div>
</template>

<script setup lang="ts">
import {useAsyncService} from "@enhavo/framework/composables/service";
import {FrameStack} from "../../frame/FrameStack";

const frameStack = useAsyncService<FrameStack>('frameStack');
let isOpen: boolean = false;

function toggle(): void 
{
    isOpen = !isOpen;
}

function getHasMoreThanOneView() 
{
    return frameStack.value.getRenderFrames().length > 1;
}

function maximize(element) 
{
    for (let frame of frameStack.value.getFrames()) {
        frame.minimize = true;
        frame.focus = false;
    }
    element.minimize = false;
    element.focus = true;
}

function close(): void 
{
    isOpen = false;
}
</script>
