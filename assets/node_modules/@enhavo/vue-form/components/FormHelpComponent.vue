<template>
    <div v-if="false" v-show="form.isVisible()">
        Help
    </div>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()
</script>
