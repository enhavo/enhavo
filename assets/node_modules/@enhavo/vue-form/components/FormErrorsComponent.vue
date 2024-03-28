<template>
    <div v-if="form.errors">
        <div v-for="error in form.errors" :key="error.message">{{ error.message }}</div>
    </div>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()
</script>
