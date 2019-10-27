<template>
    <div :class="{'view-table-col-text': true, 'text-wrap': column.wrap}">
        {{ data }}
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import TextColumn from "@enhavo/app/grid/Column/Model/TextColumn";

    @Component
    export default class ColumnTextComponent extends Vue {
        name: string = 'column-text';

        @Prop()
        data: string;

        @Prop()
        column: TextColumn;
    }
</script>






