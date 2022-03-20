<template>
    <span v-bind:class="['menu-notification', cssClass]">
        {{ label }}
    </span>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";

@Options({})
export default class extends Vue
{
    @Prop()
    data: Object;

    get label(): string {
        return (this.data && this.data.label) ? this.data.label : '';
    }

    get cssClass(): string {
        return (this.data && this.data.class) ? this.data.class : '';
    }
}
</script>

<style lang="scss" scoped>
    .menu-notification { 
        background-color: gold;
    }
</style>






