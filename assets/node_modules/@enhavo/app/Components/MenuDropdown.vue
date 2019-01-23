<template>
    <div class="menu-dropdown">
        {{ data.label }}
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";

    @Component
    export default class MenuDropdown extends Vue {
        name: string = 'menu-dropdown';

        @Prop()
        data: object;
    }
</script>

<style lang="scss" scoped>
    .menu-dropdown {
        height: 50px; background-color: #0b97c4;
    }
</style>






