<template>
    <option :value="choice.value">{{ choice.label }}</option>
</template>

<script lang="ts">
import {Vue, Component, Prop} from "vue-property-decorator";
import {Choice} from "@enhavo/vue-form/data/ChoiceFormData"

@Component({})
export default class FormForm extends Vue
{
    @Prop()
    choice: Choice

    @Prop()
    preferredChoices: boolean
}
</script>
