<template>
    <div class="app-view">
        <view-view v-bind:data="view"></view-view>
        <div>
            {{ message }}
            <button @click="close">{{ button }}</button>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import '@enhavo/app/assets/styles/view.scss'
import ViewData from "@enhavo/app/View/ViewData";
import ViewComponent from "@enhavo/app/View/Components/ViewComponent";
import FlashMessages from "@enhavo/app/FlashMessage/Components/FlashMessages.vue";
import ApplicationBag from "@enhavo/app/ApplicationBag";
import DeleteApplication from "@enhavo/app/Delete/DeleteAppliction";
const application = <DeleteApplication>ApplicationBag.getApplication();

@Component({
    components: {FlashMessages, 'view-view': ViewComponent}
})
export default class AppView extends Vue {
    name = 'app-view';

    @Prop()
    messages: Array<object>;

    @Prop()
    view: ViewData;

    close() {
        application.getApp().close();
    }

    get message() {
        return application.getTranslator().trans('enhavo_app.delete.message.success')
    }

    get button() {
        return application.getTranslator().trans('enhavo_app.delete.label.close')
    }
}
</script>


