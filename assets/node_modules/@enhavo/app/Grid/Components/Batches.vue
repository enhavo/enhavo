<template>
    <div v-bind:class="name">
        <select v-bind:value="value" @change="change">
            <option value="" selected>{{ translator.trans('batch.placeholder') }}</option>
            <option v-for="batch in batches" v-bind:value="batch.key" v-bind:key="batch.key">
                {{ batch.label }}
            </option>
        </select>
        <button v-on:click="execute" v-bind:disabled="!value">{{ translator.trans('batch.execute') }}</button>
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

        get translator() {
            return application.getTranslator();
        }

        execute() {
            application.getGrid().executeBatch();
        }

        change(event) {
            application.getGrid().changeBatch(event.target.value);
        }
  }
</script>

<style lang="scss" scoped>
    .view-table-batches {
        display: flex;
        margin-top: 10px;
        margin-bottom: 30px;
        background-color: salmon;

        & > * {
            margin-right: 10px;
        }
    }
</style>