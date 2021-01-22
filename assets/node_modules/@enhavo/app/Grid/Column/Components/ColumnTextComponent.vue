<template>
    <div :class="{'view-table-col-text': true, 'text-wrap': column.wrap}" :style="{'white-space': column.whitespace}">{{ data }}</div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import TextColumn from "@enhavo/app/Grid/Column/Model/TextColumn";

    @Component
    export default class ColumnTextComponent extends Vue {
        name: string = 'column-text';

        @Prop()
        data: string;

        @Prop()
        column: TextColumn;
    }
</script>






