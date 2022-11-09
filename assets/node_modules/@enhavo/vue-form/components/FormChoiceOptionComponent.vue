<template>
    <option :value="choice.value">{{ choice.label }}</option>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Choice} from "@enhavo/vue-form/model/ChoiceForm"

@Options({})
export default class extends Vue
{
    @Prop()
    choice: Choice

    @Prop()
    preferredChoices: boolean
}
</script>
