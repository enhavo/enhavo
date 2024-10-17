<template>
        <div v-if="!collection.loading">
            <div class="media-library-view-switch">
                <i class="icon icon-account_box" :class="collection.view == 'thumbnail' ? 'active' : ''" @click="collection.view = 'thumbnail'"></i>
                <i class="icon icon-format_list_bulleted" :class="collection.view == 'table' ? 'active' : ''" @click="collection.view = 'table'"></i>
            </div>
            <collection-table
                v-if="collection.view === 'table'"
                :collection="collection"
                :load="false"
            >
            </collection-table>
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
import {onMounted} from "vue";
import {MediaLibraryCollection} from "@enhavo/media-library/collection/MediaLibraryCollection";

const props = defineProps<{
    collection: MediaLibraryCollection,
}>()

onMounted(() => {
    props.collection.load();
})

</script>
