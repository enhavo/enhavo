<template>
    <div class="view-table-col-text">
        <img :style="{height: data.height + 'px'}" :src="data.url" />
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";

@Options({})
export default class extends Vue
{
    @Prop()
    data: string;

    @Prop()
    column: any;
}
</script>
