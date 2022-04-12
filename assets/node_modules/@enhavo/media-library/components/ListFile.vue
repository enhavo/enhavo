<template>
    <div class="view-table-row" :class="{'active': file.selected}"
        @click="onClick()"
    >
        <div class="view-table-row-columns">
            <div class="view-table-col-text view-table-col" style="width: 25%">
                {{ file.label }}
            </div>
            <div class="view-table-col-text view-table-col" style="width: 25%">
                {{ file.suffix }}
            </div>
            <div class="view-table-col-text view-table-col" style="width: 25%">
                {{ file.type }}
            </div>
            <div class="view-table-col-text view-table-col" style="width: 25%">
                {{ file.date }}
            </div>
        </div>
    </div>
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
