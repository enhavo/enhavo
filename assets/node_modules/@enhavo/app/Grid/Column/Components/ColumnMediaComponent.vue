<template>
    <div class="view-table-col-text">
        <img :style="{height: data.height + 'px'}" :src="data.url" />
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";

    @Component
    export default class ColumnMediaComponent extends Vue {
        name: string = 'column-media';

        @Prop()
        data: string;

        @Prop()
        column: any;
    }
</script>






