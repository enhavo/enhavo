<template>
    <div>
        <view-stack></view-stack>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import ViewStack from "./ViewStack.vue"

@Component
export default class App extends Vue {
    name: 'app';
}

Vue.component('view-stack', ViewStack);
</script>

<style lang="scss">

</style>


