<template>
    <div class="wysiwyg-modal full-width">
        <div class="wysiwyg-modal-header">
            Source Code
        </div>
        <div class="wysiwyg-modal-content">
            <div class="form-row">
                <div class="formwidget-container">
                    <textarea type="text" ref="modalInput" :value="form.modal.options.hasOwnProperty('code') ? form.modal.options['code'] : ''"></textarea>
                </div>
            </div>
        </div>
        <div class="wysiwyg-modal-actions">
            <a class="btn wysiwyg-modal-action" @click.prevent="submit">Save</a>
            <a class="btn-secondary wysiwyg-modal-action" @click.prevent="cancel">Cancel</a>
        </div>
    </div>
</template>

<script setup lang="ts">
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {ref} from "vue";

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
