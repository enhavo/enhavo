<template>
    <div class="iframe-container" ref="container"></div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import iframeStorage from '../frame-storage';
import dispatcher from '../dispatcher';
import IframeView from '../Model/IframeView';

@Component
export default class IframeViewComponent extends Vue {
    name: 'iframe-view';
    @Prop()
    data: IframeView;

    mounted() {
        let element = iframeStorage.create(this.data.id, this.data.url);
        let container = <HTMLElement>this.$refs.container;
        container.appendChild(element);

        dispatcher.on('removed', () => {
            this.$forceUpdate();
        });
    }

    updated()
    {
        let element = iframeStorage.get(this.data.id);
        let container = <HTMLElement>this.$refs.container;
        while (container.firstChild) {
            container.removeChild(container.firstChild);
        }
        container.appendChild(element);
    }
}
</script>

<style lang="scss">
    .iframe-container { height: 100%; width: 100%;
        iframe { border: 0; height: 100%; width: 100%; }
    }
</style>
