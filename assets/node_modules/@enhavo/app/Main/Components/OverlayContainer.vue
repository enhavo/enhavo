<template>
    <div class="overlay">
        <slot></slot>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import { LoadingScreen } from "./LoadingScreen.vue";

    @Component({
        components: {LoadingScreen}
    })
    export default class OverlayContainer extends Vue {
        name: string = 'overlay-container';
    }
</script>

<style scoped>
    .overlay {
        width: 100%; height: 100%; background-color: deeppink; position: absolute;
    }
</style>