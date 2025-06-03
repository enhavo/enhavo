<template>
    <component v-if="form.rowComponent !== 'form-admin-row'" :form="form" :is="form.rowComponent" />
    <div v-else v-show="form.isVisible()" class="form-row">
        <div class="label-help-container">
            <form-label :form="form"></form-label>
            <form-admin-help :form="form"></form-admin-help>
        </div>
        <div class="formwidget-container">
            <form-widget :form="form"></form-widget>
        </div>
        <form-errors :form="form"></form-errors>
    </div>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()

</script>
