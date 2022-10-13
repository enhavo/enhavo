<template>
    <component :is="getComponent()" :form="form"></component>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {ChoiceFormData} from "@enhavo/vue-form/data/ChoiceFormData";

@Options({})
export default class extends Vue
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
