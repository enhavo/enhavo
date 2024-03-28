<template>
    <component v-if="form.rowComponent !== 'form-row'" :is="form.rowComponent" v-show="form.visible" />
    <div v-else v-show="form.visible">
        <form-label :form="form"></form-label>
        <form-errors :form="form"></form-errors>
        <form-widget :form="form"></form-widget>
        <form-help :form="form"></form-help>
    </div>
</template>

<script setup lang="ts">
import {CheckboxForm} from "@enhavo/vue-form/model/CheckboxForm";

const props = defineProps<{
    form: CheckboxForm
}>()
</script>
