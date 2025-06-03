<template>
    <div v-show="form.isVisible()">
        {{ form.help }}
    </div>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()
</script>
