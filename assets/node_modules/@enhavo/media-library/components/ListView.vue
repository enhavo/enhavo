<template>
    <pagination v-if="mediaLibrary.hasPagination()"></pagination>
    <div class="view-table">
        <div class="view-table-head">
            <div class="view-table-head-columns">
                <div class="view-table-col"
                     v-for="column in mediaLibrary.data.columns"
                     @click="onSort(column)"
                     :class="isSorted(column)?'sorted':''"
                     style="width: 25%">
                    {{ column.label }}
                    <i aria-hidden="true"
                       class="icon sortable"
                       :class="sortClass(column)"></i>
                </div>
            </div>

        </div>
        <div class="scroll-container">
            <file-list
                v-for="file of mediaLibrary.data.files"
                :file="file"
            ></file-list>
        </div>
    </div>
    <pagination v-if="mediaLibrary.hasPagination()"></pagination>
</template>

<script lang="ts">
import {Inject, Options, Vue, Watch} from "vue-property-decorator";
import '@enhavo/app/assets/styles/view.scss';
import MediaLibrary from "@enhavo/media-library/MediaLibrary";
import {Column} from "@enhavo/media-library/Data";

@Options({})
export default class extends Vue {

    @Inject()
    mediaLibrary: MediaLibrary;

    onSort(column: Column) {
        this.mediaLibrary.setSortColumn(column);
    }

    isSorted(column: Column) {
        return this.mediaLibrary.isSortedColumn(column);
    }

    sortClass(column: Column) {
        if (this.mediaLibrary.isSortedColumn(column)) {
            return this.mediaLibrary.data.sortColumn.direction === 'asc' ? 'icon-arrow_upward' : 'icon-arrow_downward';
        }
    }

}
</script>
