<template>
    <div @click="execute($event)" class="action" :class="data.class">
        <div class="action-icon">
            <i :class="['icon', getIcon()]"></i>
        </div>
        <div class="label">{{ data.label }}</div>
    </div>
</template>

<script setup lang="ts">
import {ActionInterface} from "@enhavo/app/action/ActionInterface";

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
