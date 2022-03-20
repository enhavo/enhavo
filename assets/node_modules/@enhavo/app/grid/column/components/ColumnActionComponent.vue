<template>
    <div class="view-table-col-text">
        <component class="action-container" v-bind:is="getAction().component" v-bind:data="getAction()" :click-stop="true"></component>
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import ActionColumn from "@enhavo/app/grid/column/model/ActionColumn";
import ActionInterface from "@enhavo/app/action/ActionInterface";

@Options({})
export default class extends Vue
{
    @Prop()
    data: string;

    @Prop()
    column: ActionColumn;

    action: ActionInterface = null;

    getAction() {
        if(this.action == null) {
            this.action = this.column.getAction(this.data);
        }
        return this.action;
    }
}
</script>
