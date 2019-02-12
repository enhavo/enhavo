<template>
    <iframe :src="url"></iframe>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import dispatcher from '../dispatcher';
import LoadedEvent from '../Event/LoadedEvent';
import IframeView from '../Model/IframeView';

@Component
export default class IframeViewComponent extends Vue {
    name: 'iframe-view';
    @Prop()
    data: IframeView;

    mounted(): void
    {
        let self = this;
        window.setTimeout(function () {
            dispatcher.dispatch(new LoadedEvent(self.data.id));
        }, 3000);
    }

    get url() {
        return this.data.url;
    }
}
</script>

<style lang="scss" scoped>
    iframe { border: 0; height: 100%; width: 100%; }
</style>
