<template>
    <div class="overlay">
        <slot></slot>
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import LoadingScreen from "./LoadingScreen.vue";

@Options({
    components: {LoadingScreen}
})
export default class OverlayContainer extends Vue {

}
</script>

<style scoped>
    .overlay {
        position: absolute;top:0;left:0;width:100%;height:100%;
    }
</style>