<template>
    <div>
        <div class="icon"></div>
        <div class="label"></div>
    </div>

</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import DropdownAction from "@enhavo/app/action/model/DropdownAction";

    @Component
    export default class DropdownActionComponent extends Vue {
        name: 'dropdown-action';
        @Prop()
        data: DropdownAction;
    }
</script>

<style scoped>

</style>