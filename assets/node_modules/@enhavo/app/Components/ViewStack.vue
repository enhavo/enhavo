<template>
    <div class="view-stack">
        <div class="view-container" v-for="view in views">
            <view-container v-bind:data="view"></view-container>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import IFrameView from "./IFrameView.vue"
import ViewContainer from "./ViewContainer.vue"

@Component
export default class ViewStack extends Vue {
    name: 'view-stack';
    @Prop()
    views: array;
}

Vue.component('view-container', ViewContainer);
Vue.component('iframe-view', IFrameView);
</script>

<style lang="scss" scoped>
.view-stack {
    width: 100%; height: calc(100% - 50px); margin-left: 200px; background-color: green;
    .view-container { float: left; height: 100% }
}
</style>






