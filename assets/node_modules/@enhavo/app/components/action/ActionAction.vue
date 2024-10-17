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
import {onMounted} from "vue";

const props = defineProps<{
    data: ActionInterface,
    clickStop?: boolean,
}>()


function getIcon(): string
{
    return (props.data && props.data.icon) ? 'icon-' + props.data.icon : '';
}

function execute(event: Event)
{
    if (props.clickStop) {
        event.stopPropagation();
    }
    props.data.execute()
}

onMounted(() => {
    props.data.mounted()
})
</script>
