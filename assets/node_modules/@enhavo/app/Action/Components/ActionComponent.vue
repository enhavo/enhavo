<template>
    <div @click="data.execute();if(clickStop) {$event.stopPropagation();}" class="action">
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
        @Prop()
        clickStop: boolean;

        get icon(): string {
            return (this.data && this.data.icon) ? 'icon-' + this.data.icon : '';
        }
    }
</script>