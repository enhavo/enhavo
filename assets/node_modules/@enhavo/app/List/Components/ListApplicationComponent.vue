<template>
    <div class="app-view">
        <view-view v-bind:data="view"></view-view>
        <flash-messages v-bind:messages="messages"></flash-messages>
        <action-bar v-bind:primary="actions"></action-bar>
        <list-list v-bind:data="list"></list-list>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import ActionBar from "@enhavo/app/Action/Components/ActionBar.vue";
    import '@enhavo/app/assets/styles/view.scss'
    import ViewComponent from "@enhavo/app/View/Components/ViewComponent";
    import FlashMessages from "@enhavo/app/FlashMessage/Components/FlashMessages.vue";
    import List from "@enhavo/app/List/Components/ListComponent.vue";

    @Component({
        components: {FlashMessages, ActionBar, 'list-list': List, 'view-view': ViewComponent}
    })
    export default class ListApplicationComponent extends Vue {
        name = 'list-application';

        @Prop()
        list: Array<object>;

        @Prop()
        actions: Array<object>;

        @Prop()
        view: Array<object>;

        @Prop()
        messages: Array<object>;
    }
</script>


