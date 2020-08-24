<template>
    <div class="app-view">
        <view-view></view-view>
        <flash-messages></flash-messages>
        <action-bar></action-bar>
        <media-library></media-library>
    </div>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";
import '@enhavo/app/assets/styles/view.scss';
import '@enhavo/media/assets/styles/_library.scss';

@Component()
export default class ApplicationComponent extends Vue
{

}
</script>

<style lang="css">
    .app-view { height: 100vh }
</style>
