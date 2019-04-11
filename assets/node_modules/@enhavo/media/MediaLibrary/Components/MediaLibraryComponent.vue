<template>
    <div>
        <div v-once ref="dropzone"></div>
        <ul class="media-library-file-list">
            <li v-for="item in items">
                <div class="filename">{{ item.filename }}</div>
                <img v-bind:src="item.url" v-show="item.extension" />
                <div class="icon-container" v-show="item.extension == 'pdf'">
                    <i class="icon icon-file-pdf"></i>
                </div>
                <div class="icon-container" v-show="item.extension">
                    <i class="icon icon-file-code"></i>
                </div>
            </li>
        </ul>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import ApplicationBag from "@enhavo/app/ApplicationBag";
import MediaLibraryApplication from "@enhavo/media/MediaLibrary/MediaLibraryApplication";
import * as $ from "jquery";
import "blueimp-file-upload/js/jquery.iframe-transport";
import "blueimp-file-upload/js/jquery.fileupload";

@Component()
export default class MediaLibraryComponent extends Vue
{
    @Prop()
    items: MediaItem[];

    mounted()
    {
        let element = this.$refs.dropzone;
        $(element).fileupload({
            dataType: 'json',
            paramName: 'files',
            done: (event, data) => {
                this.getMediaLibrary().refresh()
            },
            fail: (event, data) => {
                this.getMediaLibrary().fail()
            },
            add: (event, data) => {
                data.url = router.generate('enhavo_media_library_upload', {});
                data.submit();
                this.getMediaLibrary().loading();
            },
            progressall: (event, data) => {
                let progress = data.loaded / data.total * 100;
                if (progress >= 100) {
                    this.getMediaLibrary().setProgress(0);
                } else {
                    this.getMediaLibrary().setProgress(progress);
                }
            },
            dropZone: element,
            pasteZone: null
        });

        $(document).bind('dragover', (e) =>  {
            e.preventDefault();
            e.stopPropagation();
            this.getMediaLibrary().showDropZone()

        });

        $(document).bind('dragleave drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.getMediaLibrary().hideDropZone()
        });
    }

    getApplication(): MediaLibraryApplication
    {
        return <MediaLibraryApplication>ApplicationBag.getApplication();
    }

    getMediaLibrary()
    {
        return this.getApplication().getMediaLibrary()
    }
}
</script>

<style lang="css" scoped>
    .dropzone {
        height: 100px;
        width: 100px;
        background-color: red
    }
</style>


