<template>
    <div class="view-table-col-date">
        {{ data }}
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";

    @Component
    export default class ColumnDate extends Vue {
        name: string = 'view-table-col-date';

        @Prop()
        data: any;

        @Prop()
        column: any;
    }
</script>

<style lang="scss" scoped>
    .view-table-col-date { 
        background-color: cornflowerblue;
    }
</style>






