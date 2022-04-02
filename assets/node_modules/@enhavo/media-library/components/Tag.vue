<template>
    <li @click="onClick()" :class="{'active':tag.selected}">
        <div class="tag-name">{{ tag.label }}</div>
    </li>
</template>

<script lang="ts">
import {Inject, Options, Prop, Vue} from "vue-property-decorator";
import {Tag} from "../Data";
import MediaLibrary from "@enhavo/media-library/MediaLibrary";

@Options({})
export default class extends Vue {
    @Prop()
    tag: Tag;

    @Inject()
    mediaLibrary: MediaLibrary;

    onClick() {
        this.mediaLibrary.setActiveTag(this.tag);
    }

}
</script>
