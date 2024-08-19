<template>
    <div @click="open()">
        <i :class="getIcon()"></i>
    </div>
</template>

<script setup lang="ts">
import IconWidget from "@enhavo/app/toolbar/widget/model/IconWidget";

const props = defineProps<{
    data: IconWidget
}>()

const data = props.data;

function getIcon()
{
    if (data.icon) {
        return 'icon icon-' + data.icon;
    }
    return ''
}

function open()
{
    data.open();
}
</script>