<template>
    <div class="app-view">
        <ui-components></ui-components>
    </div>
</template>

<script setup lang="ts">
import {UiManager} from "@enhavo/app/ui/UiManager";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {Translator} from "@enhavo/framework/translation/Translator";
import {useAsyncService, useWhenReady} from "@enhavo/framework/composables/service";

const uiManager = useAsyncService<UiManager>('uiManager');
const frameManager = useAsyncService<FrameManager>('frameManager');
const translator = useAsyncService<Translator>('translator');

useWhenReady([uiManager, frameManager, translator], (uiManager, frameManager, translator) => {
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
});

</script>