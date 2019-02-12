<template>
    <div class="view-stack">
        <div class="view-container" v-for="view in views">
            <view-component v-bind:data="view"></view-component>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import ViewComponent from './ViewComponent.vue'

Vue.component('view-component', ViewComponent);

@Component
export default class ViewStack extends Vue {
    name: 'view-stack';
    @Prop()
    views: array;
}
</script>

<style lang="scss" scoped>
.view-stack {
    width: 100%; height: calc(100% - 50px); margin-left: 200px; background-color: green;
    .view-container { float: left; height: 100% }
}
</style>






