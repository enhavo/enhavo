<template>
    <div class="view" v-if="ready">
        <div v-if="uiManager.confirmData" class="modal-confirm">
            <div>
                <div class="message">{{ uiManager.confirmData.message }}</div>
                <div class="buttons">
                    <div @click="uiManager.confirmData.accept()" class="modal-btn primary">{{ uiManager.confirmData.acceptLabel }}</div>
                    <div @click="uiManager.confirmData.deny()" class="modal-btn">{{ uiManager.confirmData.denyLabel }}</div>
                </div>
            </div>
        </div>

        <div v-if="uiManager.alertData" class="modal-confirm">
            <div class="message">{{ uiManager.alertData.message }}</div>
            <div class="buttons">
                <div @click="uiManager.alertData.accept()" class="modal-btn primary">{{ getLabel() }}</div>
            </div>
        </div>

        <loading-screen v-if="uiManager.loadingData"></loading-screen>
    </div>
</template>

<script setup lang="ts">
import {useAsyncService, useServicesReady} from "@enhavo/framework/composables/service";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {Translator} from "@enhavo/framework/translation/Translator";

const uiManager = useAsyncService<UiManager>('uiManager');
const translator = useAsyncService<Translator>('translator');
const ready = useServicesReady(uiManager, translator);

function getLabel()
{
    if (uiManager.value.alertData.acceptLabel) {
        return uiManager.value.alertData.acceptLabel;
    }

    return translator.value.trans('enhavo_app.view.label.ok', {}, 'javascript');
}
</script>
