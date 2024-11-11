<template>
    <input
        :type="form.type"
        :id="form.id"
        :name="form.fullName"
        v-model="form.value"
        :ref="(el) => form.setElement(el as HTMLElement)"
        @keyup="onKeyUp()"
        @change="onChange()"
        v-show="form.isVisible()"
    />
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form"

const props = defineProps<{
    form: Form
}>()

function onKeyUp()
{
    if (props.form.type == 'text') {
        props.form.dispatchChange()
    }
}

function onChange()
{
    if (props.form.type != 'text') {
        props.form.dispatchChange()
    }
}

</script>
