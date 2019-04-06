<template>
    <div class="app-view">
        <action-bar v-bind:data="actions"></action-bar>
        <div class="tab-header">
            <template v-for="tab in tabs">
                <tab-head v-bind:selected="isCurrentTab(tab)" v-bind:tab="tab"></tab-head>
            </template>
        </div>
        <form method="POST">
            <template v-for="tab in tabs">
                <tab-container v-show="isCurrentTab(tab)" v-bind:tab="tab"></tab-container>
            </template>
        </form>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import '@enhavo/app/assets/styles/base.scss'
import '@enhavo/form/assets/styles/form.scss'
import ActionBar from "@enhavo/app/Action/Components/ActionBar.vue";
import TabHead from "@enhavo/app/Form/Components/TabHead.vue";
import TabContainer from "@enhavo/app/Form/Components/TabContainer.vue";

@Component({
    components: {ActionBar, TabContainer, TabHead}
})
export default class AppView extends Vue {
    name = 'app-view';

    @Prop()
    actions: Array<object>;

    @Prop()
    tabs: Array<object>;

    @Prop()
    tab: string;

    isCurrentTab(tab: Tab): boolean
    {
        return tab.key == this.tab;
    }
}
</script>

<style lang="scss">
.tab-header {
    height: 100px;
    background-color: dimgrey;
}
</style>


