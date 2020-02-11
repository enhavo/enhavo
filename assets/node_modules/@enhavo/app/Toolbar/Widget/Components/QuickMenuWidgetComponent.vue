<template>
    <div>
        Quick Menu Widget
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import WidgetInterface from "@enhavo/app/Toolbar/Widget/WidgetInterface";

    @Component
    export default class QuickMenuWidgetComponent extends Vue {
        name: 'quick-menu-widget-component';

        @Prop()
        data: WidgetInterface;
    }
</script>

<style scoped>

</style>