<template>
    <component v-if="form.rowComponent !== 'form-admin-row'" :form="form" :is="form.rowComponent" />
    <div v-else v-show="form.visible" class="form-row">
        <form-label :form="form"></form-label>
        <form-errors :form="form"></form-errors>
        <div class="formwidget-container">
            <form-widget :form="form"></form-widget>
        </div>
        <form-help :form="form"></form-help>
    </div>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()

</script>
