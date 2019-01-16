<template>
    <div class="view">
        <iframe :src="url"></iframe>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";

@Component
export default class ViewStack extends Vue {
    name: 'iframe-view';
    @Prop()
    url: string;
}
</script>

<style lang="scss">
.view {
    width: 50%; height: 100%;
    iframe { border: 0; height: 100%; width: 100%; }
}
</style>






