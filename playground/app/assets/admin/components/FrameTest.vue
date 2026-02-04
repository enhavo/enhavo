<template>
    <h1>Test Frame</h1>
    <div>ID: {{ getName() }}</div>
    <div>Label: <input v-if="frameTestManager.currentFrame" v-model="frameTestManager.currentFrame.label"></div>
    <button @click.prevent="frameTestManager.currentFrame.minimize = true">Minimize</button>
    <pre
        v-for="frame in frameTestManager.frames"
        style="font-family: monospace, Serif; padding: 4px; border: 1px solid black"
        :style="getStyle(frame)"
    >{{ formatJson(frame) }}</pre>
</template>

<script setup lang="ts">
import {inject, getCurrentInstance} from "vue";
import {FrameTestManager} from "../manager/FrameTestManager";
import {Frame} from "@enhavo/app/frame/Frame";

const frameTestManager = inject('frameTestManager') as FrameTestManager

frameTestManager.load();

function getName() {
    return window.name;
}

function formatJson(value) {
    return JSON.stringify(value, null, 4);
}

function getStyle(frame: Frame) {
    if (frame === frameTestManager.currentFrame) {
        return 'background-color: #90ee90';
    }
    return 'background-color: #ffcccb';
}

</script>
