<template>
    <input :type="form.type" :id="form.id" :name="form.fullName" v-model="form.value" :ref="(el) => form.setElement(el)" @change="form.dispatchChange()" />
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form"

const props = defineProps<{
    form: Form
}>()

</script>
