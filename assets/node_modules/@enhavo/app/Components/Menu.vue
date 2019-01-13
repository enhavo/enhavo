<template>
    <div class="menu">
        Menu
    </div>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";
@Component
export default class Menu extends Vue {
    name: 'menu';
}
</script>

<style lang="scss">
.menu {
    width: 200px; height: 100%; float: left;  background-color: red;
}
</style>






