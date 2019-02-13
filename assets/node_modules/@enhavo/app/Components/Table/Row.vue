<template>
    <div class="view-table-row" @click="open()">
        Row
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import dispatcher from "../../ViewStack/dispatcher";
    import CreateEvent from  '../../ViewStack/Event/CreateEvent';

    @Component
    export default class Row extends Vue {
        name: string = 'view-table-row';

        @Prop()
        data: object;

        open() {
            dispatcher.dispatch(new CreateEvent({
                label: 'table',
                component: 'iframe-view',
                url: '/admin/view'
            }));
        }
    }
</script>

<style lang="scss" scoped>
    .view-table-row { 
        height: 50px;
        color: #FFF;
        background-color: midnightblue;
    }
</style>






