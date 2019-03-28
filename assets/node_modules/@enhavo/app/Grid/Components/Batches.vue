<template>
    <div v-bind:class="name">

        <select v-model="value">
            <option v-if="placeholder" value="" v-bind:checked="!value.length" v-bind:disabled="value.length">{{ placeholder }}</option>
            <option v-for="(action, index) in actions" v-bind:value="action.key" v-bind:key="'action-'+index">
                {{ action.label }}
            </option>
        </select>

        <button v-on:click="sendRequest" v-bind:disabled="!isButtonActive">Batch it!</button>
        <br />
        <button v-on:click="clearSelection" v-bind:disabled="!isButtonActive">Clear selection</button>

    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop, Watch } from "vue-property-decorator";
    import axios from 'axios';

    @Component
    export default class Batches extends Vue {
        name: string = "table-batches";

        @Prop()
        data: object;

        @Prop()
        batch: Array<object>;

        @Prop()
        selected: Array<number>;

        value: string = '';

        get placeholder(): string {
            return (this.data && this.data['placeholder']) ? this.data['placeholder'] : null;
        }
            
        get actions(): Array<object> {
            return (this.data && this.data['actions']) ? this.data['actions'] : null;
        }

        get current(): string {
            return this.batch['current'];
        }

        get hasSelection(): boolean {
            return this.selected.length > 0;
        }

        get hasValue(): boolean {
            return this.value && this.value.length > 0;
        }

        get isButtonActive(): boolean {
            return this.hasSelection && this.hasValue;
        }

        sendRequest(): void {
            if(this.hasSelection) {
                let currentBatch = this.actions.find(
                    action => action['key'] === this.value
                );
                let batchUri = currentBatch['uri'];

                axios.post(batchUri, this.selected)
                // executed on success
                .then(response => {
                })
                // executed on error
                .catch(error => {
                })
                // always executed
                .then(() => {
                    this.clearSelection();
                })

            }
        }

        clearSelection(): void {
            this.value = '';
            this.selected.splice(0, this.selected.length);
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