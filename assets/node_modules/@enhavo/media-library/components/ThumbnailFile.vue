<template>
    <li :class="{'active': file.selected}" @click="onClick()">
        <span v-if="file.selected"><i class="icon icon-check"></i></span>
        <div class="img">
            <div class="background" :style="{ backgroundImage: 'url(' + file.previewImageUrl + ')' }">
            </div>
            <span ><i class="icon" :class="file.icon"></i></span>
        </div>
        <div class="title">{{ file.label }}</div>
    </li>
</template>

<script setup lang="ts">
import {inject} from "vue";
import {File} from "@enhavo/media-library/Data";
import MediaLibrary from "@enhavo/media-library/MediaLibrary";

const mediaLibrary = inject<MediaLibrary>('mediaLibrary');

const props = defineProps<{
    file: File
}>()

function onClick()
{
    mediaLibrary.open(props.file);
}
</script>
