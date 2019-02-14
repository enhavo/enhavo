<template>
    <div class="loading-screen">
        Loading...
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
    .loading-screen {

    }
</style>