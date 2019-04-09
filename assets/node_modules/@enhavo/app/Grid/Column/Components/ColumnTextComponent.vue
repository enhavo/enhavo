<template>
    <div class="view-table-col-text">
        {{ data }}
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";

    @Component
    export default class ColumnText extends Vue {
        name: string = 'view-table-col-text';

        @Prop()
        data: string;

        @Prop()
        column: any;
    }
</script>






