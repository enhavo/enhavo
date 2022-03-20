<template>
    <iframe :src="data.url"></iframe>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import LoadedEvent from '../event/LoadedEvent';

@Options({})
export default class extends Vue
{
    @Prop()
    data: object;

    created(): void
    {
        // window.setTimeout(function () {
        //     this.eventDispatcher.dispatch(new LoadedEvent());
        // }, 3000);
    }
}
</script>

<style lang="scss" scoped>
    iframe { border: 0; height: 100%; width: 100%; }
</style>
