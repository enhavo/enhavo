<template>
    <div class="modal">
        <div class="modal-form-container" v-show="!modal.loading">
            <form-form :form="modal.form"></form-form>
            <div class="buttons">
                <button @click="save" class="modal-btn primary">{{ trans(modal.saveLabel) }}</button>
                <button @click="close" class="modal-btn">{{ trans(modal.closeLabel) }}</button>
            </div>
        </div>
        <loading-screen v-if="modal.loading"></loading-screen>
    </div>
</template>

<script setup lang="ts">
import { inject } from 'vue'
import {Translator} from "@enhavo/app/translation/Translator";
import {FormModal} from "@enhavo/app/modal/model/FormModal";

const translator = inject<Translator>('translator');
const props = defineProps<{
    modal: FormModal
}>()

function save() 
{
    props.modal.submit().then((close: boolean) => {
        if (close) {
            props.modal.close();
        }
    }).catch((close: boolean) => {
        if (close) {
            props.modal.close();
        }
    });
}

function close()
{
    props.modal.close();
}

function trans(text: string)
{
    return translator.trans(text);
}
</script>
