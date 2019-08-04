<template>
    <div class="media-library">
        <div ref="dropzone" class="dropzone" v-show="data.dropZone"></div>
        <input v-once type="file" ref="upload" v-show="false">
        <ul class="media-library-file-list">
            <li v-for="item in data.items" @click="open(item)">
                <div class="filename">{{ item.data.name }}</div>
                <img v-bind:src="item.data.media.url" v-show="getType(item.data.extension) === 'image'" />
                <div class="icon-container"  v-show="getType(item.data.extension) === 'document'">
                    <i class="icon icon-insert_drive_file"></i>
                </div>
                <div class="icon-container"  v-show="getType(item.data.extension) === 'file'">
                    <i class="icon icon-insert_drive_file"></i>
                </div>
            </li>
        </ul>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import ApplicationBag from "@enhavo/app/ApplicationBag";
import MediaLibraryApplication from "@enhavo/media/MediaLibrary/MediaLibraryApplication";
import MediaData from "@enhavo/media/MediaLibrary/MediaData";
import MediaItem from "@enhavo/media/MediaLibrary/MediaItem";
import * as $ from "jquery";
import "blueimp-file-upload/js/jquery.iframe-transport";
import "blueimp-file-upload/js/jquery.fileupload";

@Component()
export default class MediaLibraryComponent extends Vue
{
    @Prop()
    data: MediaData;

    mounted()
    {
        let element = this.$refs.upload;

        $(document).on('upload', function () {
            $(element).trigger('click');
        });

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
                data.url = this.getRouter().generate('enhavo_media_library_upload', {});
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
            dropZone: this.$refs.dropzone,
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

    open(item: MediaItem) {
        this.getMediaLibrary().open(item)
    }

    getApplication(): MediaLibraryApplication
    {
        return <MediaLibraryApplication>ApplicationBag.getApplication();
    }

    getMediaLibrary()
    {
        return this.getApplication().getMediaLibrary()
    }

    getRouter()
    {
        return this.getApplication().getRouter()
    }

    getType(extension)
    {
        if(extension == 'png' || extension == 'jpg' ||  extension == 'jpeg' ||  extension == 'gif') {
            return 'image';
        }

        if(extension == 'pdf') {
            return 'document';
        }

        return 'file';
    }
}
</script>


