<template>
    <component
        v-if="form.label !== false"
        :for="getFor()"
        :is="getComponent()"
        v-show="form.visible"
        :ref="(el) => form.setElement(el)">
        {{ getLabel() }}
    </component>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";
import {FormUtil} from "../form/FormUtil";

const props = defineProps<{
    form: Form,
    element?: string
}>()

const form = props.form;
const element = props.element;

function getComponent(): string
{
    if (typeof element === 'string') {
        return element;
    }
    return 'label';
}

function getLabel(): string|boolean
{
    if (form.label) {
        return form.label;
    }
    if (form.labelFormat) {
        return format()
    }
    return FormUtil.humanize(form.name);
}

function getFor(): string|boolean
{
    if (!form.compound) {
        return form.id
    }
    return false;
}

function format(): string
{
    let label = form.labelFormat.replace('%id%', form.id);
    label = label.replace('%name%', form.name);
    return label;
}
</script>
