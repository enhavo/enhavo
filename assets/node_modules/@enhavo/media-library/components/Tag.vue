<template>
    <li @click="onClick()" :class="{'active':tag.selected}">
        <div class="tag-name">{{ tag.label }}</div>
    </li>
</template>

<script setup lang="ts">
import {inject} from "vue";
import {Tag} from "@enhavo/media-library/Data";
import MediaLibrary from "@enhavo/media-library/MediaLibrary";

const mediaLibrary = inject<MediaLibrary>('mediaLibrary');

const props = defineProps<{
    tag: Tag
}>()

function onClick()
{
    mediaLibrary.setActiveTag(props.tag);
}
</script>
