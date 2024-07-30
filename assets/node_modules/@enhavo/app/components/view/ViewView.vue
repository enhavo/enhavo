<template>
    <div class="view">
        <div v-if="view.data && view.data.confirm" class="modal-confirm">
            <div>
                <div class="message">{{ view.data.confirm.message }}</div>
                <div class="buttons">
                    <div @click="view.data.confirm.accept()" class="modal-btn primary">{{ view.data.confirm.acceptText }}</div>
                    <div @click="view.data.confirm.deny()" class="modal-btn">{{ view.data.confirm.denyText }}</div>
                </div>
            </div>
        </div>

        <div v-if="view.data && view.data.alert" class="modal-confirm">
            <div class="message">{{ view.data.alert }}</div>
            <div class="buttons">
                <div @click="view.data.alert = null" class="modal-btn primary">{{ translator.trans('enhavo_app.view.label.ok') }}</div>
            </div>
        </div>

        <loading-screen v-if="view.data && view.data.loading"></loading-screen>
    </div>
</template>

<script setup lang="ts">
import {inject} from "vue";
import View from "@enhavo/app/view/View";
import Translator from "@enhavo/core/Translator";

const view = inject<View>('view');
const translator = inject<Translator>('translator');
</script>
