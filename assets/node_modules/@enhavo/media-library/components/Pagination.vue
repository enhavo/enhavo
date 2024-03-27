<template>
    <div class="media-library-pagination">
        <ul>
            <li v-if="hasPrevious()" @click="setPage(previousPage())">&lt;</li>
            <li v-else>&nbsp;</li>
            <li v-for="page in getPages()"
                @click="setPage(page)"
                :class="isActivePage(page)?'active':''">
                {{ page }}
            </li>
            <li v-if="hasNext()" @click="setPage(nextPage())">&gt;</li>
            <li v-else>&nbsp;</li>
        </ul>
    </div>
</template>

<script setup lang="ts">
import {inject} from "vue";
import MediaLibrary from "@enhavo/media-library/MediaLibrary";
import '@enhavo/app/assets/styles/view.scss';

const mediaLibrary = inject<MediaLibrary>('mediaLibrary');

function setPage(page: number) 
{
    mediaLibrary.setActivePage(page);
}

function isActivePage(page: number) 
{
    return mediaLibrary.getActivePage() == page;
}

function getPages() 
{
    let current = mediaLibrary.getActivePage();
    let pages = mediaLibrary.getPages().length;
    if (pages > 3) {
        if (current < 2) {
            return [1, 2, 3];
        } else if (current > pages - 2) {
            return [pages - 2, pages - 1, pages];
        }

        return [current - 1, current, current + 1];
    }

    return mediaLibrary.getPages();
}

function previousPage() 
{
    return mediaLibrary.getActivePage() - 1;
}

function nextPage() 
{
    return (mediaLibrary.getActivePage() + 1);
}

function hasPrevious() 
{
    return mediaLibrary.getActivePage() > 1;
}

function hasNext() 
{
    return mediaLibrary.getActivePage() < mediaLibrary.getPages().length;
}
</script>
