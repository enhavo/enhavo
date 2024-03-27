<template>
    <iframe :src="getUrl()" v-once :ref="(el) => frame = el"></iframe>
</template>

<script setup lang="ts">
import {inject, onMounted, onUnmounted, onUpdated, getCurrentInstance, ref} from "vue";
import IframeView from '@enhavo/app/view-stack/model/IframeView';
import * as URI from 'urijs';
import ViewStack from "@enhavo/app/view-stack/ViewStack";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";

const viewStack = inject<ViewStack>('viewStack');
const eventDispatcher = inject<EventDispatcher>('eventDispatcher');

const props = defineProps<{
    data: IframeView
}>()

const data = props.data;
let subscriber: object;
let viewId: number = null;
let url: string = getUrl();
let frame: HTMLIFrameElement = null;

function getUrl()
{
    let uri = new URI(data.url);
    return uri.addQuery('view_id', data.id) + '';
}

onMounted(() => {
    viewId = data.id;
    viewStack.getFrameStorage().add(data.id, frame);
    subscriber = eventDispatcher.on('removed', () => {
        // trigger update
        const instance = getCurrentInstance();
        instance?.proxy?.$forceUpdate();
    });
});

onUpdated(() => {
    /**
     * If this vue component was not destroyed and reused to display the iframe, we need to detect a mismatch
     * between the view id and iframe view id. If we found a mismatch me need to reload the iframe, otherwise,
     * we won't receive updates for the correct view id
     */
    if (viewId != data.id) {
        viewStack.getFrameStorage().remove(data.id);
        viewId = data.id;
        frame.src = url;
        viewStack.getFrameStorage().add(data.id, frame);
    }
});

onUnmounted(() => {
    eventDispatcher.remove(subscriber);
    viewStack.getFrameStorage().remove(data.id);
});
</script>

<style lang="scss" scoped>
    iframe { border: 0; height: 100%; width: 100%; }
</style>
