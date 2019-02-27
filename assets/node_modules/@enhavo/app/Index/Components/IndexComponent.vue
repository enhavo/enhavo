<template>
    <div class="app-view">
        <action-bar v-bind:data="actions"></action-bar>
        <div>{{ view_id }}</div>
        <div>{{ uuid }}</div>
        <view-table v-bind:columns="columns" v-bind:page="page"></view-table>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import Table from "../../Grid/Components/Table.vue"
import * as uuidv4 from "uuid/v4";
import ActionBar from "@enhavo/app/Action/Components/ActionBar.vue";
import '@enhavo/app/assets/styles/base.scss'

@Component({
    components: {ActionBar, 'view-table': Table}
})
export default class AppView extends Vue {
    name = 'app-view';

    @Prop()
    view_id: number;

    @Prop()
    actions: Array<object>;

    @Prop()
    columns: Array<object>;

    @Prop()
    page: Array<object>;

    get uuid() {
        return uuidv4();
    }
}
</script>

<style lang="scss">
.app {
    height: 100%;
}
</style>


