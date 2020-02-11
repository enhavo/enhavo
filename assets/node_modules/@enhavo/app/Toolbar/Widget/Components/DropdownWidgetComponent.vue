<template>
    <div>
        Dropdown Widget
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import WidgetInterface from "@enhavo/app/Toolbar/Widget/WidgetInterface";

    @Component
    export default class DropdownWidgetComponent extends Vue {
        name: 'dropdown-widget-component';

        @Prop()
        data: WidgetInterface;
    }
</script>