<template>
    <div class="modal">
        <div class="modal-form-container" v-show="!modal.loading">
            <form v-once :ref="(el) => container = el"></form>
            <div class="buttons">
                <button @click="save" class="modal-btn primary">{{ trans(modal.saveLabel) }}</button>
                <button @click="close" class="modal-btn">{{ trans(modal.closeLabel) }}</button>
            </div>
        </div>
        <loading-screen v-if="modal.loading"></loading-screen>
    </div>
</template>

<script setup lang="ts">
import { inject, watch, onMounted, ref } from 'vue'
import AjaxFormModal from "@enhavo/app/modal/model/AjaxFormModal"
import FormInitializer from "@enhavo/app/form/FormInitializer";
import Translator from "@enhavo/core/Translator";

const translator = inject<Translator>('translator');

const props = defineProps<{
    modal: AjaxFormModal
}>()

let container: HTMLElement = null;

watch(() => props.modal.element, async() => {
    const initializer = new FormInitializer();
    initializer.setElement(props.modal.element);
    $(container).html("");
    initializer.append(container);
    props.modal.form = container;
});

onMounted(() => {
    props.modal.loadForm().then(() => {});
});

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
