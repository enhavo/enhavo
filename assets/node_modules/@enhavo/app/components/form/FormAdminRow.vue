<template>
    <component v-if="form.rowComponent !== 'form-admin-row'" :is="form.rowComponent" v-show="form.visible" />
    <div v-else v-show="form.visible">
        <form-label :form="form"></form-label>
        <form-errors :form="form"></form-errors>
        <form-widget :form="form"></form-widget>
        <form-help :form="form"></form-help>
    </div>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()

</script>
