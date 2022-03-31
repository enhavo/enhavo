<template>
    <div class="table-batches">
        <v-select :placeholder="placeholder" @update:modelValue="change" :options="options" :searchable="false"></v-select>
        <button @click="executeBatch" class="apply-button green"><i class="icon icon-check"></i></button>
    </div>
</template>

<script lang="ts">
import {Vue, Options, Inject} from "vue-property-decorator";
import BatchManager from "@enhavo/app/grid/batch/BatchManager";
import Grid from "@enhavo/app/grid/Grid";
import Translator from "@enhavo/core/Translator";

@Options({})
export default class extends Vue
{
    @Inject()
    batchManager: BatchManager;

    @Inject()
    grid: Grid;

    @Inject()
    translator: Translator;

    get options() {
        let options = [];
        for(let batch of this.batchManager.data.batches) {
            options.push({
                label: batch.label,
                code: batch.key
            })
        }
        return options;
    }

    get placeholder() {
        return this.translator.trans('enhavo_app.batch.label.placeholder')
    }

    executeBatch() {
        this.grid.executeBatch();
    }

    change(value: any) {
        let key = null;
        if(value != null) {
            key = value.code;
            this.batchManager.changeBatch(key);
        }
    }
}
</script>