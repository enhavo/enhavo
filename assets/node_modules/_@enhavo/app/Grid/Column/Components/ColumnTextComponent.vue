<template>
    <div class="view-table-col-text">
        {{ data }}
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";

    @Component
    export default class ColumnTextComponent extends Vue {
        name: string = 'column-text';

        @Prop()
        data: string;

        @Prop()
        column: any;
    }
</script>






