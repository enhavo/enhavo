<template>
    <div class="actions" v-if="actionManager.hasActions()">
        <div class="primary-actions">
            <template v-for="action in actionManager.primary">
                <component class="action-container" v-bind:is="action.component" v-bind:data="action" :data-action="action.key"></component>
            </template>
        </div>
        <div class="secondary-actions">
            <template v-for="action in actionManager.secondary">
                <component class="action-container" v-bind:is="action.component" v-bind:data="action"></component>
            </template>
        </div>
    </div>
</template>

<script lang="ts">
import {Vue, Options, Inject} from "vue-property-decorator";
import ActionManager from "@enhavo/app/action/ActionManager";

@Options({})
export default class extends Vue
{
    @Inject()
    actionManager: ActionManager;
}
</script>
