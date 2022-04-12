<template>
    <div class="media-library-pagination">
        <ul>
            <li v-for="page in mediaLibrary.data.pages"
                @click="setPage(page)"
                :class="isActivePage(page)?'active':''">
                {{ page }}
            </li>
        </ul>
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
import ListView from "@enhavo/media-library/components/ListView.vue";
import ThumbnailView from "@enhavo/media-library/components/ThumbnailView.vue";

@Options({})
export default class extends Vue {

    @Inject()
    mediaLibrary: MediaLibrary;

    setPage(page: number) {
        this.mediaLibrary.setActivePage(page);
    }

    isActivePage(page: number) {
        return this.mediaLibrary.data.page == page;
    }
}
</script>
