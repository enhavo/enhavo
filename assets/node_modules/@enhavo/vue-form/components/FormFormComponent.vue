<template>
    <form :name="getName()" :method="getMethod()" :action="getAction()" :ref="(el) => form.setElement(<HTMLElement>el)" v-show="form.isVisible()">
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
import {onUpdated} from "vue";

const props = defineProps<{
    form: Form
}>()

function getMethod()
{
    if (typeof props.form.method === 'string') {
        let method = props.form.method.toLowerCase();
        if (['get', 'post'].indexOf(method) >= 0) {
            return method;
        }
    }
    return null;
}

function getDifferentMethod()
{
    if (typeof props.form.method === 'string') {
        let method = props.form.method.toLowerCase();
        if (['get', 'post'].indexOf(method) == -1) {
            return method;
        }
    }
    return null;
}

function getAction()
{
    if (typeof props.form.action === 'string') {
        return props.form.action;
    }
    return null;
}

function getName()
{
    if (typeof props.form.name === 'string') {
        return props.form.name;
    }
    return null;
}
</script>
