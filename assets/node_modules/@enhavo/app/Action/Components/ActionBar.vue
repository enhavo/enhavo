<template>
    <div class="actions">
        Actions:
        <div class="primary-actions">
            <template v-for="action in data">
                <component class="action-container" v-bind:is="action.component" v-bind:data="action"></component>
            </template>
        </div>
        <div class="secondary-actions">
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
        data: object;
    }
</script>

<style lang="scss" scoped>
    .actions {
        width: 100%; height: 100px; background-color: #00d6b2;
        .primary-actions {
            float: left;
        }
        .secondary-actions {
            float: right;
        }
    }
</style>






