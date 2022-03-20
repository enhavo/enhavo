<template>
    <div :class="{'view-table-col-text': true, 'text-wrap': column.wrap}" :style="getStyle()">
        {{ data.value }}
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import StateColumn from "@enhavo/app/grid/column/model/StateColumn";

@Options({})
export default class ColumnTextComponent extends Vue
{
    @Prop()
    data: any;

    @Prop()
    column: StateColumn;

    getStyle() {
        return 'color: '+this.data.color;
    }
}
</script>






