<template>
    <div class="table-batches">
        <v-select :placeholder="getPlaceholder()" @update:modelValue="change" :options="getOptions()" :searchable="false"></v-select>
        <button @click="executeBatch" class="apply-button green"><i class="icon icon-check"></i></button>
    </div>
</template>

<script setup lang="ts">
import {inject} from "vue";
import BatchManager from "@enhavo/app/grid/batch/BatchManager";
import Grid from "@enhavo/app/grid/Grid";
import Translator from "@enhavo/core/Translator";

const batchManager = inject<BatchManager>('batchManager');
const grid = inject<Grid>('grid');
const translator = inject<Translator>('translator');


function getOptions() 
{
    let options = [];
    for (let batch of batchManager.data.batches) {
        options.push({
            label: batch.label,
            code: batch.key
        })
    }
    return options;
}

function getPlaceholder() 
{
    return translator.trans('enhavo_app.batch.label.placeholder')
}

function executeBatch() 
{
    grid.executeBatch();
}

function change(value: any) 
{
    let key = null;
    if(value != null) {
        key = value.code;
        batchManager.changeBatch(key);
    }
}
</script>
