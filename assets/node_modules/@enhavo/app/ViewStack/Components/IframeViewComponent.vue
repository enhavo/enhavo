<template>
    <iframe :src="url" ref="iframe"></iframe>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import dispatcher from '../dispatcher';
import IframeView from '../Model/IframeView';
import { Subscriber } from '../EventDispatcher';
import * as URI from 'urijs';

@Component
export default class IframeViewComponent extends Vue {
    name: 'iframe-view';
    @Prop()
    data: IframeView;
    subscriber: Subscriber;

    get url() {
        return this.getUrl();
    }

    get iframe() {
        const iframe = document.createElement('iframe');
        iframe.src = this.getUrl();
        return iframe;
    }

    private getUrl() {
        let uri = new URI(this.data.url);
        return uri.addQuery('view_id', this.data.id)
    }

    mounted() {
        let iframeEl = this.$refs.iframe;
        this.subscriber = dispatcher.all((event) => {
            if(event.origin != this.data.id && iframeEl.contentWindow != null) {
                if(dispatcher.isDebug()) {
                    console.groupCollapsed('pass event ('+event.name+') to iframe ' + this.data.id);
                    console.dir(event);
                    console.groupEnd()
                }
                let data = 'view_stack_event|'+JSON.stringify(event);
                iframeEl.contentWindow.postMessage(data, '*');
            }
        });
    }

    destroyed()
    {
        console.log('destroyed');
        console.log(this.data.id);
        dispatcher.remove(this.subscriber);
    }
}
</script>

<style lang="scss" scoped>
    .iframe-container { height: 100%; width: 100%; }
    iframe { border: 0; height: 100%; width: 100%; }
</style>
