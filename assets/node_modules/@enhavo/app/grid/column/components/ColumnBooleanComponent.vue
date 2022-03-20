<template>
    <div class="view-table-col-text">
        <i class="icon icon-done" v-if="data"></i>
        <i class="icon icon-close" v-if="!data"></i>
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
