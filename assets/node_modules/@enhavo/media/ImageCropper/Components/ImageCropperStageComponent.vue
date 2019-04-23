<template>
    <div class="image-cropper-stage">
        <img v-once ref="cropper-stage" />
        <form method="POST" v-show="false">
            <input type="hidden" name="height" v-bind:value="data.height">
            <input type="hidden" name="width" v-bind:value="data.width">
            <input type="hidden" name="x" v-bind:value="data.x">
            <input type="hidden" name="y" v-bind:value="data.y">
        </form>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import * as Cropper from 'cropperjs';
import FormatData from "@enhavo/media/ImageCropper/FormatData";
import 'cropperjs/dist/cropper.min.css';
import * as $ from 'jquery';

@Component()
export default class ImageCropperStageComponent extends Vue
{
    @Prop()
    data: FormatData;

    cropper: Cropper;

    mounted()
    {
        let element = <HTMLElement>this.$refs['cropper-stage'];
        element.src = this.data.url;
        this.cropper = new Cropper(element, {
            ready: () => {
                if(this.data.ratio) {
                    this.cropper.setAspectRatio(this.data.ratio);
                }
                if(this.data.getData()) {
                    this.cropper.setAspectRatio(this.data.ratio);
                }
            }
        });

        element.addEventListener('crop', () => {
            this.data.height = this.cropper.getData().height;
            this.data.width = this.cropper.getData().width;
            this.data.x = this.cropper.getData().x;
            this.data.y = this.cropper.getData().y;
            this.data.changed = true;
        });

        $(document).on('image-cropper-move', () => {
            this.cropper.setDragMode('move');
        });

        $(document).on('image-cropper-crop', () => {
            this.cropper.setDragMode('crop');
        });

        $(document).on('image-cropper-zoom-in', () => {
            this.cropper.zoom(0.1);
        });

        $(document).on('image-cropper-zoom-out', () => {
            this.cropper.zoom(-0.1);
        });

        $(document).on('image-cropper-reset', () => {
            this.cropper.reset();
        });
    }
}
</script>

<style lang="css" scoped>
    .image-cropper-stage { height: 100% }
</style>


