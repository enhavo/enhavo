<template>
    <div class="media-library-pagination">
        <ul>
            <li v-if="hasPrevious()" @click="setPage(this.previousPage())">&lt;</li>
            <li v-else>&nbsp;</li>
            <li v-for="page in this.getPages()"
                @click="setPage(page)"
                :class="isActivePage(page)?'active':''">
                {{ page }}
            </li>
            <li v-if="hasNext()" @click="setPage(this.nextPage())">&gt;</li>
            <li v-else>&nbsp;</li>
        </ul>
    </div>
</template>

<script lang="ts">
import {Inject, Options, Vue, Watch} from "vue-property-decorator";
import '@enhavo/app/assets/styles/view.scss';
import MediaLibrary from "@enhavo/media-library/MediaLibrary";

@Options({})
export default class extends Vue {

    @Inject()
    mediaLibrary: MediaLibrary;

    setPage(page: number) {
        this.mediaLibrary.setActivePage(page);
    }

    isActivePage(page: number) {
        return this.mediaLibrary.getActivePage() == page;
    }

    getPages() {
        let current = this.mediaLibrary.getActivePage();
        let pages = this.mediaLibrary.getPages().length;
        if (pages > 3) {
            if (current < 2) {
                return [1, 2, 3];
            } else if (current > pages - 2) {
                return [pages - 2, pages - 1, pages];
            }

            return [current - 1, current, current + 1];
        }

        return this.mediaLibrary.getPages();
    }

    previousPage() {
        return this.mediaLibrary.getActivePage() - 1;
    }

    nextPage() {
        return (this.mediaLibrary.getActivePage() + 1);
    }

    hasPrevious() {
        return this.mediaLibrary.getActivePage() > 1;
    }

    hasNext() {
        return this.mediaLibrary.getActivePage() < this.mediaLibrary.getPages().length;
    }
}
</script>
