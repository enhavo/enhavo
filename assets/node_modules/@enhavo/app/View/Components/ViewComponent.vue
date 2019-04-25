<template>
    <div class="view">
        <div v-if="data.confirm" class="modal-confirm">
            <div>
                <div class="message">{{ data.confirm.message }}</div>
                <div class="buttons">
                    <div @click="data.confirm.accept()" class="modal-btn">{{ data.confirm.acceptText }}</div>
                    <div @click="data.confirm.deny()" class="modal-btn primary">{{ data.confirm.denyText }}</div>
                </div>
            </div>
        </div>

        <div v-if="data.alert" class="alert-box">
            <div class="text">{{ data.alert }}</div>
            <div @click="data.alert = null" class="btn">Ok</div>
        </div>
        <loading-screen v-if="data.loading"></loading-screen>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import ViewData from "@enhavo/app/View/ViewData";
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import ApplicationInterface from "@enhavo/app/ApplicationInterface";
    import LoadingScreen from "@enhavo/app/Main/Components/LoadingScreen.vue";

    Vue.component('loading-screen', LoadingScreen);

    @Component()
    export default class ViewComponent extends Vue {
        name = 'view-view';

        @Prop()
        data: ViewData;

        get ok() {
            let application = <ApplicationInterface>ApplicationBag.getApplication();
            return application.getTranslator().trans('enhavo_app.view.label.ok')
        }
    }
</script>