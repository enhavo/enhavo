<template>
    <div class="app-view">
<!--        <view-view></view-view>-->
        <flash-messages></flash-messages>
        <action-bar></action-bar>
        <input v-show="false" v-once ref="upload" multiple type="file">
        <div class="media-library-overlay" data-media-library-overlay>
            <div class="inner-content" data-scroll-container>
                <div class="media-library-progress" style="width: 0%;" v-bind:style="{'width': styleProgress()}"></div>
                <div class="tag-list">
                    <div class="headline"><i class="icon icon-filter_list"></i>
                        {{ translator.trans('enhavo_media_library.tags') }}
                    </div>
                    <tag-list
                        :tags="mediaLibrary.data.tags"
                    ></tag-list>

                    <div class="headline"><i class="icon icon-filter_list"></i>
                        {{ translator.trans('enhavo_media_library.content_types') }}
                    </div>
                    <content-type-list
                        :content-types="mediaLibrary.data.contentTypes"
                    ></content-type-list>
                </div>
                <div class="result-search">
                    <div class="search">
                        <input ref="searchInput" v-model="mediaLibrary.data.searchString" type="text" data-media-library-search />
                        <span class="media-library-search-reset" @click="clearSearch()"><i class="icon icon-close"></i></span>
                        <span class="media-library-search-submit" @click="search()"><i class="icon icon-search"></i></span>
                    </div>
                    <div v-if="!mediaLibrary.data.loading">
                        <div class="media-library-view-switch">
                            <i class="icon icon-account_box" :class="mediaLibrary.getView() == 'thumbnail' && 'active'" @click="setView('thumbnail')"></i>
                            <i class="icon icon-format_list_bulleted" :class="mediaLibrary.getView() == 'list' && 'active'" @click="setView('list')"></i>
                        </div>
                        <list-view v-if="mediaLibrary.data.view==='list'"></list-view>
                        <thumbnail-view v-else></thumbnail-view>
                        <div v-if="mediaLibrary.data.dropZoneActive" class="media-library-overlay">
                            Drop something here
                        </div>
                    </div>
                    <div v-else class="lds-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
                <br clear="all">
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import * as $ from 'jquery';
import {Inject, Options, Vue, Watch} from "vue-property-decorator";
import '@enhavo/app/assets/styles/view.scss';
import Translator from "@enhavo/core/Translator";
import Router from "@enhavo/core/Router";
import {Column, File} from "@enhavo/media-library/Data";
import MediaLibrary from "@enhavo/media-library/MediaLibrary";
import "blueimp-file-upload/js/jquery.fileupload";
import '@enhavo/media-library/assets/styles.scss';

@Options({})
export default class extends Vue {
    @Inject()
    translator: Translator;

    @Inject()
    mediaLibrary: MediaLibrary;

    @Inject()
    router: Router;

    @Watch('searchString')
    onChangeSearchString(val: string) {
        this.getMediaLibrary().data.searchString = val;
    }

    styleProgress() {
        return this.mediaLibrary.data.progress + '%';
    }

    getAddLabel() {
        if (this.getMediaLibrary().data.multiple) {
            return "media_library.add_selecteded";
        } else {
            return "media_library.add_selected";
        }
    }

    setView(type: string) {
        this.mediaLibrary.setView(type);
    }

    search() {
        this.getMediaLibrary().search();
    }

    clearSearch() {
        this.getMediaLibrary().clearSearch()

    }

    created() {
        window.addEventListener('keydown', (e) => {
            if (e.key == 'Enter') {
                let isSearchFocus = $(this.$refs.searchInput).is(':focus');
                if (isSearchFocus) {
                    this.search();
                }
            }
        });
    }

    mounted() {
        let element = this.$refs.upload;

        $(document).on('upload', function () {
            $(element).trigger('click');
        });

        $(element).fileupload({
            replaceFileInput: false,
            dataType: 'json',
            paramName: 'files',
            done: (event, data) => {
                if (false === data.response().result.success) {
                    data.response().result.errors.forEach((error) => {
                        this.getMediaLibrary().showError(error);
                    });

                    this.getMediaLibrary().loaded();

                } else if (data.response().result.length === 0) {
                    this.getMediaLibrary().showError(this.translator.trans('enhavo_media_library.upload.fail.message'));
                    this.getMediaLibrary().loaded();

                } else {
                    this.getMediaLibrary().refresh();
                }

                this.getMediaLibrary().setProgress(0);
            },
            fail: (event, data) => {
                this.getMediaLibrary().showError(this.translator.trans('enhavo_media_library.upload.fail.message'));
                this.getMediaLibrary().setProgress(0);
                this.getMediaLibrary().loaded();
            },
            add: (event, data) => {
                data.url = this.getRouter().generate('enhavo_media_upload', {});
                data.submit();
                this.getMediaLibrary().loading();
                this.getMediaLibrary().setProgress(0);
            },
            progressall: (event, data) => {
                let progress = data.loaded / data.total * 100;
                this.getMediaLibrary().setProgress(progress);
            },
            dropZone: this.$refs.itemList,
            pasteZone: null
        });

        $(document).bind('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.getMediaLibrary().showDropZone();
        });

        $(this.$refs.mediaLibrary).bind('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.getMediaLibrary().showDropZone();
            this.getMediaLibrary().showDropZoneActive();
        });

        $(document).bind('dragleave', (e) => {
            if ($(document).find('.app-view').length > 0 && $(document).find('.app-view').find(e.target).length > 0) return;
            e.preventDefault();
            e.stopPropagation();
            this.getMediaLibrary().hideDropZone();
        });

        $(this.$refs.mediaLibrary).bind('dragleave', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.getMediaLibrary().hideDropZoneActive();
        });

        $(document).bind('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.getMediaLibrary().hideDropZone();
            this.getMediaLibrary().hideDropZoneActive();
        });
    }

    onClickPage(page: number) {
        this.getMediaLibrary().setActivePage(page);
    }

    open(item: File) {
        this.getMediaLibrary().open(item)
    }

    getMediaLibrary(): MediaLibrary {
        return this.mediaLibrary;
    }

    getRouter() {
        return this.router;
    }

    getType(extension) {
        if (extension == 'png' || extension == 'jpg' || extension == 'jpeg' || extension == 'gif') {
            return 'image';
        }

        if (extension == 'pdf') {
            return 'document';
        }

        return 'file';
    }
}
</script>
