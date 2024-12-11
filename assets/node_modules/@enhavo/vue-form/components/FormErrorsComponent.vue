<template>
    <ul v-if="form.errors" v-show="form.isVisible()" class="form-error-list">
        <li v-for="error in form.errors">{{ error.message }}</li>
    </ul>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()
</script>
