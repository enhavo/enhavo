<template>
    <button v-if="data.hasFormats()" class="btn has-symbol" :class="data.class" @click.prevent="execute($event)" :data-action="data.key">{{ data.label }} <span :class="['icon', getIcon()]"></span></button>
    <div v-if="data.open">
        <div v-for="format in data.getFormats()" @click="data.openFormat(format.key)">{{ format.label }}</div>
    </div>
</template>

<script setup lang="ts">
import {MediaFormatAction} from "@enhavo/media/action/MediaFormatAction";
import {onMounted} from "vue";

const props = defineProps<{
    data: MediaFormatAction,
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
