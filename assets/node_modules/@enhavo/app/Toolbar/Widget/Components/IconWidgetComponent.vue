<template>
    <div>
        Icon Widget
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import WidgetInterface from "@enhavo/app/Toolbar/Widget/WidgetInterface";

    @Component
    export default class IconWidgetComponent extends Vue {
        name: 'icon-widget';

        @Prop()
        data: WidgetInterface;
    }
</script>