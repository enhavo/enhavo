<template>
    <div class="app-view">
        <view-view></view-view>
        <template v-for="widget of $widgetManager.widgets">
            <component
                class="widget-container"
                :is="widget.component"
                :data="widget"
            ></component>
        </template>
    </div>
</template>

<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
import '@enhavo/app/assets/styles/view.scss';

@Component()
export default class ApplicationComponent extends Vue
{

}
</script>

<style lang="css">
    .app-view { height: 100vh }
</style>
