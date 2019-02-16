<template>
    <div class="app-view">
        <div>{{ view_id }}</div>
        <div>{{ uuid }}</div>
        <view-table v-bind:columns="columns"></view-table>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import Table from "./Table.vue"
import * as uuidv4 from "uuid/v4";

@Component
export default class AppView extends Vue {
    name = 'app-view';

    @Prop()
    view_id: number;

    @Prop()
    columns: Array<object>;

    get uuid() {
        return uuidv4();
    }
}

Vue.component('view-table', Table);
</script>

<style lang="scss">
.app {
    height: 100%;
}
</style>


