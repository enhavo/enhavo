<template>
    <div class="toolbar">
        Toolbar
    </div>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";

@Component
export default class Toolbar extends Vue {
    name: 'toolbar';
}
</script>

<style lang="scss">
    .toolbar {
        width: 100%; background-color: blue; margin-left: 200px; height: 50px;
    }
</style>






