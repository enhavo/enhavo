<template>
    <div class="app-view">
        <view-view v-bind:data="view"></view-view>
        <flash-messages v-bind:messages="messages"></flash-messages>
        <action-bar v-bind:data="actions"></action-bar>
        <image-cropper-stage v-bind:data="format"></image-cropper-stage>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import '@enhavo/app/assets/styles/view.scss';
import ActionBar from "@enhavo/app/Action/Components/ActionBar.vue";
import FlashMessages from "@enhavo/app/FlashMessage/Components/FlashMessages.vue";
import ImageCropperStageComponent from "@enhavo/media/ImageCropper/Components/ImageCropperStageComponent.vue";
import ViewData from "@enhavo/app/View/ViewData";
import ViewComponent from "@enhavo/app/View/Components/ViewComponent";
import FormatData from "@enhavo/media/ImageCropper/FormatData";

@Component({
    components: {FlashMessages, ActionBar, 'view-view': ViewComponent, 'image-cropper-stage': ImageCropperStageComponent}
})
export default class ImageCropperComponent extends Vue {
    name = 'app-view';

    @Prop()
    view: ViewData;

    @Prop()
    actions: Array<object>;

    @Prop()
    messages: Array<object>;

    @Prop()
    format: FormatData;

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


