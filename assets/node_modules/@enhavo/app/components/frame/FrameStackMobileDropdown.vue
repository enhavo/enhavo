<template>
    <div class="viewstack-dropdown-container" v-if="getHasMoreThanOneView()" v-click-outside="close" :class="{'selected': isOpen}">
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
import {inject} from "vue";
import {FrameStack} from "../../frame/FrameStack";

const frameStack = inject<FrameStack>('frameStack');
let isOpen: boolean = false;

function toggle(): void 
{
    isOpen = !isOpen;
}

function getHasMoreThanOneView() 
{
    return frameStack.getRenderFrames().length > 1;
}

function maximize(element) 
{
    for (let frame of frameStack.getFrames()) {
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
