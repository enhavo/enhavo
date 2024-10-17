<template>
    <div @click="open()">
        <i :class="getIcon()"></i>
    </div>
</template>

<script setup lang="ts">
import {IconWidget} from "@enhavo/app/toolbar/model/IconWidget";

const props = defineProps<{
    data: IconWidget
}>()

function getIcon()
{
    if (props.data.icon) {
        return 'icon icon-' + props.data.icon;
    }
    return ''
}

function open()
{
    props.data.open();
}
</script>