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
import { Vue, Component } from "vue-property-decorator";
import '@enhavo/app/assets/fonts/enhavo-icons.font'
import '@enhavo/app/assets/styles/view.scss'
import FlashMessages from "@enhavo/app/FlashMessage/Components/FlashMessages.vue";
@Component({
    components: {FlashMessages}
})
export default class AppView extends Vue {
    close() {
        this.$deleteApp.close()
    }

    get message() {
        return this.$translator.trans('enhavo_app.delete.message.success')
    }

    get button() {
        return this.$translator.trans('enhavo_app.delete.label.close')
    }
}
</script>
