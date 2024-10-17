<template>
    <div class="app-view">
        <view-view></view-view>
        <action-bar></action-bar>
        <div :class="{'preview-view': true, 'tablet': iframeClass === 'tablet', 'mobile': iframeClass === 'mobile', 'desktop': iframeClass === 'desktop'}">
            <iframe class="iframe" name="preview" v-once></iframe>
        </div>
        <form :action="previewApp.data.url" method="post" target="preview">
            <template v-for="input in previewApp.data.inputs">
                <input type="hidden" :name="input.name" :value="input.value" />
            </template>
        </form>
    </div>
</template>

<script setup lang="ts">
import {onMounted, getCurrentInstance, inject} from "vue";
import '@enhavo/app/assets/styles/view.scss';
import $ from "jquery";
import PreviewApp from "@enhavo/app/preview/PreviewApp";

const previewApp = inject<PreviewApp>('previewApp');

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
