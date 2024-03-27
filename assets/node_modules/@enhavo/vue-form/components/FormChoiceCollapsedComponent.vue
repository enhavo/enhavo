<template>
    <select :multiple="form.multiple" v-model="form.value" :name="form.fullName" :ref="(el) => form.setElement(el)" @change="form.dispatchChange()">
        <option v-if="form.placeholder" value="" >{{ form.placeholder }}</option>
        <component v-if="size(form.preferredChoices) > 0" :is="getChoiceComponent(choice)" v-for="choice of form.preferredChoices" :choice="choice" :key="choice.label + '_preferred'" :preferredChoices="true" />
        <option v-if="size(form.preferredChoices) > 0" :disabled="true">{{ form.separator }}</option>
        <component :is="getChoiceComponent(choice)" v-for="choice of form.choices" :choice="choice" :key="choice.label" :preferredChoices="false" />
    </select>
</template>

<script setup lang="ts">
import {ChoiceForm} from "../model/ChoiceForm";
import * as _ from "lodash";

const props = defineProps<{
    form: ChoiceForm
}>()
let form = props.form;

function size(object: object)
{
    return _.size((object));
}

function isRequired()
{
    return !(
        form.required &&
        form.placeholder === null &&
        !form.placeholderInChoices &&
        !form.multiple && isSizeOneOrLess()
    );
}

function isSizeOneOrLess(): boolean
{
    let size: number = null;
    if (form.attr.hasOwnProperty('size')) {
        size = form.attr['size'];
    }

    return size === null || size <= 1;
}

function getChoiceComponent(choice: any)
{
    if (choice.choices.length > 0) {
        return 'form-choice-optgroup';
    }
    return 'form-choice-option';
}
</script>
