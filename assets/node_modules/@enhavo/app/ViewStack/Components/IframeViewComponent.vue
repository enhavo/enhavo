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

    mounted() {
        let application = <MainApplication>ApplicationBag.getApplication();
        let frame = <HTMLIFrameElement>this.$refs.frame;
        application.getFrameStorage().add(this.data.id, frame);
        application.getEventDispatcher().on('removed', () => {
            this.$forceUpdate();
        });
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
