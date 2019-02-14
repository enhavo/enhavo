<template>
    <div class="app-view">
        <view-table v-bind:data="data"></view-table>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import Table from "./Table.vue"
import Row from "./Row.vue"

@Component
export default class AppView extends Vue {
    name = 'app-view';

    @Prop()
    data: array;
}

Vue.component('view-table', Table);
Vue.component('view-table-row', Row);
</script>

<style lang="scss">
.app {
    height: 100%;
}
</style>


