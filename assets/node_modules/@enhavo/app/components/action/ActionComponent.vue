<template>
    <div @click="execute($event)" class="action">
        <div class="action-icon">
            <i v-bind:class="['icon', getIcon()]" aria-hidden="true"></i>
        </div>
        <div class="label">{{ data.label }}</div>
    </div>
</template>

<script setup lang="ts">
import { inject } from 'vue'
import ActionManager from "@enhavo/app/action/ActionManager";
import ActionInterface from "@enhavo/app/action/ActionInterface";

const actionManager = inject<ActionManager>('actionManager');

const props = defineProps<{
    data: ActionInterface,
    clickStop?: boolean,
}>()

const data = props.data;
const clickStop = props.clickStop;

function getIcon(): string
{
    return (data && data.icon) ? 'icon-' + data.icon : '';
}

function execute(event: Event)
{
    if (clickStop) {
        event.stopPropagation();
    }
    data.execute()
}
</script>
