<template>
    <div class="view-stack">
        <iframe></iframe>
    </div>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";

@Component
export default class ViewStack extends Vue {
    name: 'iframe-view';
}
</script>

<style lang="scss">
.view-stack {
    width: 100%; height: calc(100% - 50px); margin-left: 200px; background-color: green;
}
</style>






