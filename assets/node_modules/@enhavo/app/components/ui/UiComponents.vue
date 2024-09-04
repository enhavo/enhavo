<template>
    <div class="view">
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
import {inject} from "vue";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {Translator} from "@enhavo/app/translation/Translator";

const uiManager = inject<UiManager>('uiManager');
const translator = inject<Translator>('translator');

function getLabel()
{
    if (uiManager.alertData.acceptLabel) {
        return uiManager.alertData.acceptLabel;
    }

    return translator.trans('enhavo_app.view.label.ok');
}
</script>
