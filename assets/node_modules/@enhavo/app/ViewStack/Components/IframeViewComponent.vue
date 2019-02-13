<template>
    <iframe :src="url" ref="iframe"></iframe>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import dispatcher from '../dispatcher';
import IframeView from '../Model/IframeView';
import * as URI from 'urijs';

@Component
export default class IframeViewComponent extends Vue {
    name: 'iframe-view';
    @Prop()
    data: IframeView;

    get url() {
        let uri = new URI(this.data.url);
        return uri.addQuery('view_id', this.data.id)
    }

    mounted() {
        let iframeEl = this.$refs.iframe;
        dispatcher.all((event) => {
            if(event.origin != this.data.id && iframeEl.contentWindow != null) {
                let data = 'view_stack_event|'+JSON.stringify(event);
                iframeEl.contentWindow.postMessage(data, '*');
            }
        });
    }
}
</script>

<style lang="scss" scoped>
    iframe { border: 0; height: 100%; width: 100%; }
</style>
