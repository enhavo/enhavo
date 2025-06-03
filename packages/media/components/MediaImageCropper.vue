<template>
    <div class="app-view">
        <ui-components></ui-components>
        <flash-messages></flash-messages>
        <modal-stack></modal-stack>
        <action-bar :primary="manager.actions" :secondary="manager.actionsSecondary"></action-bar>
        <div class="image-cropper-stage" v-if="manager.format">
            <loading-screen v-if="manager.loading"></loading-screen>
            <img v-once :src="manager.format.url" :ref="(el) => {manager.initCropper(el as HTMLImageElement)}" v-show="!manager.loading" />
        </div>
    </div>
</template>

<script setup lang="ts">
import {inject} from "vue";
import 'cropperjs/dist/cropper.min.css';
import {ImageCropperManager} from "@enhavo/media/manager/ImageCropperManager"
import {useRoute} from "vue-router";

const manager = inject<ImageCropperManager>('imageCropperManager');
const route = useRoute();

manager.load('enhavo_media_admin_api_image_cropper', route.params.token as string, route.params.format as string);
</script>

<style lang="css">
    .app-view { height: 100vh }
    .image-cropper-stage { height: 100vh }
</style>
