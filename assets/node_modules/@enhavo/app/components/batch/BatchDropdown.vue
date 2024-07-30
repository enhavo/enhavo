<template>
    <div class="table-batches">
        <v-select :placeholder="getPlaceholder()" @update:modelValue="change" :options="getOptions()" :searchable="false"></v-select>
        <button @click="executeBatch" class="apply-button green"><i class="icon icon-check"></i></button>
    </div>
</template>

<script setup lang="ts">
import {inject} from "vue";
import {Translator} from "@enhavo/app/translation/Translator";
import {BatchInterface} from "@enhavo/app/batch/BatchInterface";

const translator = inject<Translator>('translator');

const props = defineProps<{
    batches: BatchInterface[],
    ids: Array<number>,
}>()

const emit = defineEmits(['executed']);

const batches = props.batches;

const ids = props.ids;
let currentBatch: BatchInterface = null;


function getOptions()
{
    let options = [];
    for (let batch of batches) {
        options.push({
            label: batch.label,
            code: batch.key
        })
    }
    return options;
}

function getPlaceholder()
{
    return translator.trans('enhavo_app.batch.label.placeholder', {}, 'javascript')
}

async function executeBatch()
{
    if (ids) {
        await currentBatch.execute(ids);
        emit('executed');
    }
}

function change(value: any)
{
    if (value != null) {
        for (let batch of batches) {
            if (value.code === batch.key) {
                currentBatch = batch;
                break;
            }
        }
    }
}
</script>
