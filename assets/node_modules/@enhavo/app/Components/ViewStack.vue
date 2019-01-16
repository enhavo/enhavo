<template>
    <div class="view-stack">
        <div v-for="view in views" class="view-container">
            <iframe-view v-bind:url="view.url"></iframe-view>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import IFrameView from  "./IFrameView.vue";

@Component
export default class ViewStack extends Vue {
    name: 'view-stack';
    @Prop()
    views: array;
}

Vue.component('iframe-view', IFrameView);
</script>

<style lang="scss">
.view-stack {
    width: 100%; height: calc(100% - 50px); margin-left: 200px; background-color: green;
    .view-container { width: 100%; height: 100%; }
}
</style>






