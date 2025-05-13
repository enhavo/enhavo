<template>
    <optgroup :label="choice.label" :ref="(el) => choice.element = (el as HTMLOptGroupElement)">
        <component :is="getChoiceComponent(subChoice)" v-for="subChoice of choice.choices" :choice="subChoice" />
    </optgroup>
</template>

<script setup lang="ts">
import {Choice} from "@enhavo/vue-form/model/ChoiceForm"

const props = defineProps<{
    choice: Choice
    preferredChoices?: boolean
}>()

function getChoiceComponent(choice: Choice)
{
    if (choice.choices.length > 0) {
        return 'form-choice-optgroup';
    }
    return 'form-choice-option';
}

</script>
