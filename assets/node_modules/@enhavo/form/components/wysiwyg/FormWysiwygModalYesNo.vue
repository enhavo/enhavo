<template>
    <div class="wysiwyg-modal">
        <div class="wysiwyg-modal-header">
            {{ form.modal.options.hasOwnProperty('headline') ? form.modal.options['headline'] : '' }}
        </div>
        <div class="wysiwyg-modal-content"
             v-html="form.modal.options.hasOwnProperty('prompt') ? form.modal.options['prompt'] : translator.trans('enhavo_form.wysiwyg_form.command.modal_yes_no.default_prompt', [], 'javascript')"
        >
        </div>
        <div class="wysiwyg-modal-actions">
            <a class="btn wysiwyg-modal-action" @click.prevent="submit">
                {{ form.modal.options.hasOwnProperty('label_yes') ? form.modal.options['label_yes'] : translator.trans('enhavo_form.wysiwyg_form.command.modal_yes_no.default_yes', [], 'javascript') }}
            </a>
            <a class="btn-secondary wysiwyg-modal-action" @click.prevent="cancel">
                {{ form.modal.options.hasOwnProperty('label_no') ? form.modal.options['label_no'] : translator.trans('enhavo_form.wysiwyg_form.command.modal_yes_no.default_no', [], 'javascript') }}
            </a>
        </div>
    </div>
</template>

<script setup lang="ts">
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {inject} from "vue";
import {Translator} from "@enhavo/app/translation/Translator";

const translator = inject<Translator>('translator');

const props = defineProps<{
    form: WysiwygForm
}>()

function submit()
{
    props.form.modal.submit({});
}

function cancel()
{
    props.form.modal.cancel();
}

</script>
