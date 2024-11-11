<template>
    <div class="input-container date-type" v-show="form.isVisible()">
        <input :class="getClass()" :name="form.fullName" :value="form.value" type="text" :ref="(el) => form.setElement(el as HTMLElement)" />
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

function getClass()
{
    return props.form.timepicker ? 'datetimepicker' : 'datepicker'
}

function clear()
{
    $(props.form.element).val('');
}

let options = {
    format: props.form.timepicker ? 'd.m.Y H:i' : 'd.m.Y',
    timepicker: props.form.timepicker,
    dayOfWeekStart: 1,
    scrollInput: false,
};

onMounted(() => {
    $.datetimepicker.setLocale('de');
    $(props.form.element).datetimepicker(options);
});

</script>
