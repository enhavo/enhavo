<template>
    <input
        type="radio"
        :ref="(el) => form.setElement(el)"
        :value="form.value"
        :checked="form.checked"
        :id="form.id"
        :name="form.fullName"
        :disabled="form.disabled"
        :required="form.required"
        @change="form.checked = form.element.checked; form.dispatchChange()"
    />
</template>

<script setup lang="ts">
import {RadioForm} from "@enhavo/vue-form/model/RadioForm";

const props = defineProps<{
    form: RadioForm
}>()
</script>
