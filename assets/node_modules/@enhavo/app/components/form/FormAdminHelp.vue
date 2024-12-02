<template>
    <div class="help-container" v-show="form.isVisible() && form.help">
        <i class="help-icon icon icon-help"></i>
        <div class="help-text">{{ form.help }}</div>
    </div>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()
</script>
