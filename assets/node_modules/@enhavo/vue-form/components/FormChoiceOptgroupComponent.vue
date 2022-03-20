<template>
    <optgroup :label="choice.label">
        <component :is="getChoiceComponent(subChoice)" v-for="subChoice of choice.choices" :choice="subChoice" :key="subChoice.label" />
    </optgroup>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import {Choice} from "@enhavo/vue-form/data/ChoiceFormData"

@Options({})
export default class FormForm extends Vue
{
    @Prop()
    choice: Choice

    @Prop()
    preferredChoices: boolean

    getChoiceComponent(choice: Choice)
    {
        if (choice.choices.length > 0) {
            return 'form-choice-optgroup';
        }
        return 'form-choice-option';
    }
}
</script>
