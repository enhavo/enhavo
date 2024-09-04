<template>
    <div class="input-container date-type">
        <input :class="getClass()" :value="form.value" type="text" :ref="(el) => form.setElement(el as HTMLElement)" />
        <span v-if="form.allowClear" class="clear-button" @click="clear"><i class="icon icon-clear"></i></span>
    </div>
</template>

<script setup lang="ts">
import {DateTimeForm} from "@enhavo/form/form/model/DateTimeForm";
import $ from "jquery";
import {onMounted} from "vue";
import 'jquery-datetimepicker'
import 'jquery-datetimepicker/build/jquery.datetimepicker.min.css'

const props = defineProps<{
    form: DateTimeForm
}>()

const form = props.form;

function getClass()
{
    return form.timepicker ? 'datetimepicker' : 'datepicker'
}

function clear()
{
    $(form.element).val('');
}

let options = {
    format: 'd.m.Y',
    timepicker: form.timepicker,
    dayOfWeekStart: 1,
    scrollInput: false,
};

onMounted(() => {
    $.datetimepicker.setLocale('de');
    $(form.element).datetimepicker(options);
});

</script>
