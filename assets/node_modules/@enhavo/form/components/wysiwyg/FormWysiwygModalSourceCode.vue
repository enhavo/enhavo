<template>
    <div class="wysiwyg-modal full-width">
        <div class="wysiwyg-modal-header">
            {{ translator.trans('enhavo_form.wysiwyg_form.command.source_code.modal.header', [], 'javascript') }}
        </div>
        <div class="wysiwyg-modal-content">
            <div class="form-row">
                <div class="formwidget-container">
                    <textarea class="wysiwyg-modal-textarea-source-code" type="text" ref="modalInput" :value="form.modal.options.hasOwnProperty('code') ? form.modal.options['code'] : ''"></textarea>
                </div>
            </div>
        </div>
        <div class="wysiwyg-modal-actions">
            <a class="btn wysiwyg-modal-action" @click.prevent="submit">{{ translator.trans('enhavo_form.wysiwyg_form.command.source_code.modal.save', [], 'javascript') }}</a>
            <a class="btn-secondary wysiwyg-modal-action" @click.prevent="cancel">{{ translator.trans('enhavo_form.wysiwyg_form.command.source_code.modal.cancel', [], 'javascript') }}</a>
        </div>
    </div>
</template>

<script setup lang="ts">
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {inject, ref} from "vue";
import {Translator} from "@enhavo/app/translation/Translator";

const translator = inject<Translator>('translator');

const props = defineProps<{
    form: WysiwygForm
}>()

const modalInput = ref(null);

function submit()
{
    props.form.modal.submit({
        'code': modalInput.value.value,
    });
}

function cancel()
{
    props.form.modal.cancel();
}

</script>
