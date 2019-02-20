<template>
    <div class="view-table-col-text" v-bind:style="{ width: width }">
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
        width: string;

        @Prop()
        data: any;
    }
</script>

<style lang="scss" scoped>
    .view-table-col-text { 
        padding: 10px; margin: 10px; color: #FFF; background-color: cadetblue;
    }
</style>






