<template>
    <div class="view-table-col-text">
        {{ data }}
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import dispatcher from "../../ViewStack/dispatcher";
    import CreateEvent from '../../ViewStack/Event/CreateEvent';

    @Component
    export default class ColumnText extends Vue {
        name: string = 'view-table-col-text';

        @Prop()
        data: string;

        @Prop()
        column: any;
    }
</script>

<style lang="scss" scoped>
    .view-table-col-text { 
        background-color: cadetblue;
    }
</style>






