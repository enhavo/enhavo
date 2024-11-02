<template>
    <div class="tab-container">
        <div v-for="revision in tab.revisions">
            {{ formatDate(revision.date) }}
            <button @click.prevent="tab.activateRevision(revision)">{{ translator.trans('enhavo_app.revision.action.restore', {}, 'javascript') }}</button>
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
    let month = String(date.getMonth() + 1).padStart(2, '0'); // JavaScript Date months are 0-indexed
    let year = date.getFullYear();

    let hours = String(date.getHours()).padStart(2, '0');
    let minutes = String(date.getMinutes()).padStart(2, '0');

    return `${day}.${month}.${year} ${hours}:${minutes}`;
}
</script>
