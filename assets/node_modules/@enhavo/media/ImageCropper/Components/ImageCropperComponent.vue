<template>
    <div class="app-view">
        <view-view></view-view>
        <flash-messages></flash-messages>
        <action-bar></action-bar>
        <image-cropper></image-cropper>
    </div>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";
import '@enhavo/app/assets/styles/view.scss';

@Component()
export default class ImageCropperComponent extends Vue
{
    mounted() {
        $(document).on('change', ':input', () => {
            this.format.changed = true;
        });
    }
}
</script>

<style lang="css">
    .app-view { height: 100vh }
</style>
