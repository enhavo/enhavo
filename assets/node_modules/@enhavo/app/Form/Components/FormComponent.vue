<template>
    <div class="app-view">
        <view-view v-bind:data="view"></view-view>
        <flash-messages v-bind:messages="messages"></flash-messages>
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
import FlashMessages from "@enhavo/app/FlashMessage/Components/FlashMessages.vue";
import ViewData from "@enhavo/app/View/ViewData";
import ViewComponent from "@enhavo/app/View/Components/ViewComponent";
import ApplicationBag from "@enhavo/app/ApplicationBag";
import FormApplication from "@enhavo/app/Form/FormApplication";
const application = <FormApplication>ApplicationBag.getApplication();

@Component({
    components: {FlashMessages, ActionBar, TabContainer, TabHead, 'view-view': ViewComponent}
})
export default class AppView extends Vue {
    name = 'app-view';

    @Prop()
    view: ViewData;

    @Prop()
    actions: Array<object>;

    @Prop()
    tabs: Array<object>;

    @Prop()
    tab: string;

    @Prop()
    messages: Array<object>;

    mounted() {
        $(document).on('change', ':input', () => {
            application.getForm().changeForm();
        })
    }

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


