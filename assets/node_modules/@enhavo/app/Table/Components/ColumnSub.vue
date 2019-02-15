<template>
    <div class="view-table-col-sub" v-bind:style="{ width: width }">
        <template v-for="row in rows">
            <component 
                class="view-table-col-child" 
                v-bind:is="row.component" 
                :key="row.key"
                v-bind:label="label"></component>
        </template>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import dispatcher from "../../ViewStack/dispatcher";
    import CreateEvent from '../../ViewStack/Event/CreateEvent';

    @Component
    export default class ColumnDate extends Vue {
        name: string = 'view-table-col-date';

        @Prop()
        width: string;

        @Prop()
        label: string;

        @Prop()
        rows: Array<object>;
    }
</script>

<style lang="scss" scoped>
    .view-table-col-sub { 
        color: #FFF; background-color: seagreen;
    }
</style>






