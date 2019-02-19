<template>
    <div class="loading-screen">
        <div class="loading-indicator">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";

    @Component
    export default class LoadingScreen extends Vue {
        name: string = 'loading-screen';
    }
</script>

<style scoped>
    .loading-screen {position:absolute;top:0;left:0;width:100%;height:100%;

    }
</style>