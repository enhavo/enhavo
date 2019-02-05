<template>
    <div class="view-table">
        Table
        <br />
        --> rows
        <view-table-row></view-table-row>
        <view-table-row></view-table-row>
        <view-table-row></view-table-row>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    
    @Component
    export default class Table extends Vue {
        name: string = 'view-table';

        @Prop()
        data: object;
    }
</script>

<style lang="scss" scoped>
.view-table {
    width: 100%;
    background-color: lawngreen;
}
</style>






