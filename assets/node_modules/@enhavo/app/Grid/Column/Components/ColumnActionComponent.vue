<template>
    <div class="view-table-col-text">
        <component class="action-container" v-bind:is="column.getAction().component" v-bind:data="action"></component>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import ActionColumn from "@enhavo/app/grid/Column/Model/ActionColumn";
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";
    let application = <ActionAwareApplication>ApplicationBag.getApplication();

    @Component({
        components: application.getActionRegistry().getComponents()
    })
    export default class ColumnActionComponent extends Vue {
        name: string = 'column-action';

        @Prop()
        data: string;

        @Prop()
        column: ActionColumn;

        get action()
        {
            let action = this.column.getAction();
            action[this.column.mapping] = this.data;
            return action;
        }
    }
</script>






