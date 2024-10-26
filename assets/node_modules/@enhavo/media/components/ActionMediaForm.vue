<template>
    <button class="btn has-symbol" :class="data.class" @click.prevent="execute($event)">{{ data.label }} <span :class="['icon', getIcon()]" :data-action="data.key"></span></button>
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
