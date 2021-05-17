<template>
    <component :is="getComponent()" :form="form"></component>
</template>

<script lang="ts">
import {Vue, Component, Prop} from "vue-property-decorator";
import {ChoiceFormData} from "@enhavo/vue-form/data/ChoiceFormData";

@Component({})
export default class FormChoiceComponent extends Vue
{
    @Prop()
    form: ChoiceFormData

    getComponent()
    {
        if (this.form.expanded) {
            return 'form-choice-expanded';
        } else {
            return 'form-choice-collapsed'
        }
    }
}
</script>
