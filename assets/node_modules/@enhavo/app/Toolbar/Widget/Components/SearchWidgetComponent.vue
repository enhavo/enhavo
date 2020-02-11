<template>
    <div>
        Search Widget
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import WidgetInterface from "@enhavo/app/Toolbar/Widget/WidgetInterface";

    @Component
    export default class SearchWidgetComponent extends Vue {
        name: 'search-widget';

        @Prop()
        data: WidgetInterface;
    }
</script>