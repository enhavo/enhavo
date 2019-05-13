<template>
    <iframe :src="url" v-once ref="frame"></iframe>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import IframeView from '@enhavo/app/ViewStack/Model/IframeView';
import * as URI from 'urijs';
import ApplicationBag from "@enhavo/app/ApplicationBag";
import MainApplication from "@enhavo/app/MainApplication";

@Component
export default class IframeViewComponent extends Vue {
    name: 'iframe-view';

    @Prop()
    data: IframeView;

    subscriber: object;
    viewId: number = null;

    mounted()
    {
        this.viewId = this.data.id;
        let application = <MainApplication>ApplicationBag.getApplication();
        let frame = <HTMLIFrameElement>this.$refs.frame;
        application.getFrameStorage().add(this.data.id, frame);
        this.subscriber = application.getEventDispatcher().on('removed', () => {
            this.$forceUpdate();  // trigger update
        });
    }

    updated()
    {
        let application = <MainApplication>ApplicationBag.getApplication();
        /**
         * If this vue component was not destroyed and reused to display the iframe, we need to detect a mismatch
         * between the view id and iframe view id. If we found a mismatch me need to reload the iframe, otherwise,
         * we won't receive updates for the correct view id
         */
        if(this.viewId != this.data.id) {
            application.getFrameStorage().remove(this.data.id);
            this.viewId = this.data.id;
            let frame = <HTMLIFrameElement>this.$refs.frame;
            this.$refs.frame.src = this.url;
            application.getFrameStorage().add(this.data.id, frame);
        }
    }

    destroyed()
    {
        let application = <MainApplication>ApplicationBag.getApplication();
        application.getEventDispatcher().remove(this.subscriber);
        application.getFrameStorage().remove(this.data.id);
    }

    get url()
    {
        let uri = new URI(this.data.url);
        return uri.addQuery('view_id', this.data.id) + '';
    }
}
</script>

<style lang="scss" scoped>
    iframe { border: 0; height: 100%; width: 100%; }
</style>
