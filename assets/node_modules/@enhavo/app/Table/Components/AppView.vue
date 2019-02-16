<template>
    <div class="app-view">
        {{ uuid }}
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


