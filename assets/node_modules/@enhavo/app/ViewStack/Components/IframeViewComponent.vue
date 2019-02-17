<template>
    <iframe :src="url" v-once ref="frame"></iframe>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import iframeStorage from '../frame-storage';
import dispatcher from '../dispatcher';
import IframeView from '../Model/IframeView';
import * as URI from 'urijs';

@Component
export default class IframeViewComponent extends Vue {
    name: 'iframe-view';
    @Prop()
    data: IframeView;

    mounted() {
        let frame = <HTMLIFrameElement>this.$refs.frame;
        iframeStorage.add(this.data.id, frame);

        dispatcher.on('removed', () => {
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

<style lang="scss">
    iframe { border: 0; height: 100%; width: 100%; }
</style>
