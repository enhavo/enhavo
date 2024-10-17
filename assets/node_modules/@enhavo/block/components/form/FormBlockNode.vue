<template>
    <form-widget :form="form.get('name')" />
    <form-widget :form="form.get('position')" />
    <form-widget :form="form.get('block')" />
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form,
}>()
</script>
