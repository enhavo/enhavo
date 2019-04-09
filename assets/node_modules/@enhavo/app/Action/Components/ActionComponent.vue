<template>
    <div @click="data.execute()" class="action">
        <div class="action-icon">
            <i v-bind:class="['icon', icon]" aria-hidden="true"></i>
        </div>
        <div class="label">{{ data.label }}</div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import ActionInterface from "@enhavo/app/Action/ActionInterface";

    @Component
    export default class CloseActionComponent extends Vue {
        name: 'action-component';
        @Prop()
        data: ActionInterface;

        get icon(): string {
            return (this.data && this.data.icon) ? 'icon-' + this.data.icon : '';
        }
    }
</script>

<style lang="scss" scoped>
    .action {text-align:center;flex:0 1 100px;min-width:60px;padding:8px 13px;cursor:pointer;
        .action-icon {margin-bottom:6px;}
        label {white-space: nowrap;text-overflow: ellipsis;overflow: hidden;}
    }
</style>