<template>
    <div v-bind:class="name">
        <v-select :placeholder="placeholder" @input="change" :options="options" :searchable="false"></v-select>
        <button @click="executeBatch" class="apply-button"><i class="icon icon-play_arrow"></i></button>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import IndexApplication from "@enhavo/app/Index/IndexApplication";
    const application = <IndexApplication>ApplicationBag.getApplication();

    @Component
    export default class Batches extends Vue {
        name: string = "table-batches";

        @Prop()
        batches: Array<object>;

        @Prop()
        value: string;

        get options() {
            let options = [];
            for(let batch of this.batches) {
                options.push({
                    label: batch.label,
                    code: batch.key
                })
            }
            return options;
        }

        get placeholder() {
            return application.getTranslator().trans('enhavo_app.batch.label.placeholder')
        }

        executeBatch() {
            application.getGrid().executeBatch();
        }

        change(value) {
            let key = null;
            if(value != null) {
                key = value.code;
                application.getBatchManager().changeBatch(key);
            }
        }
  }
</script>