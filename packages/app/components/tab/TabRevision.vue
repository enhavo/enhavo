<template>
    <div class="tab-container">
        <div class="revisions view-table">
            <p v-if="tab.diff && tab.revisions.length" class="revision-hint">{{ tab.diffTexts.hint }}</p>
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
            <template v-for="revision in tab.revisions" :key="revision.id">
                <div class="view-table-row" :class="{'revision-row-expandable': tab.diff}" @click="tab.toggle(revision)">
                    <i v-if="tab.diff" class="icon" :class="tab.openId === revision.id ? 'icon-keyboard_arrow_down' : 'icon-keyboard_arrow_right'"></i>
                    <i v-else class="icon icon-library_books"></i>
                    <div class="view-table-row-columns">
                        <div class="view-table-col view-table-col-text revision-col">
                            {{ formatDate(revision.date) }}
                        </div>
                        <div class="view-table-col view-table-col-text revision-col">
                            <template v-if="revision.user">{{ revision.user[tab.userLabel] }}</template>
                            <template v-else>-</template>
                        </div>
                        <div v-if="tab.restore && revision.url" class="view-table-col view-table-col-text revision-col-actions">
                            <div class="action action-container" @click.stop="tab.activateRevision(revision)">
                                <div class="action-icon">
                                    <i class="icon icon-restore"></i>
                                </div>
                                <div class="label">{{ translator.trans('enhavo_app.revision.action.restore', {}, 'javascript') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="tab.diff && tab.openId === revision.id" class="revision-diff">
                    <p v-if="tab.loadingId === revision.id">{{ tab.diffTexts.loading }}</p>
                    <p v-else-if="tab.errorId === revision.id">{{ tab.diffTexts.error }}</p>
                    <p v-else-if="tab.first[revision.id]">{{ tab.diffTexts.first }}</p>
                    <p v-else-if="!tab.getSections(revision.id).length">{{ tab.diffTexts.noChanges }}</p>
                    <table v-else class="revision-diff-table">
                        <colgroup>
                            <col class="revision-diff-col-field">
                            <col class="revision-diff-col-value">
                            <col class="revision-diff-col-value">
                        </colgroup>
                        <thead>
                            <tr>
                                <th></th>
                                <!-- newer state left, older state right -->
                                <th>{{ tab.getColumnLabel(revision.id, 'newer') }}</th>
                                <th>{{ tab.getColumnLabel(revision.id, 'older') }}</th>
                            </tr>
                        </thead>
                        <tbody v-for="section in tab.getSections(revision.id)">
                            <tr v-if="section.label">
                                <th colspan="3" class="revision-diff-section">
                                    {{ section.label }}
                                    <template v-if="section.status === 'added'"> ({{ tab.diffTexts.added }})</template>
                                    <template v-if="section.status === 'removed'"> ({{ tab.diffTexts.removed }})</template>
                                </th>
                            </tr>
                            <tr v-for="change in section.changes" class="revision-diff-change">
                                <td>{{ change.field || '–' }}</td>
                                <template v-if="change.old !== null && change.new !== null">
                                    <td class="revision-diff-value"><span v-for="part in tab.diffWords(change.old, change.new).new" :class="{'revision-diff-added': part.changed}">{{ part.text }}</span></td>
                                    <td class="revision-diff-value"><span v-for="part in tab.diffWords(change.old, change.new).old" :class="{'revision-diff-removed': part.changed}">{{ part.text }}</span></td>
                                </template>
                                <template v-else>
                                    <td class="revision-diff-value"><span v-if="change.new !== null" class="revision-diff-added">{{ change.new }}</span><template v-else>–</template></td>
                                    <td class="revision-diff-value"><span v-if="change.old !== null" class="revision-diff-removed">{{ change.old }}</span><template v-else>–</template></td>
                                </template>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {RevisionTab} from "../../tab/model/RevisionTab";
import {inject} from "vue";
import {Translator} from "@enhavo/framework/translation/Translator";

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
