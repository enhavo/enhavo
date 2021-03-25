<template>
    <div class="table-batches">
        <v-select :placeholder="placeholder" @input="change" :options="options" :searchable="false"></v-select>
        <button @click="executeBatch" class="apply-button green"><i class="icon icon-check"></i></button>
    </div>
</template>

<script lang="ts">
    import { Vue, Component } from "vue-property-decorator";

    @Component()
    export default class Batches extends Vue {
        get options() {
            let options = [];
            for(let batch of this.$batchManager.data.batches) {
                options.push({
                    label: batch.label,
                    code: batch.key
                })
            }
            return options;
        }

        get placeholder() {
            return this.$translator.trans('enhavo_app.batch.label.placeholder')
        }

        executeBatch() {
            this.$grid.executeBatch();
        }

        change(value) {
            let key = null;
            if(value != null) {
                key = value.code;
                this.$batchManager.changeBatch(key);
            }
        }
  }
</script>