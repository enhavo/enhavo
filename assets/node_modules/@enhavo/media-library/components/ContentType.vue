<template>
    <li :class="{'active':contentType.selected}">
        <div class="tag-name" v-bind:contentType="contentType" @click="onClick()">{{ contentType.label }}</div>
    </li>
</template>

<script lang="ts">
import {Inject, Options, Prop, Vue} from "vue-property-decorator";
import {ContentType} from "../Data";
import MediaLibrary from "@enhavo/media-library/MediaLibrary";

@Options({})
export default class extends Vue {
    @Prop()
    contentType: ContentType;

    @Inject()
    mediaLibrary: MediaLibrary;

    onClick() {
        this.mediaLibrary.setActiveContentType(this.contentType);
    }

}
</script>
