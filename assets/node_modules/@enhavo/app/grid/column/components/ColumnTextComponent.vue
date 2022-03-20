<template>
    <div :class="{'view-table-col-text': true, 'text-wrap': column.wrap}" :style="{'white-space': column.whitespace}">{{ data }}</div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import TextColumn from "@enhavo/app/grid/column/model/TextColumn";

@Options({})
export default class ColumnTextComponent extends Vue
{
    @Prop()
    data: string;

    @Prop()
    column: TextColumn;
}
</script>






