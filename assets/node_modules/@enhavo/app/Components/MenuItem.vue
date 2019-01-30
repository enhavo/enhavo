<template>
    <div class="menu-child-title menu-item" @click="openView()">
        <i v-bind:class="['fa', data.icon]" aria-hidden="true"></i>
        {{ data.label }}
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    
    @Component
    export default class MenuItem extends Vue {
        name: string = 'menu-item';

        @Prop()
        data: object;

        openView(): void {
            console.log('open view');
        }
    }
</script>

<style lang="scss" scoped>
    .menu-item { 
        height: 50px; cursor: pointer; 
    }
</style>






