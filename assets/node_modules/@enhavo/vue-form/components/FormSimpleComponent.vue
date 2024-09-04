<template>
    <input :type="form.type" :id="form.id" :name="form.fullName" v-model="form.value" :ref="(el) => form.setElement(el)" @keyup="onKeyUp()" @change="onChange()" />
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form"

const props = defineProps<{
    form: Form
}>()

const form = props.form;

function onKeyUp()
{
    if (form.type == 'text') {
        this.form.dispatchChange()
    }
}

function onChange()
{
    if (form.type != 'text') {
        this.form.dispatchChange()
    }
}

</script>
