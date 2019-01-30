<template>
    <iframe :src="data.url"></iframe>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";

@Component
export default class IFrameView extends Vue {
    name: 'iframe-view';
    @Prop()
    data: object;
}
</script>

<style lang="scss" scoped>
    iframe { border: 0; height: 100%; width: 100%; }
</style>
