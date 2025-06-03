<template>
    <textarea
        :id="form.id"
        :placeholder="form.placeholder"
        :name="form.fullName"
        :required="form.required"
        :disabled="form.disabled"
        :title="getTitle()"
        v-model="form.value"
        :ref="(el) => form.setElement(el as HTMLElement)"
        @change="form.dispatchChange()"
        v-show="form.isVisible()"
    >{{ form.value }}</textarea>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form"

const props = defineProps<{
    form: Form
}>()

function getTitle()
{
    if (props.form.attr.hasOwnProperty('title')) {
        return props.form.attr['title'];
    }
    return null;
}
</script>
