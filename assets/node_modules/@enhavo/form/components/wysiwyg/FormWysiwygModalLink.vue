<template>
    <div class="wysiwyg-modal">
        <div class="wysiwyg-modal-header">
            {{ translator.trans('enhavo_form.wysiwyg_form.command.link.modal.header', [], 'javascript') }}
        </div>
        <div class="wysiwyg-modal-content">
            <div class="form-row">
                <label for="modal-url">{{ translator.trans('enhavo_form.wysiwyg_form.command.link.modal.label_url', [], 'javascript') }}</label>
                <div class="formwidget-container">
                    <input type="text" id="modal-url" ref="modalUrlInput" :value="form.modal.options.hasOwnProperty('url') ? form.modal.options['url'] : ''">
                </div>
            </div>
            <div class="form-row">
                <label for="modal-target">{{ translator.trans('enhavo_form.wysiwyg_form.command.link.modal.label_target', [], 'javascript') }}</label>
                <div class="formwidget-container">
                    <select id="modal-target" ref="modalTargetInput" class="select2-base-select">
                        <option value="_self" :selected="!(form.modal.options.hasOwnProperty('target') && form.modal.options['target'] === '_blank')">{{ translator.trans('enhavo_form.wysiwyg_form.command.link.modal.option_target_self', [], 'javascript') }}</option>
                        <option value="_blank" :selected="form.modal.options.hasOwnProperty('target') && form.modal.options['target'] === '_blank'">{{ translator.trans('enhavo_form.wysiwyg_form.command.link.modal.option_target_blank', [], 'javascript') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="wysiwyg-modal-actions">
            <a class="btn wysiwyg-modal-action" @click.prevent="submit">{{ translator.trans('enhavo_form.wysiwyg_form.command.link.modal.save', [], 'javascript') }}</a>
            <a class="btn wysiwyg-modal-action" @click.prevent="unlink" v-if="(form.modal.options.hasOwnProperty('url') && form.modal.options['url'] != null)">{{ translator.trans('enhavo_form.wysiwyg_form.command.link.modal.unlink', [], 'javascript') }}</a>
            <a class="btn-secondary wysiwyg-modal-action" @click.prevent="cancel">{{ translator.trans('enhavo_form.wysiwyg_form.command.link.modal.cancel', [], 'javascript') }}</a>
        </div>
    </div>
</template>

<script setup lang="ts">
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {inject, onMounted, ref} from "vue";
import select2 from "select2";
import $ from 'jquery';
import {Translator} from "@enhavo/app/translation/Translator";

const translator = inject<Translator>('translator');

const props = defineProps<{
    form: WysiwygForm
}>()

const modalUrlInput = ref(null);
const modalTargetInput = ref(null);

onMounted(() => {
    select2($);
    $(modalTargetInput.value).select2();
});

function submit()
{
    props.form.modal.submit({
        'unlink': false,
        'url': modalUrlInput.value.value,
        'target': modalTargetInput.value.value,
    });
}

function unlink()
{
    props.form.modal.submit({
        'unlink': true,
        'url': modalUrlInput.value.value,
        'target': modalTargetInput.value.value,
    });
}

function cancel()
{
    props.form.modal.cancel();
}

</script>
