<template>
    <div class="tab-container">
        <div class="revisions view-table">
            <div class="view-table-head">
                <i class="icon icon-library_books revision-icon-placeholder"></i>
                <div class="view-table-head-columns">
                    <div class="view-table-col revision-col">
                        Date
                    </div>
                    <div class="view-table-col revision-col">
                        User
                    </div>
                </div>
            </div>
            <div v-for="revision in tab.revisions" class="view-table-row">
                <i class="icon icon-library_books"></i>
                <div class="view-table-row-columns">
                    <div class="view-table-col view-table-col-text revision-col">
                        {{ formatDate(revision.date) }}
                    </div>
                    <div class="view-table-col view-table-col-text revision-col">
                        <template v-if="revision.user">{{ revision.user[tab.userLabel] }}</template>
                        <template v-else>-</template>
                    </div>
                    <div class="view-table-col view-table-col-text revision-col-actions">
                        <div class="action action-container" @click="tab.activateRevision(revision)">
                            <div class="action-icon">
                                <i class="icon icon-restore"></i>
                            </div>
                            <div class="label">{{ translator.trans('enhavo_app.revision.action.restore', {}, 'javascript') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {RevisionTab} from "../../tab/model/RevisionTab";
import {inject} from "vue";
import {Translator} from "@enhavo/app/translation/Translator";

const translator = inject<Translator>('translator');

const props = defineProps<{
    tab: RevisionTab,
}>()

function formatDate(value: string): string
{
    let date = new Date(value);

    let day = String(date.getDate()).padStart(2, '0');
    let month = String(date.getMonth() + 1).padStart(2, '0');
    let year = date.getFullYear();

    let hours = String(date.getHours()).padStart(2, '0');
    let minutes = String(date.getMinutes()).padStart(2, '0');
    let seconds = String(date.getSeconds()).padStart(2, '0');

    return `${day}.${month}.${year} ${hours}:${minutes}:${seconds}`;
}
</script>
