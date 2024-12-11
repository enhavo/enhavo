<template>
    <div class="form-select2-choice">
        <select
            :multiple="form.multiple"
            v-model="form.value"
            :name="form.fullName"
            :ref="(el) => form.setElement(el as HTMLElement)"
            @change="form.dispatchChange()"
            v-show="form.isVisible()"
            class="select2-base-select"
        >
            <option v-if="form.placeholder" value="" >{{ form.placeholder }}</option>
            <component v-if="size(form.preferredChoices) > 0" :is="getChoiceComponent(choice)" v-for="choice of form.preferredChoices" :choice="choice" :preferredChoices="true" />
            <option v-if="size(form.preferredChoices) > 0" :disabled="true">{{ form.separator }}</option>-->
            <component :is="getChoiceComponent(choice)" v-for="choice of form.choices" :choice="choice" :preferredChoices="false" />
        </select>
    </div>
</template>

<script setup lang="ts">
import {onMounted, onUpdated, ref} from "vue";
import * as _ from "lodash";
import {ChoiceForm} from "@enhavo/vue-form/model/ChoiceForm";
import $ from "jquery";
import select2 from "select2";

const props = defineProps<{
    form: ChoiceForm
}>()

function size(object: object)
{
    return _.size((object));
}

function getChoiceComponent(choice: any)
{
    if (choice.choices.length > 0) {
        return 'form-choice-optgroup';
    }
    return 'form-choice-option';
}

onMounted(() => {
    select2($);
    $(props.form.element).select2();
});
</script>
