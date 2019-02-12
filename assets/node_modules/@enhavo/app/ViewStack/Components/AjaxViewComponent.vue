<template>
    <iframe :src="data.url"></iframe>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import dispatcher from '../dispatcher';
import LoadedEvent from '../Event/LoadedEvent';

@Component
export default class AjaxViewComponent extends Vue {
    name: 'iframe-view';
    @Prop()
    data: object;

    created(): void
    {
        window.setTimeout(function () {
            dispatcher.dispatch(new LoadedEvent('asd'));
            console.log('finish loading');
        }, 3000);
    }
}
</script>

<style lang="scss" scoped>
    iframe { border: 0; height: 100%; width: 100%; }
</style>
