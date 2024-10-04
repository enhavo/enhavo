<template>
        <div v-if="!collection.loading">
            <div class="media-library-view-switch">
                <i class="icon icon-account_box" :class="collection.view == 'thumbnail' ? 'active' : ''" @click="collection.view = 'thumbnail'"></i>
                <i class="icon icon-format_list_bulleted" :class="collection.view == 'table' ? 'active' : ''" @click="collection.view = 'table'"></i>
            </div>
            <collection-media-library-table
                v-if="collection.view === 'table'"
                :collection="collection">
            </collection-media-library-table>
            <collection-media-library-thumbnail
                v-if="collection.view === 'thumbnail'"
                :collection="collection">
            </collection-media-library-thumbnail>
        </div>
        <div v-else class="lds-ellipsis">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
</template>

<script setup lang="ts">
import {onMounted, onUnmounted} from "vue";
import {MediaLibraryCollection} from "@enhavo/media-library/collection/MediaLibraryCollection";

const props = defineProps<{
    collection: MediaLibraryCollection,
}>()

const collection = props.collection;

const resizeHandler = () => {
    collection.resize();
}

onMounted(() => {
    collection.load();
    window.addEventListener('resize', resizeHandler);
})

onUnmounted(() => {
    window.removeEventListener('resize', resizeHandler);
});


function calcColumnWidth(parts: number): string
{
    let totalWidth = 0;
    for(let column of collection.columns) {
        if(column.display) {
            totalWidth += column.width;
        }
    }
    return (100 / totalWidth * parts) + '%';
}

function getColumnStyle(column: any): object
{
    let styles: object = Object.assign(
        {},
        column.style,
        {width: calcColumnWidth(column.width)} );

    return styles;
}

</script>
