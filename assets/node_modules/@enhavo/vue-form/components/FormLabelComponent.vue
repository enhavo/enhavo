<template>
    <component
        v-if="form.label !== false"
        :for="getFor()"
        :is="getComponent()"
        :ref="(el) => form.setElement(el as HTMLElement)">
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

function getComponent(): string
{
    if (typeof props.element === 'string') {
        return props.element;
    }
    return 'label';
}

function getLabel(): string|boolean
{
    if (props.form.label) {
        return props.form.label;
    }
    if (props.form.labelFormat) {
        return format()
    }
    return FormUtil.humanize(props.form.name);
}

function getFor(): string|boolean
{
    if (!props.form.compound) {
        return props.form.id
    }
    return false;
}

function format(): string
{
    let label = props.form.labelFormat.replace('%id%', props.form.id);
    label = label.replace('%name%', props.form.name);
    return label;
}
</script>
