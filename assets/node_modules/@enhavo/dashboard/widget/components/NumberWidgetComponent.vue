<template>
    <div>
        <div class="label">{{ data.label }}</div>
        <div class="value">{{ data.value }}</div>
    </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from "vue-property-decorator";
    import NumberWidget from "@enhavo/dashboard/widget/model/NumberWidget";

    @Component
    export default class NumberWidgetComponent extends Vue
    {
        name: 'number-widget';

        @Prop()
        data: NumberWidget;
    }
</script>
