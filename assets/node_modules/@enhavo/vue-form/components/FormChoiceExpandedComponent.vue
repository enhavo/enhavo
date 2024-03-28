<template>
    <div :id="form.id" :ref="(el) => form.setElement(el)">
        <template v-for="child of form.children">
            <form-widget :form="child"></form-widget>
            <form-label :form="child"></form-label>
        </template>
    </div>
</template>

<script setup lang="ts">
import {ChoiceForm} from "@enhavo/vue-form/model/ChoiceForm";

const props = defineProps<{
    form: ChoiceForm
}>()
</script>
