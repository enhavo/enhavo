<template>
    <button type="submit" v-show="form.isVisible()">{{ form.label }}</button>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()
</script>
