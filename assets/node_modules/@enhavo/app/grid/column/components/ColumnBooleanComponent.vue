<template>
    <div class="view-table-col-text">
        <i class="icon icon-done" v-if="data"></i>
        <i class="icon icon-close" v-if="!data"></i>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";

    @Component
    export default class ColumnBooleanComponent extends Vue {
        name: string = 'column-boolean';

        @Prop()
        data: string;

        @Prop()
        column: any;
    }
</script>






