<template>
    <div class="view-table">
        <view-table-row v-bind:columns="columns"></view-table-row>
        <view-table-row v-bind:columns="columns"></view-table-row>
        <view-table-row v-bind:columns="columns"></view-table-row>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import Row from "./Row.vue"
    
    @Component
    export default class Table extends Vue {
        name: string = 'view-table';
    
        @Prop()
        columns: any;

        get columnLabels(): Array<string> {
            let labels = [];

            for (let column of this.columns) {
                labels.push(column.label);
            }

            return labels;
        }
    }

    Vue.component('view-table-row', Row);
</script>

<style lang="scss" scoped>
.view-table {
    width: 100%; padding: 15px; background-color: darkseagreen;
}
</style>






