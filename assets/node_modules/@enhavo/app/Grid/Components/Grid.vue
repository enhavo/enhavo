<template>
    <view-table></view-table>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import Table from "./Table.vue";
    import axios from 'axios';

    @Component({
        components: {Table}
    })
    export default class Table extends Vue {
        name: string = 'view-table';
    }
</script>

<style lang="scss" scoped>
.view-table {
    width: 100%; padding: 15px; background-color: darkseagreen;
}
</style>






