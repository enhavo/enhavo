<template>
    <iframe :src="url" v-once ref="frame"></iframe>
</template>

<script lang="ts">
import {Vue, Options, Prop, Inject} from "vue-property-decorator";
import IframeView from '@enhavo/app/view-stack/model/IframeView';
import * as URI from 'urijs';
import ViewStack from "@enhavo/app/view-stack/ViewStack";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";

@Options({})
export default class extends Vue
{
    @Prop()
    data: IframeView;

    @Inject()
    viewStack: ViewStack

    @Inject()
    eventDispatcher: EventDispatcher

    subscriber: object;
    viewId: number = null;

    mounted()
    {
        this.viewId = this.data.id;
        let frame = <HTMLIFrameElement>this.$refs.frame;
        this.viewStack.getFrameStorage().add(this.data.id, frame);
        this.subscriber = this.eventDispatcher.on('removed', () => {
            this.$forceUpdate();  // trigger update
        });
    }

    updated()
    {
        /**
         * If this vue component was not destroyed and reused to display the iframe, we need to detect a mismatch
         * between the view id and iframe view id. If we found a mismatch me need to reload the iframe, otherwise,
         * we won't receive updates for the correct view id
         */
        if(this.viewId != this.data.id) {
            this.viewStack.getFrameStorage().remove(this.data.id);
            this.viewId = this.data.id;
            let frame = <HTMLIFrameElement>this.$refs.frame;
            this.$refs.frame.src = this.url;
            this.viewStack.getFrameStorage().add(this.data.id, frame);
        }
    }

    unmounted()
    {
        this.eventDispatcher.remove(this.subscriber);
        this.viewStack.getFrameStorage().remove(this.data.id);
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
