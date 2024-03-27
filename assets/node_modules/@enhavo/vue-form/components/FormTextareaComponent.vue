<template>
    <textarea
        :id="form.id"
        :placeholder="form.placeholder"
        :name="form.fullName"
        :required="form.required"
        :disabled="form.disabled"
        :title="getTitle()"
        v-model="form.value"
        :ref="(el) => form.setElement(el)"
        @change="form.dispatchChange()"
    >{{ form.value }}</textarea>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form"

const props = defineProps<{
    form: Form
}>()

const form = props.form;

function getTitle()
{
    if (form.attr.hasOwnProperty('title')) {
        return form.attr['title'];
    }
    return null;
}
</script>
