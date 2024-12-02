<template>
    <component v-if="form.rowComponent !== 'form-admin-row'" :form="form" :is="form.rowComponent" />
    <div v-else v-show="form.isVisible()" class="form-row">
        <form-label :form="form"></form-label>
        <form-errors :form="form"></form-errors>
        <div class="formwidget-container">
            <form-widget :form="form"></form-widget>
        </div>
        <form-admin-help :form="form"></form-admin-help>
    </div>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()

</script>
