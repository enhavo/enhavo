<template>
    <div class="view">
        <div v-if="uiManager.confirm" class="modal-confirm">
            <div>
                <div class="message">{{ uiManager.confirm.message }}</div>
                <div class="buttons">
                    <div @click="uiManager.confirm.accept()" class="modal-btn primary">{{ uiManager.confirm.acceptText }}</div>
                    <div @click="uiManager.confirm.deny()" class="modal-btn">{{ uiManager.confirm.denyText }}</div>
                </div>
            </div>
        </div>

        <div v-if="uiManager.alert" class="modal-confirm">
            <div class="message">{{ uiManager.alert }}</div>
            <div class="buttons">
                <div @click="uiManager.alert = null" class="modal-btn primary">{{ translator.trans('enhavo_app.view.label.ok') }}</div>
            </div>
        </div>

        <loading-screen v-if="uiManager.loading"></loading-screen>
    </div>
</template>

<script setup lang="ts">
import {inject} from "vue";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {Translator} from "@enhavo/app/translation/Translator";

const uiManager = inject<UiManager>('uiManager');
const translator = inject<Translator>('translator');
</script>
