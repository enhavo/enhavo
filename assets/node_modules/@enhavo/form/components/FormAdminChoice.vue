<template>
    <component :is="getComponent()" :form="form"></component>
</template>

<script setup lang="ts">
import {ChoiceForm} from "@enhavo/vue-form/model/ChoiceForm";

const props = defineProps<{
    form: ChoiceForm
}>()

function getComponent()
{
    if (props.form.expanded) {
        return 'form-admin-choice-expanded';
    } else {
        return 'form-select2-choice-collapsed'
    }
}
</script>
