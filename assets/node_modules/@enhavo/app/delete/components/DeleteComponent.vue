<template>
    <div class="app-view">
        <view-view></view-view>
        <flash-messages></flash-messages>
        <div class="inline-confirm-dialogue">
            <div class="message">{{ message }}</div>
            <button @click="close" class="btn">{{ button }}</button>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Options, Inject } from "vue-property-decorator";
import '@enhavo/app/assets/fonts/enhavo-icons.font'
import '@enhavo/app/assets/styles/view.scss'
import FlashMessages from "@enhavo/app/flash-message/components/FlashMessages.vue";
import DeleteApp from "@enhavo/app/delete/DeleteApp";
import Translator from "@enhavo/core/Translator";

@Options({
    components: {FlashMessages}
})
export default class extends Vue
{
    @Inject()
    deleteApp: DeleteApp

    @Inject()
    translator: Translator

    close() {
        this.deleteApp.close()
    }

    get message() {
        return this.translator.trans('enhavo_app.delete.message.success')
    }

    get button() {
        return this.translator.trans('enhavo_app.delete.label.close')
    }
}
</script>
