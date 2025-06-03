<template>
    <input
        v-once
        v-show="form.isVisible()"
        type="checkbox"
        :ref="(el) => updateElement(el as HTMLElement)"
        :value="form.value"
        :checked="form.checked"
        :id="form.id"
        :name="form.fullName"
        :disabled="form.disabled"
        :required="form.required"
        @change="form.checked = form.element.checked; form.dispatchChange();"
    />
</template>

<script setup lang="ts">
import {RadioForm} from "@enhavo/vue-form/model/RadioForm";
import $ from "jquery";
import 'icheck'
import {watch} from "vue";

const props = defineProps<{
    form: RadioForm
}>()

watch(() => props.form.checked, () => {
    if (props.form.checked && props.form.element && !props.form.element.checked) {
        $(props.form.element).iCheck('check');
    } else if (!props.form.checked && props.form.element && props.form.element.checked) {
        $(props.form.element).iCheck('uncheck');
    }
})

function updateElement(el)
{
    props.form.element = el;

    let iCheck = $(props.form.element).iCheck({
        checkboxClass: 'icheckbox',
        radioClass: 'icheckbox'
    });

    iCheck.on('ifChanged', (event: any) => {
        props.form.checked = event.target.checked;
        props.form.dispatchChange();
    });

    if ($(props.form.element).attr('readonly')) {
        $(props.form.element).closest('.icheckbox').addClass('readonly');
    }
}

</script>
