<template>
    <div class="app-view">
        <view-view v-bind:data="view"></view-view>
        <action-bar v-bind:data="actions"></action-bar>
        <grid-grid v-bind:data="grid"></grid-grid>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import Grid from "@enhavo/app/Grid/Components/Grid.vue";
import ActionBar from "@enhavo/app/Action/Components/ActionBar.vue";
import '@enhavo/app/assets/styles/view.scss'
import ViewData from "@enhavo/app/View/ViewData";
import ViewComponent from "@enhavo/app/View/Components/ViewComponent";

@Component({
    components: {ActionBar, 'grid-grid': Grid, 'view-view': ViewComponent}
})
export default class AppView extends Vue {
    name = 'app-view';

    @Prop()
    view: ViewData;

    @Prop()
    actions: Array<object>;

    @Prop()
    grid: Array<object>;
}
</script>


