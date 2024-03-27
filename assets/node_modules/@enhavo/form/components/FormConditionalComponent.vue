<template>
    <form-widget :form="form.get('conditional')" />
    <form-widget :form="form.get('key')" />
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()
</script>