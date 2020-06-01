<template>
    <div class="app-view">
        <view-view v-bind:data="view"></view-view>
        <flash-messages v-bind:messages="messages"></flash-messages>
        <action-bar v-bind:primary="actions"></action-bar>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import '@enhavo/app/assets/styles/view.scss';
import ActionBar from "@enhavo/app/Action/Components/ActionBar.vue";
import FlashMessages from "@enhavo/app/FlashMessage/Components/FlashMessages.vue";
import ViewData from "@enhavo/app/View/ViewData";
import ViewComponent from "@enhavo/app/View/Components/ViewComponent";

@Component({
    components: {FlashMessages, ActionBar, 'view-view': ViewComponent}
})
export default class ApplicationComponent extends Vue
{
    @Prop()
    view: ViewData;

    @Prop()
    actions: Array<object>;

    @Prop()
    messages: Array<object>;
}
</script>

<style lang="css">
    .app-view { height: 100vh }
</style>


