<template>
    <select
        :multiple="form.multiple"
        v-model="form.value"
        :name="form.fullName"
        :ref="(el) => form.setElement(el as HTMLElement)"
        @change="form.dispatchChange()"
        v-show="form.isVisible()"
    >
        <option v-if="form.placeholder" value="" >{{ form.placeholder }}</option>
        <component v-if="size(form.preferredChoices) > 0" :is="getChoiceComponent(choice)" v-for="choice of form.preferredChoices" :choice="choice" :preferredChoices="true" />
        <option v-if="size(form.preferredChoices) > 0" :disabled="true">{{ form.separator }}</option>
        <component :is="getChoiceComponent(choice)" v-for="choice of form.choices" :choice="choice" :preferredChoices="false" />
    </select>
</template>

<script setup lang="ts">
import {ChoiceForm} from "../model/ChoiceForm";
import * as _ from "lodash";

const props = defineProps<{
    form: ChoiceForm
}>()

function size(object: object)
{
    return _.size((object));
}

function isRequired()
{
    return !(
        props.form.required &&
        props.form.placeholder === null &&
        !props.form.placeholderInChoices &&
        !props.form.multiple && isSizeOneOrLess()
    );
}

function isSizeOneOrLess(): boolean
{
    let size: number = null;
    if (props.form.attr.hasOwnProperty('size')) {
        size = props.form.attr['size'];
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
