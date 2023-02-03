<template>
    <div class="image-cropper-stage">
        <loading-screen v-if="loading"></loading-screen>
        <img v-once ref="cropper-stage" v-show="!loading" />
        <form method="POST" v-show="false">
            <input type="hidden" name="height" v-bind:value="imageCropper.data.height">
            <input type="hidden" name="width" v-bind:value="imageCropper.data.width">
            <input type="hidden" name="x" v-bind:value="imageCropper.data.x">
            <input type="hidden" name="y" v-bind:value="imageCropper.data.y">
        </form>
    </div>
</template>

<script lang="ts">
import {Vue, Options, Prop, Inject} from "vue-property-decorator";
import * as Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.min.css';
import * as $ from 'jquery';
import ImageCropperApp from "@enhavo/media/image-cropper/ImageCropperApp";

@Options({})
export default class extends Vue
{
    @Inject()
    imageCropper: ImageCropperApp

    cropper: Cropper;
    loading = false;

    mounted()
    {
        this.loading = true;
        let element = <HTMLElement>this.$refs['cropper-stage'];
        element.src = this.imageCropper.data.url;
        this.cropper = new Cropper(element, {
            ready: () => {
                if(this.imageCropper.data.ratio) {
                    this.cropper.setAspectRatio(this.imageCropper.data.ratio);
                }
                if(this.imageCropper.data.getData()) {
                    this.cropper.setData(this.imageCropper.data.getData());
                }
                this.loading = false;
                this.$forceUpdate();
            }
        });

        element.addEventListener('crop', () => {
            let cropperData = this.cropper.getData();

            let changed = (value1: number, value2: number) => {
                return Math.abs(value1 - value2) > 0.00001;
            };

            if (changed(this.imageCropper.data.height, cropperData.height)) {
                this.imageCropper.data.changed = true;
                this.imageCropper.data.height = cropperData.height;
            }

            if (changed(this.imageCropper.data.width, cropperData.width)) {
                this.imageCropper.data.changed = true;
                this.imageCropper.data.width = cropperData.width;
            }

            if (changed(this.imageCropper.data.x, cropperData.x)) {
                this.imageCropper.data.changed = true;
                this.imageCropper.data.x = cropperData.x;
            }

            if (changed(this.imageCropper.data.y, cropperData.y)) {
                this.imageCropper.data.changed = true;
                this.imageCropper.data.y = cropperData.y;
            }
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
    .image-cropper-stage { height: 100%;position:relative; }
</style>


