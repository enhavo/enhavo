<template>
    <div class="actions" v-if="primary.length > 0 || secondary.length > 0">
        <div class="primary-actions">
            <template v-for="action in primary">
                <component class="action-container" v-bind:is="action.component" v-bind:data="action"></component>
            </template>
        </div>
        <div class="secondary-actions">
            <template v-for="action in secondary">
                <component class="action-container" v-bind:is="action.component" v-bind:data="action"></component>
            </template>
        </div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";
    let application = <ActionAwareApplication>ApplicationBag.getApplication();

    @Component({
        components: application.getActionRegistry().getComponents()
    })
    export default class ActionBar extends Vue {
        @Prop()
        primary: object;

        @Prop()
        secondary: object;
    }
</script>