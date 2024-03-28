<template>
    <form :name="getName()" :method="getMethod()" :action="getAction()" :ref="(el) => form.setElement(el)">
        <slot name="form-before"></slot>
        <input v-if="getDifferentMethod()" type="hidden" name="_method" :value="getDifferentMethod()" />
        <slot>
            <form-widget :form="form"></form-widget>
        </slot>
        <slot name="form-after"></slot>
    </form>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()

const form = props.form;

function getMethod()
{
    if (typeof form.method === 'string') {
        let method = form.method.toLowerCase();
        if (['get', 'post'].indexOf(method) >= 0) {
            return method;
        }
    }
    return null;
}

function getDifferentMethod()
{
    if (typeof form.method === 'string') {
        let method = form.method.toLowerCase();
        if (['get', 'post'].indexOf(method) == -1) {
            return method;
        }
    }
    return null;
}

function getAction()
{
    if (typeof form.action === 'string') {
        return form.action;
    }
    return null;
}

function getName()
{
    if (typeof form.name === 'string') {
        return form.name;
    }
    return null;
}
</script>
