<template>
    <div class="app-view">
        <ui-components></ui-components>
    </div>
</template>

<script setup lang="ts">
import '@enhavo/app/assets/styles/view.scss'
import {inject, onMounted} from "vue";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {Translator} from "@enhavo/app/translation/Translator";

const uiManager = inject<UiManager>('uiManager');
const frameManager = inject<FrameManager>('frameManager');
const translator = inject<Translator>('translator');

onMounted(() => {
    frameManager.loaded();
    uiManager.alert({
        message: translator.trans('enhavo_user.error.unauthenticated', {}, 'javascript'),
        onAccept: async () => {
            uiManager.loading(true);
            if (frameManager.isRoot()) {
                window.location.reload();
            } else {
                frameManager.unload().then(() => {
                    uiManager.loading(false);
                    window.location.reload();
                });
            }
        }
    });
})

</script>