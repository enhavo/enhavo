<template>
    <input v-once
        type="radio"
        :ref="(el) => updateElement(el)"
        :value="form.value"
        :checked="form.checked"
        :id="form.id"
        :name="form.fullName"
        :disabled="form.disabled"
        :required="form.required"
        @change="form.checked = form.element.checked; form.dispatchChange()"
    />
</template>

<script setup lang="ts">
import {RadioForm} from "@enhavo/vue-form/model/RadioForm";
import $ from "jquery";
import 'icheck'

const props = defineProps<{
    form: RadioForm
}>()

const form = props.form;


function updateElement(el)
{
    form.element = el;

    let iCheck = $(form.element).iCheck({
        checkboxClass: 'icheckbox',
        radioClass: 'icheckbox'
    });

    let $formRow = $(form.element).closest('[data-form-row]');
    let $count = $formRow.find('[data-selected-count]');

    if ($count.length) { // if there is a data-selected-count element, then multiple must have been true
        iCheck.on('ifChanged', (event: any) => {
            let checked = $formRow.find('input:checked');
            let count = checked.length;
            $count.text('(' + count + ')');
        });
    }

    if ($(form.element).attr('readonly')) {
        $(form.element).closest('.icheckbox').addClass('readonly');
    }
}

</script>
