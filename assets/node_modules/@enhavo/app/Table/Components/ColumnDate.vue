<template>
    <div class="view-table-col-date" v-bind:style="{ width: width }">
        {{ data }}
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
        data: any;
    }
</script>

<style lang="scss" scoped>
    .view-table-col-date { 
        padding: 10px; margin: 10px; color: #FFF; background-color: cornflowerblue;
    }
</style>






