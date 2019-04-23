<template>
    <div class="view">
        <div v-if="data.confirm">
            {{ data.confirm.message }}
            <div @click="data.confirm.accept()">{{ data.confirm.acceptText }}</div>
            <div @click="data.confirm.deny()">{{ data.confirm.denyText }}</div>
        </div>

        <div v-if="data.alert" class="alert-box">
            <div class="text">{{ data.alert }}</div>
            <div @click="data.alert = null" class="btn">Ok</div>
        </div>

        <div v-if="data.loading">Loading</div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import ViewData from "@enhavo/app/View/ViewData";
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import ApplicationInterface from "@enhavo/app/ApplicationInterface";

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