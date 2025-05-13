<template>
    <div :id="form.id" v-show="form.isVisible()">
        <form-rows :form="form" />
    </div>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()
</script>
