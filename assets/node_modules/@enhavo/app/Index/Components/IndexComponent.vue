<template>
    <div class="app-view">
        <action-bar v-bind:data="actions"></action-bar>
        <grid-grid v-bind:data="grid"></grid-grid>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import Grid from "@enhavo/app/Grid/Components/Grid.vue";
import ActionBar from "@enhavo/app/Action/Components/ActionBar.vue";
import * as uuidv4 from "uuid/v4";
import '@enhavo/app/assets/styles/base.scss'

@Component({
    components: {ActionBar, 'grid-grid': Grid}
})
export default class AppView extends Vue {
    name = 'app-view';

    @Prop()
    view_id: number;

    @Prop()
    actions: Array<object>;

    @Prop()
    grid: Array<object>;
}
</script>

<style lang="scss">
.app {
    height: 100%;
}
</style>


