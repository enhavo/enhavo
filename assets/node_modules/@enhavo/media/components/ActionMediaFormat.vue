<template>
    <div class="action-media-format" v-click-outside="() => { data.open = false; }">
        <button v-if="data.hasFormats()" class="btn has-symbol" :class="data.class" @click.prevent="execute($event)" :data-action="data.key">{{ data.label }} <span :class="['icon', getIcon()]"></span></button>
        <div class="formats" v-if="data.open">
            <div class="format btn" v-for="format in data.getFormats()" @click="data.openFormat(format.key, format.label); data.open = false;">{{ format.label }}</div>
        </div>
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
