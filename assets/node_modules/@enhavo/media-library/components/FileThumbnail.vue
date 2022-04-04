<template>
    <li :class="{'active': file.selected}" @click="onClick()">
        <span v-if="file.selected"><i class="icon icon-check"></i></span>
        <div :style="{ backgroundImage: 'url(' + file.previewImageUrl + ')' }" class="img">
            <span><i class="icon" :class="file.icon"></i></span>
        </div>
        <div class="title">{{ file.label }}</div>
    </li>
</template>

<script lang="ts">
import {Inject, Options, Prop, Vue} from "vue-property-decorator";
import {File} from "@enhavo/media-library/Data";
import MediaLibrary from "@enhavo/media-library/MediaLibrary";

@Options({})
export default class extends Vue {
    @Inject()
    mediaLibrary: MediaLibrary;

    @Prop()
    file: File;

    onClick() {
        this.mediaLibrary.open(this.file);
    }
}
</script>
