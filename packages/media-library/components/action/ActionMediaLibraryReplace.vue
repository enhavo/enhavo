<template>
    <div @click="execute($event)" class="action" :class="data.class" :data-action="data.key">
        <div class="action-icon">
            <i :class="['icon', getIcon()]"></i>
        </div>
        <div class="label">{{ data.label }}</div>
        <input v-show="false" v-once :ref="(el) => data.replaceElement = el as HTMLElement" type="file" @change.prevent="changeReplace"/>
    </div>

</template>

<script setup lang="ts">
import {MediaLibraryReplaceAction} from "@enhavo/media-library/action/MediaLibraryReplaceAction";
import {inject, onMounted} from "vue";

const props = defineProps<{
    data: MediaLibraryReplaceAction,
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

function changeReplace()
{
    props.data.replaceFile();
}

onMounted(() => {
    props.data.mounted()
})
</script>
