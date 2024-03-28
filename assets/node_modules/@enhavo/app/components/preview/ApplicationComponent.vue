<template>
    <div class="app-view">
        <view-view></view-view>
        <action-bar></action-bar>
        <div v-bind:class="{'preview-view': true, 'tablet': iframeClass === 'tablet', 'mobile': iframeClass === 'mobile', 'desktop': iframeClass === 'desktop'}">
            <iframe class="iframe" name="preview" v-once></iframe>
        </div>
        <form v-bind:action="previewApp.data.url" method="post" target="preview">
            <template v-for="input in previewApp.data.inputs">
                <input type="hidden" v-bind:name="input.name" v-bind:value="input.value" />
            </template>
        </form>
    </div>
</template>

<script setup lang="ts">
import {onMounted, getCurrentInstance} from "vue";
import '@enhavo/app/assets/fonts/enhavo-icons.font'
import '@enhavo/app/assets/styles/view.scss';
import * as $ from "jquery";
import PreviewApp from "@enhavo/app/preview/PreviewApp";

const props = defineProps<{
    previewApp: PreviewApp
}>()

const previewApp = props.previewApp;
const instance = getCurrentInstance();
let iframeClass = 'desktop';

onMounted(() => {
    $(document).on('tablet', ()  => {
        iframeClass = 'tablet';
        instance?.proxy?.$forceUpdate();
    });

    $(document).on('desktop', ()  => {
        iframeClass = 'desktop';
        instance?.proxy?.$forceUpdate();
    });

    $(document).on('mobile', ()  => {
        iframeClass = 'mobile';
        instance?.proxy?.$forceUpdate();
    });
})
</script>
