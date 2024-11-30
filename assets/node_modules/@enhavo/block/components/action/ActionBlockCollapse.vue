<template>
    <div class="button" @click="execute">
        <i class="icon" :class="getIcon()"></i>
    </div>
</template>

<script setup lang="ts">
import {BlockCollapseAction} from "@enhavo/block/action/BlockCollapseAction";

const props = defineProps<{
    data: BlockCollapseAction,
    clickStop?: boolean,
}>()


function getIcon(): string
{
    return props.data.collapsed ? 'icon-unfold_less' : 'icon-unfold_more';
}

function execute(event: Event)
{
    if (props.clickStop) {
        event.stopPropagation();
    }
    props.data.execute()
}
</script>
