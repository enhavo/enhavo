<template>
    <div class="menu-item" @click="openView()">
        {{ label }}
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    @Component
    export default class MenuItem extends Vue {
        name: string = 'menu-item';
        @Prop()
        label: string;

        openView() {
            console.log('open view');
        }
    }
</script>

<style lang="scss">
    .menu {
        .menu-item {background-color: #0b97c4}
    }
</style>






